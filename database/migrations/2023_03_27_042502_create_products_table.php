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
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('id_1c')->nullable();
            $table->string('article')->nullable();
            $table->tinyInteger('measure');
            $table->string('barcode')->nullable();
            $table->double('remainder')->nullable();
            $table->boolean('enabled')->default(1);
            $table->boolean('purchase')->default(1);
            $table->boolean('return')->default(1);
            $table->string('presale_id')->nullable();
            $table->float('discount')->default(0);
            $table->boolean('hit')->default(0);
            $table->boolean('new')->default(0);
            $table->boolean('action')->default(0);
            $table->boolean('discount_5')->default(0);
            $table->boolean('discount_10')->default(0);
            $table->boolean('discount_15')->default(0);
            $table->boolean('discount_20')->default(0);

            $table->bigInteger('rating')->nullable();
            $table->softDeletes();
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
