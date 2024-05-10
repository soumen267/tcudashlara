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
        Schema::create('shopify_notreg_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->string('email');
            $table->string('error_msg');
            $table->string('mail_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_notreg_data');
    }
};
