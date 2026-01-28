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
CREATE OR REPLACE VIEW v_stock_summary AS
SELECT
    s.id AS stock_id,
    p.id AS product_id,
    p.name AS product_name,
    c.name AS category_name,
    m.name AS manufacturer_name,
    sl.id AS storage_location_id,
    sl.name AS storage_location_name,
    sl.type AS storage_location_type,
    s.quantity,
    p.min_stock,
    (s.quantity < p.min_stock) AS is_low_stock,
    p.price_rub,
    (p.price_rub * s.quantity) AS total_value_rub,
    s.updated_at
FROM stocks s
JOIN products p ON p.id = s.product_id
JOIN categories c ON c.id = p.category_id
JOIN manufacturers m ON m.id = p.manufacturer_id
JOIN storage_locations sl ON sl.id = s.storage_location_id;
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS v_stock_summary;');
    }
};
