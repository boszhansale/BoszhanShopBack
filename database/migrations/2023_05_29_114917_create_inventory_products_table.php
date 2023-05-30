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
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('receipt',20,2)->comment('поступления');
            $table->decimal('sale',20,2)->comment('продажа');
            $table->decimal('moving',20,2)->comment('перемещение');
            $table->decimal('remains',20,2)->comment('остатки');
            $table->decimal('count',20,2)->comment('Фактическое количество');
            $table->decimal('overage',20,2)->default(0)->comment('Излишки');
            $table->decimal('overage_price',20,2)->default(0)->comment('Излишки');
            $table->decimal('shortage',20,2)->default(0)->comment('Недостачи');
            $table->decimal('shortage_price',20,2)->default(0)->comment('Недостачи');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_products');
    }
};
