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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('login')->unique();
            $table->string('password');
            $table->string('id_1c')->nullable();
            $table->string('device_token')->nullable();
            $table->smallInteger('status')->default(1);
            $table->decimal('lat', 11, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();

            $table->foreignId('store_id')->nullable()
                ->constrained('stores')
                ->cascadeOnDelete();
            $table->foreignId('storage_id')->nullable()->constrained('storages')->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->cascadeOnDelete();
            $table->string('bank')->nullable();

            $table->timestamp('webkassa_token')->nullable();
            $table->string('webkassa_login')->nullable();
            $table->timestamp('webkassa_login_at')->nullable();
            $table->string('webkassa_password')->nullable();
            $table->decimal('balance',20,2)->nullable();


            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
