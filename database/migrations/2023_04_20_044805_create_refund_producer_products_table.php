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
        Schema::create('refund_producer_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_producer_id')
                ->constrained('refund_producers')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->decimal('count',11,2);
            $table->decimal('price',20,2);
            $table->decimal('all_price',20,2);
            $table->text('comment')->nullable();

            $table->foreignId('reason_refund_id')->nullable()->constrained('reason_refunds')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_producer_products');
    }
};
