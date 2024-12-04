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
        Schema::create('basegestion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('cliente_id')->nullable();
            $table->integer('tipo')->default(1); // 1 ingreso 2 gasto
            $table->char('documento',50)->default('')->comment('Título');
            $table->double('importe')->default(0);
            $table->date('fecha');
            $table->timestamps();
            $table->index('fecha');
            $table->index('tipo');
            $table->index('user_id');
            $table->index('documento');
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
