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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nombre', length: 100)->default('')->index();
            $table->string('apellidos', length: 100)->default('')->index();
            $table->string('nif', length: 15)->default('')->nullable()->index();
            $table->string('domicilio', length: 200)->default('');
            $table->string('cpostal', length: 8)->default('')->index();
            $table->string('poblacion', length: 100)->default('')->index();
            $table->string('provincia', length: 100)->default('')->index();
            $table->string('telefono', length: 25)->default('')->index();
            $table->string('email', length: 200)->default('');
            $table->boolean('activo')->default(true);
            $table->boolean('permiteimagenes')->default(false);
            $table->boolean('permitecomunicaciones')->default(false);

            $table->string('nombrepareja', length: 100)->default('')->nullable();
            $table->string('apellidospareja', length: 100)->default('')->nullable();
            $table->string('nifpareja', length: 15)->default('')->nullable();
            $table->string('hijo1', length: 200)->default('')->nullable();
            $table->date('edad1')->nullable();
            $table->string('hijo2', length: 200)->default('')->nullable();
            $table->date('edad2')->nullable();
            $table->string('hijo3', length: 200)->default('')->nullable();
            $table->date('edad3')->nullable();
            $table->string('hijo4', length: 200)->default('')->nullable();
            $table->date('edad4')->nullable();
            $table->string('hijo5', length: 200)->default('')->nullable();
            $table->date('edad5')->nullable();
            $table->string('hijo6', length: 200)->default('')->nullable();
            $table->date('edad6')->nullable();
            $table->longText('notasinternas')->nullable();
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamps();
            $table->unique(['user_id', 'email']);
            //$table->index('nombre');
            //$table->index('apellidos');
            //$table->index('nif');
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
