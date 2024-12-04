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
        Schema::create('iniciosdesesion', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id')->index();
            $table->char('ip', 100)->default('');
            $table->char('navegador', 200)->default('')->index();
            $table->boolean('is_mobile')->default(false);
            $table->dateTime('session_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
