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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('operation')->default(1);
            $table->string('bank')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('counteragent_id')->nullable()->constrained('counteragents')->cascadeOnDelete();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->foreignId('storage_id')->nullable()->constrained('storages')->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->cascadeOnDelete();
            $table->decimal('total_price',20,2);
            $table->bigInteger('inventory_id')->unsigned()->nullable();
            $table->bigInteger('moving_id')->unsigned()->nullable();
            $table->bigInteger('refund_id')->unsigned()->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('nds')->default(2);
            $table->tinyInteger('source')->default(1);


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
        Schema::dropIfExists('receipts');
    }
};
