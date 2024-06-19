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
        Schema::create('crm_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('orderId');
            $table->string('customerId');
            $table->string('emailAddress');
            $table->string('phoneNumber');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('pid');
            $table->tinyInteger('dashboard');
            $table->text('api_response')->nullable();
            $table->enum('status',['1','0'])->default('1');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_orders');
    }
};
