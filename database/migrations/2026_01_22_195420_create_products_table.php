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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->foreignId('manufacturer_id')->constrained()->cascadeOnDelete();

        $table->string('name'); // Название модели
        $table->date('manufacture_date'); // Дата изготовления (для отчета > 1 года)
        $table->integer('warranty_months'); // Гарантия (мес)

        // Цены
        $table->decimal('price_rub', 10, 2); // Цена руб
        $table->decimal('price_usd', 10, 2)->nullable(); // Цена в валюте (по ТЗ)

        // Скидки (по ТЗ)
        $table->integer('discount_percent')->default(0);
        $table->string('discount_reason')->nullable(); // "Брак", "Акция", "Витринный образец"

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
