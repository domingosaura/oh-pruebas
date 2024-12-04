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

        Schema::create('productosgaleria', function (Blueprint $table) {
            $table->comment('esta es la tabla de datos de las galerias');
            $table->increments('id');
            $table->foreignId('user_id');
            $table->foreignId('galeria_id')->index()->nullable();
            $table->foreignId('base_id')->index()->nullable();
            $table->char('nombre', 100)->default('');
            $table->mediumText('anotaciones')->nullable();
            $table->binary('binario')->nullable()->comment('imagen de fondo');
            $table->integer('numfotos')->default(0);
            $table->integer('numfotosadicionales')->default(0);
            $table->integer('fotosdesde')->default(0); // 0 no es una seleccion 1 cualquiera 2 seleccionadas 3 no seleccionadas
            $table->double('precioproducto')->default(0);
            $table->double('preciofotoadicional')->default(0);
            $table->mediumText('seleccionfotos')->default('')->nullable(); // la lista de fotos seleccionadas separadas por coma
            $table->boolean('seleccionada')->default(false);
            $table->boolean('incluido')->default(false); // el producto está incluido en la sesion no tiene coste adicional
            $table->char('txtopc1', 100)->default('');
            $table->char('txtopc2', 100)->default('');
            $table->char('txtopc3', 100)->default('');
            $table->char('txtopc4', 100)->default('');
            $table->char('txtopc5', 100)->default('');
            $table->double('precio1')->default(0);
            $table->double('precio2')->default(0);
            $table->double('precio3')->default(0);
            $table->double('precio4')->default(0);
            $table->double('precio5')->default(0);
            $table->boolean('selopc1')->default(false);
            $table->boolean('selopc2')->default(false);
            $table->boolean('selopc3')->default(false);
            $table->boolean('selopc4')->default(false);
            $table->boolean('selopc5')->default(false);
            $table->char('pregunta1', 100)->default('');
            $table->char('pregunta2', 100)->default('');
            $table->char('pregunta3', 100)->default('');
            $table->char('pregunta4', 100)->default('');
            $table->char('pregunta5', 100)->default('');
            $table->char('respuesta1', 250)->default('');
            $table->char('respuesta2', 250)->default('');
            $table->char('respuesta3', 250)->default('');
            $table->char('respuesta4', 250)->default('');
            $table->char('respuesta5', 250)->default('');
            $table->boolean('pre1obligatorio')->default(false);
            $table->boolean('pre2obligatorio')->default(false);
            $table->boolean('pre3obligatorio')->default(false);
            $table->boolean('pre4obligatorio')->default(false);
            $table->boolean('pre5obligatorio')->default(false);
            $table->boolean('permitecantidad')->default(false); // el producto está incluido en la sesion no tiene coste adicional
            $table->integer('cantidad')->default(1); // si se permite seleccionar varias unidades de este producto solo en adicionales
            $table->integer('position')->default(0);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->index('user_id');
        });
        DB::statement("alter table productosgaleria change column binario binario longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
