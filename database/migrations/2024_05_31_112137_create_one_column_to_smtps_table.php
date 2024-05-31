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
        Schema::table('smtps', function (Blueprint $table) {
            $table->string('type')->nullable()->after('password');
            $table->string('api')->nullable()->after('host');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smtps', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('api');
        });
    }
};
