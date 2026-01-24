<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // in | out | transfer | adjustment
            $table->integer('quantity');
            $table->foreignId('source_storage_location_id')->nullable()->constrained('storage_locations')->nullOnDelete();
            $table->foreignId('destination_storage_location_id')->nullable()->constrained('storage_locations')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note')->nullable();
            $table->timestamp('moved_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'type']);
            $table->index(['source_storage_location_id', 'destination_storage_location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
