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
        Schema::create('moving_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moving_id')
                ->constrained('movings')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->decimal('count',11,2);
            $table->decimal('old_price',20,2)->nullable();
            $table->decimal('price',20,2);
            $table->decimal('all_price',20,2);
            $table->string('storage_life')->nullable();
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moving_products');
    }
};
