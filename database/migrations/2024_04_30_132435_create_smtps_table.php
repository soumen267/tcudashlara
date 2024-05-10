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
        Schema::create('smtps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('host');
            $table->string('domain');
            $table->string('port');
            $table->string('email');
            $table->string('mailfrom');
            $table->string('username');
            $table->string('password');
            $table->string('emailtemplatepath');
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
        Schema::dropIfExists('smtps');
    }
};
