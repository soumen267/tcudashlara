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
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('dashname');
            $table->unsignedBigInteger('crm_id');
            $table->unsignedBigInteger('smtp_id');
            $table->unsignedBigInteger('shopify_id');
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('cascade');
            $table->foreign('smtp_id')->references('id')->on('smtps')->onDelete('cascade');
            $table->foreign('shopify_id')->references('id')->on('shopifies')->onDelete('cascade');
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
        Schema::dropIfExists('dashboards');
    }
};
