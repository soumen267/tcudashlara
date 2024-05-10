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
        Schema::create('shopifies', function (Blueprint $table) {
            $table->id();
            $table->string('storeurl');
            $table->string('shopifyapikey');
            $table->string('shopifyapipassword');
            $table->string('shopifyshopname');
            $table->string('shopifydomainname');
            $table->enum('status',['1','0'])->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopifies');
    }
};
