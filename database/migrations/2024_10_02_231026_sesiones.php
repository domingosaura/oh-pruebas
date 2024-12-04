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
        Schema::create('sesiones', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id');
            $table->char('nombre', 100)->default('');
            $table->char('nombreinterno', 100)->default('');
            $table->mediumText('anotaciones')->nullable();
            $table->mediumText('anotaciones2')->nullable();
            $table->boolean('activa')->default(true);
            $table->integer('antelacion')->default(0); // dias de antelacion para pedir cita
            $table->char('pregunta1', 250)->default('');
            $table->char('respuesta1', 250)->default('');
            $table->boolean('obliga1')->default(false);
            $table->char('pregunta2', 250)->default('');
            $table->char('respuesta2', 250)->default('');
            $table->boolean('obliga2')->default(false);
            $table->char('pregunta3', 250)->default('');
            $table->char('respuesta3', 250)->default('');
            $table->boolean('obliga3')->default(false);
            $table->char('pregunta4', 250)->default('');
            $table->char('respuesta4', 250)->default('');
            $table->boolean('obliga4')->default(false);
            $table->char('pregunta5', 250)->default('');
            $table->char('respuesta5', 250)->default('');
            $table->boolean('obliga5')->default(false);
            
            $table->char('pregunta6', 250)->default('');
            $table->char('respuesta6', 250)->default('');
            $table->boolean('obliga6')->default(false);
            $table->char('pregunta7', 250)->default('');
            $table->char('respuesta7', 250)->default('');
            $table->boolean('obliga7')->default(false);
            $table->char('pregunta8', 250)->default('');
            $table->char('respuesta8', 250)->default('');
            $table->boolean('obliga8')->default(false);
            $table->char('pregunta9', 250)->default('');
            $table->char('respuesta9', 250)->default('');
            $table->boolean('obliga9')->default(false);
            $table->char('pregunta10', 250)->default('');
            $table->char('respuesta10', 250)->default('');
            $table->boolean('obliga10')->default(false);

            $table->char('emailconfirmaasunto', 200)->default('');
            $table->longText('emailconfirmacuerpo')->nullable();
            $table->longText('emailconfirmacuerpo0')->nullable();
            $table->char('emailrecuerdaasunto', 200)->default('');
            $table->longText('emailrecuerdacuerpo')->nullable();
            $table->binary('binario')->nullable()->comment('imagen de fondo');
            $table->longText('packs')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('nombre');
            $table->index('nombreinterno');
        });
        DB::statement("alter table sesiones change column binario binario longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
