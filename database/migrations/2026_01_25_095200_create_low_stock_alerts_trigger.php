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
CREATE OR REPLACE FUNCTION apply_low_stock_alert()
RETURNS TRIGGER AS $$
DECLARE
    threshold_value INTEGER;
    existing_alert_id BIGINT;
BEGIN
    SELECT min_stock INTO threshold_value
    FROM products
    WHERE id = NEW.product_id
    LIMIT 1;

    IF threshold_value IS NULL THEN
        RETURN NEW;
    END IF;

    SELECT id INTO existing_alert_id
    FROM low_stock_alerts
    WHERE product_id = NEW.product_id
      AND storage_location_id = NEW.storage_location_id
      AND resolved_at IS NULL
    LIMIT 1;

    IF NEW.quantity < threshold_value THEN
        IF existing_alert_id IS NULL THEN
            INSERT INTO low_stock_alerts (
                product_id,
                storage_location_id,
                quantity,
                threshold,
                created_at,
                updated_at
            ) VALUES (
                NEW.product_id,
                NEW.storage_location_id,
                NEW.quantity,
                threshold_value,
                NOW(),
                NOW()
            );
        ELSE
            UPDATE low_stock_alerts
            SET quantity = NEW.quantity,
                threshold = threshold_value,
                updated_at = NOW()
            WHERE id = existing_alert_id;
        END IF;
    ELSE
        IF existing_alert_id IS NOT NULL THEN
            UPDATE low_stock_alerts
            SET resolved_at = NOW(),
                updated_at = NOW()
            WHERE id = existing_alert_id;
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_low_stock_alert_after_change ON stocks;

CREATE TRIGGER trg_low_stock_alert_after_change
AFTER INSERT OR UPDATE ON stocks
FOR EACH ROW
EXECUTE FUNCTION apply_low_stock_alert();
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(<<<'SQL'
DROP TRIGGER IF EXISTS trg_low_stock_alert_after_change ON stocks;
DROP FUNCTION IF EXISTS apply_low_stock_alert();
SQL);
    }
};
