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
        Schema::create('shopify_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_customer_id')->nullable();
            $table->string('name');
            $table->string('email_address');
            $table->string('password');
            $table->string('phone');
            $table->string('balance');
            $table->string('coupon_code');
            $table->string('discount_code_id');
            $table->string('price_rule_id');
            // $table->enum('status', ['Canceled','Refunded','Deleted'])->nullable()->default('Active');
            $table->enum('mail_status', ['Sent','Not Sent','Failed'])->nullable()->default('Not Sent');
            $table->string('mail_response');
            $table->longText('webhook_response');
            $table->longText('crm_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_customers');
    }
};
