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
        Schema::create('basecalendario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nombre', length: 100)->index();
            $table->string('descripcion', length: 200);
            $table->boolean('activo')->default(true);
            $table->boolean('permitereserva')->default(true);
            $table->binary('binario')->nullable()->comment('Datos binario');
            $table->mediumText('servicios')->nullable(); // la lista de servicios disponibles para este calendario
            //$table->integer('tipodepago')->nullable()->default(1); // tipodepago 1 efectivo 2 transferencia 3 redsys 4 paypal 5 stripe 6 bizum manual
            $table->boolean('efectivo')->default(false); // 1
            $table->boolean('transferencia')->default(false); // 2
            $table->boolean('redsys')->default(false); // 3
            $table->boolean('paypal')->default(false); // 4
            $table->boolean('stripe')->default(false); // 5
            $table->boolean('bizum')->default(false); // 6
            $table->boolean('mostrarreservadas')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
        DB::statement("alter table basecalendario change column binario binario longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
