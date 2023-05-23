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
        Schema::create('webkassa_cash_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('unique_number');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });


        Schema::create('users', function (Blueprint $table) {
            $table->foreignId('webkassa_cash_box_id')
                ->nullable()
                ->constrained('webkassa_cash_boxes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webkassa_cash_boxes');
    }
};
