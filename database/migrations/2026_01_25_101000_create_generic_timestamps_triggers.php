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
CREATE OR REPLACE FUNCTION set_generic_timestamps()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        IF NEW.created_at IS NULL THEN
            NEW.created_at := NOW();
        END IF;
        IF NEW.updated_at IS NULL THEN
            NEW.updated_at := NOW();
        END IF;
    ELSIF TG_OP = 'UPDATE' THEN
        NEW.updated_at := NOW();
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Replace product-specific trigger with generic one.
DROP TRIGGER IF EXISTS trg_products_timestamps ON products;

DROP TRIGGER IF EXISTS trg_users_timestamps ON users;
CREATE TRIGGER trg_users_timestamps
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_categories_timestamps ON categories;
CREATE TRIGGER trg_categories_timestamps
BEFORE INSERT OR UPDATE ON categories
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_countries_timestamps ON countries;
CREATE TRIGGER trg_countries_timestamps
BEFORE INSERT OR UPDATE ON countries
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_manufacturers_timestamps ON manufacturers;
CREATE TRIGGER trg_manufacturers_timestamps
BEFORE INSERT OR UPDATE ON manufacturers
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_products_timestamps ON products;
CREATE TRIGGER trg_products_timestamps
BEFORE INSERT OR UPDATE ON products
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_storage_locations_timestamps ON storage_locations;
CREATE TRIGGER trg_storage_locations_timestamps
BEFORE INSERT OR UPDATE ON storage_locations
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_stocks_timestamps ON stocks;
CREATE TRIGGER trg_stocks_timestamps
BEFORE INSERT OR UPDATE ON stocks
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_specifications_timestamps ON specifications;
CREATE TRIGGER trg_specifications_timestamps
BEFORE INSERT OR UPDATE ON specifications
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_inventory_movements_timestamps ON inventory_movements;
CREATE TRIGGER trg_inventory_movements_timestamps
BEFORE INSERT OR UPDATE ON inventory_movements
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();

DROP TRIGGER IF EXISTS trg_low_stock_alerts_timestamps ON low_stock_alerts;
CREATE TRIGGER trg_low_stock_alerts_timestamps
BEFORE INSERT OR UPDATE ON low_stock_alerts
FOR EACH ROW
EXECUTE FUNCTION set_generic_timestamps();
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(<<<'SQL'
DROP TRIGGER IF EXISTS trg_users_timestamps ON users;
DROP TRIGGER IF EXISTS trg_categories_timestamps ON categories;
DROP TRIGGER IF EXISTS trg_countries_timestamps ON countries;
DROP TRIGGER IF EXISTS trg_manufacturers_timestamps ON manufacturers;
DROP TRIGGER IF EXISTS trg_products_timestamps ON products;
DROP TRIGGER IF EXISTS trg_storage_locations_timestamps ON storage_locations;
DROP TRIGGER IF EXISTS trg_stocks_timestamps ON stocks;
DROP TRIGGER IF EXISTS trg_specifications_timestamps ON specifications;
DROP TRIGGER IF EXISTS trg_inventory_movements_timestamps ON inventory_movements;
DROP TRIGGER IF EXISTS trg_low_stock_alerts_timestamps ON low_stock_alerts;

DROP FUNCTION IF EXISTS set_generic_timestamps();
SQL);
    }
};
