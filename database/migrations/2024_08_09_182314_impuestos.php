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
        Schema::create('impuestos', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id');
            $table->char('nombre', 50)->default('')->comment('Impuesto');
            $table->double('porcentaje')->default(0)->comment('Porcentaje Impuesto');
            $table->timestamps();
            $table->index('nombre');
            $table->index('user_id');
        });
        //$ins="insert ignore into impuestos (id,descripcion,nomodificable) values (1,'IVA',1)";
        //DB::statement($ins);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
