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
        Schema::create('movings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('operation')->default(1);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->foreignId('storage_id')->nullable()->constrained('storages')->cascadeOnDelete();


            $table->decimal('total_price',20,2)->nullable();
            $table->json('product_history')->nullable();


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
        Schema::dropIfExists('movings');
    }
};
