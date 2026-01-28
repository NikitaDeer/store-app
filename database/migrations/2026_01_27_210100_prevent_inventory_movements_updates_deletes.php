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
CREATE OR REPLACE FUNCTION prevent_inventory_movements_modification()
RETURNS TRIGGER AS $$
BEGIN
    RAISE EXCEPTION 'Inventory movements are append-only';
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_inventory_movements_no_update_delete ON inventory_movements;

CREATE TRIGGER trg_inventory_movements_no_update_delete
BEFORE UPDATE OR DELETE ON inventory_movements
FOR EACH ROW
EXECUTE FUNCTION prevent_inventory_movements_modification();
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(<<<'SQL'
DROP TRIGGER IF EXISTS trg_inventory_movements_no_update_delete ON inventory_movements;
DROP FUNCTION IF EXISTS prevent_inventory_movements_modification();
SQL);
    }
};
