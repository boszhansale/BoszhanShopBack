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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('counteragent_id')->nullable()->constrained('counteragents')->cascadeOnDelete();
            $table->string('phone');
            $table->string('address');
            $table->decimal('lat',11,8)->nullable();
            $table->decimal('lng',11,8)->nullable();
            $table->float('discount')->default(0);
            $table->boolean('discount_position')->default(0);
            $table->boolean('enabled')->default(1);
            $table->timestamp('removed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
