<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_inventory_movements_after_insert
AFTER INSERT ON inventory_movements
FOR EACH ROW
BEGIN
    DECLARE current_qty INT DEFAULT 0;
    DECLARE target_location_id BIGINT DEFAULT NULL;

    IF NEW.type = 'in' THEN
        IF NEW.destination_storage_location_id IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Destination storage location required for incoming movement';
        END IF;

        INSERT INTO stocks (product_id, storage_location_id, quantity, created_at, updated_at)
        VALUES (NEW.product_id, NEW.destination_storage_location_id, NEW.quantity, NOW(), NOW())
        ON DUPLICATE KEY UPDATE quantity = quantity + NEW.quantity, updated_at = NOW();

    ELSEIF NEW.type = 'out' THEN
        IF NEW.source_storage_location_id IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Source storage location required for outgoing movement';
        END IF;

        SELECT quantity INTO current_qty
        FROM stocks
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id
        LIMIT 1;

        IF current_qty IS NULL THEN
            SET current_qty = 0;
        END IF;

        IF current_qty < NEW.quantity THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock for outgoing movement';
        END IF;

        UPDATE stocks
        SET quantity = quantity - NEW.quantity, updated_at = NOW()
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id;

    ELSEIF NEW.type = 'transfer' THEN
        IF NEW.source_storage_location_id IS NULL OR NEW.destination_storage_location_id IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Both source and destination required for transfer';
        END IF;

        SELECT quantity INTO current_qty
        FROM stocks
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id
        LIMIT 1;

        IF current_qty IS NULL THEN
            SET current_qty = 0;
        END IF;

        IF current_qty < NEW.quantity THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock for transfer';
        END IF;

        UPDATE stocks
        SET quantity = quantity - NEW.quantity, updated_at = NOW()
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id;

        INSERT INTO stocks (product_id, storage_location_id, quantity, created_at, updated_at)
        VALUES (NEW.product_id, NEW.destination_storage_location_id, NEW.quantity, NOW(), NOW())
        ON DUPLICATE KEY UPDATE quantity = quantity + NEW.quantity, updated_at = NOW();

    ELSEIF NEW.type = 'adjustment' THEN
        IF NEW.destination_storage_location_id IS NULL AND NEW.source_storage_location_id IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Storage location required for adjustment';
        END IF;

        SET target_location_id = COALESCE(NEW.destination_storage_location_id, NEW.source_storage_location_id);

        INSERT INTO stocks (product_id, storage_location_id, quantity, created_at, updated_at)
        VALUES (NEW.product_id, target_location_id, NEW.quantity, NOW(), NOW())
        ON DUPLICATE KEY UPDATE quantity = quantity + NEW.quantity, updated_at = NOW();
    END IF;
END
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_inventory_movements_after_insert');
    }
};
