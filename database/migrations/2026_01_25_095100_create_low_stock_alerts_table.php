<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('low_stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('storage_location_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('threshold');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'storage_location_id']);
        });

        DB::unprepared(<<<'SQL'
CREATE UNIQUE INDEX low_stock_alerts_open_unique
ON low_stock_alerts (product_id, storage_location_id)
WHERE resolved_at IS NULL;
SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP INDEX IF EXISTS low_stock_alerts_open_unique;');
        Schema::dropIfExists('low_stock_alerts');
    }
};
