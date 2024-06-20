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
        Schema::table('shopify_customers', function (Blueprint $table) {
            $table->enum('status', ['Canceled','Refunded','Deleted','Active'])->nullable()->default('Active')->after('mail_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shopify_customers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
