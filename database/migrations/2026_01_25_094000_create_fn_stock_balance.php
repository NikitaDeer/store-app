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
CREATE OR REPLACE FUNCTION fn_stock_balance(p_product_id BIGINT, p_storage_location_id BIGINT)
RETURNS INTEGER AS $$
DECLARE
    result_qty INTEGER;
BEGIN
    SELECT quantity INTO result_qty
    FROM stocks
    WHERE product_id = p_product_id
      AND storage_location_id = p_storage_location_id
    LIMIT 1;

    RETURN COALESCE(result_qty, 0);
END;
$$ LANGUAGE plpgsql;
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_stock_balance(BIGINT, BIGINT);');
    }
};
