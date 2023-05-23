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
        Schema::create('counteragents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('id_1c')->nullable();
            $table->string('bin')->nullable();
            $table->string('iik')->nullable();
            $table->string('bik')->nullable();
            $table->integer('payment_type');
            $table->foreignId('price_type_id')->constrained('price_types')->cascadeOnDelete();
            $table->float('discount')->default(0);
            $table->boolean('enabled')->default(1);
            $table->time('delivery_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counteragents');
    }
};
