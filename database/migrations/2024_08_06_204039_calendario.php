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
        Schema::create('calendario', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('basecalendario_id');
            $table->bigInteger('cliente_id')->nullable();
            $table->boolean('nodisponible')->default(0);
            $table->boolean('reservado')->default(0);
            $table->boolean('confirmado')->default(0); // para los pagos manuales, lo confirma el fotografo
            $table->boolean('pagado')->default(0); // para los pagos manuales, lo confirma el fotografo
            $table->double('importepagado')->default(0);
            $table->integer('tipodepago')->nullable()->default(0); // tipodepago 1 efectivo 2 transferencia 3 redsys 4 paypal 5 stripe 6 bizum manual
            $table->integer('minutos')->default(0);
            $table->timestamp('prereserved_at')->nullable();
            $table->timestamp('reserved_at')->nullable();
            $table->char('title', 150)->default('')->comment('Título'); // parece que no se va a usar
            $table->mediumText('cuerpo')->nullable()->comment('Cuerpo'); // parece que no se va a usar
            $table->double('sinfecha')->default(0);
            $table->timestamp('start')->useCurrent()->comment('Inicio');
            $table->mediumText('servicios')->nullable(); // la lista de servicios disponibles para este calendario
            $table->mediumText('servicios2')->nullable(); // la lista de servicios disponibles para este calendario antes de haber seleccionado la cita
            $table->char('ipcontrato', 250)->default('');
            $table->char('localizador', 12)->default('');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->char('pregunta1', 250)->default('');
            $table->char('respuesta1', 250)->default('');
            $table->char('pregunta2', 250)->default('');
            $table->char('respuesta2', 250)->default('');
            $table->char('pregunta3', 250)->default('');
            $table->char('respuesta3', 250)->default('');
            $table->char('pregunta4', 250)->default('');
            $table->char('respuesta4', 250)->default('');
            $table->char('pregunta5', 250)->default('');
            $table->char('respuesta5', 250)->default('');

            $table->char('pregunta6', 250)->default('');
            $table->char('respuesta6', 250)->default('');
            $table->char('pregunta7', 250)->default('');
            $table->char('respuesta7', 250)->default('');
            $table->char('pregunta8', 250)->default('');
            $table->char('respuesta8', 250)->default('');
            $table->char('pregunta9', 250)->default('');
            $table->char('respuesta9', 250)->default('');
            $table->char('pregunta10', 250)->default('');
            $table->char('respuesta10', 250)->default('');

            $table->index('cliente_id');
            $table->index('start');
            $table->index('title');
            $table->index('basecalendario_id');
            $table->index('localizador');
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
