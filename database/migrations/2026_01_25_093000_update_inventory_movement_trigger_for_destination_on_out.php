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
CREATE OR REPLACE FUNCTION apply_inventory_movement()
RETURNS TRIGGER AS $$
DECLARE
    current_qty INT := 0;
    target_location_id BIGINT := NULL;
BEGIN
    IF NEW.type = 'in' THEN
        IF NEW.destination_storage_location_id IS NULL THEN
            RAISE EXCEPTION 'Destination storage location required for incoming movement';
        END IF;

        INSERT INTO stocks (product_id, storage_location_id, quantity, created_at, updated_at)
        VALUES (NEW.product_id, NEW.destination_storage_location_id, NEW.quantity, NOW(), NOW())
        ON CONFLICT (product_id, storage_location_id)
        DO UPDATE SET quantity = stocks.quantity + EXCLUDED.quantity, updated_at = NOW();

    ELSIF NEW.type = 'out' THEN
        IF NEW.source_storage_location_id IS NULL THEN
            RAISE EXCEPTION 'Source storage location required for outgoing movement';
        END IF;

        SELECT quantity INTO current_qty
        FROM stocks
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id
        LIMIT 1;

        IF current_qty IS NULL THEN
            current_qty := 0;
        END IF;

        IF current_qty < NEW.quantity THEN
            RAISE EXCEPTION 'Not enough stock for outgoing movement';
        END IF;

        UPDATE stocks
        SET quantity = quantity - NEW.quantity, updated_at = NOW()
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id;

        -- If destination provided, treat as movement into that location.
        IF NEW.destination_storage_location_id IS NOT NULL THEN
            INSERT INTO stocks (product_id, storage_location_id, quantity, created_at, updated_at)
            VALUES (NEW.product_id, NEW.destination_storage_location_id, NEW.quantity, NOW(), NOW())
            ON CONFLICT (product_id, storage_location_id)
            DO UPDATE SET quantity = stocks.quantity + EXCLUDED.quantity, updated_at = NOW();
        END IF;

    ELSIF NEW.type = 'transfer' THEN
        IF NEW.source_storage_location_id IS NULL OR NEW.destination_storage_location_id IS NULL THEN
            RAISE EXCEPTION 'Both source and destination required for transfer';
        END IF;

        SELECT quantity INTO current_qty
        FROM stocks
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id
        LIMIT 1;

        IF current_qty IS NULL THEN
            current_qty := 0;
        END IF;

        IF current_qty < NEW.quantity THEN
            RAISE EXCEPTION 'Not enough stock for transfer';
        END IF;

        UPDATE stocks
        SET quantity = quantity - NEW.quantity, updated_at = NOW()
        WHERE product_id = NEW.product_id
          AND storage_location_id = NEW.source_storage_location_id;

        INSERT INTO stocks (product_id, storage_location_id, quantity, created_at, updated_at)
        VALUES (NEW.product_id, NEW.destination_storage_location_id, NEW.quantity, NOW(), NOW())
        ON CONFLICT (product_id, storage_location_id)
        DO UPDATE SET quantity = stocks.quantity + EXCLUDED.quantity, updated_at = NOW();

    ELSIF NEW.type = 'adjustment' THEN
        IF NEW.destination_storage_location_id IS NULL AND NEW.source_storage_location_id IS NULL THEN
            RAISE EXCEPTION 'Storage location required for adjustment';
        END IF;

        target_location_id := COALESCE(NEW.destination_storage_location_id, NEW.source_storage_location_id);

        INSERT INTO stocks (product_id, storage_location_id, quantity, created_at, updated_at)
        VALUES (NEW.product_id, target_location_id, NEW.quantity, NOW(), NOW())
        ON CONFLICT (product_id, storage_location_id)
        DO UPDATE SET quantity = stocks.quantity + EXCLUDED.quantity, updated_at = NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_inventory_movements_after_insert ON inventory_movements;

CREATE TRIGGER trg_inventory_movements_after_insert
AFTER INSERT ON inventory_movements
FOR EACH ROW
EXECUTE FUNCTION apply_inventory_movement();
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: trigger/function remain compatible.
    }
};
