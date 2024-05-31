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
        Schema::table('shopifies', function (Blueprint $table) {
            $table->string('shopifywebhookhash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shopifies', function (Blueprint $table) {
            $table->dropColumn('shopifywebhookhash');
        });
    }
};
