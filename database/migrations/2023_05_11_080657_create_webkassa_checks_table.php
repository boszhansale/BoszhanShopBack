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
        Schema::create('webkassa_checks', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('operation_type');
            $table->string('number')->unique();
            $table->string('check_number')->unique();
            //2
            $table->foreignId('order_id')->nullable()->constrained('orders');
            //3
            $table->foreignId('refund_id')->nullable()->comment('возврат')->constrained('refunds');
            //0
            $table->foreignId('receipt_id')->nullable()->comment('Поступление товара')->constrained('receipts');
            //1
            $table->foreignId('refund_producer_id')->nullable()->comment('Возврат товара поставщику')->constrained('refund_producers');


            $table->foreignId('webkassa_cash_box_id')->constrained('webkassa_cash_boxes');

            $table->string('ticket_url')->nullable();
            $table->string('ticket_print_url')->nullable();

            $table->json('params')->nullable()->comment('request data');
            $table->json('data')->nullable()->comment('response data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webkassa_checks');
    }
};
