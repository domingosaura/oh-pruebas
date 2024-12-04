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
        Schema::create('accesoilegal', function (Blueprint $table) {
            $table->increments('id');
            //$table->foreignId('user_id')->index();
            $table->char('ip', 100)->default('');
            $table->char('ruta', 200)->default('')->index();
            $table->char('navegador', 200)->default('');
            $table->boolean('is_mobile')->default(false);
            $table->boolean('is_login')->default(false);
            $table->timestamp('created_at')->useCurrent();
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
