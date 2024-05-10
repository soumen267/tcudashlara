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
        Schema::create('shopify_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_customer_id');
            $table->string('customer_email');
            $table->string('shopify_orderid');
            $table->string('shopify_ordername');
            $table->decimal('subtotal_price', 5, 2)->nullable();
            $table->string('total_discounts');
            $table->decimal('total_price', 5, 2)->nullable();
            $table->longText('raw_data')->nullable();
            // $table->enum('Active', ['Cancelled', 'Deleted'])->nullable()->default(['Active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_orders');
    }
};
