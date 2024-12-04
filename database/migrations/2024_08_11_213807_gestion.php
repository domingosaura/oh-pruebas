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
        Schema::create('gestion', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('basegestion_id');
            //$table->bigInteger('galeria_id')->nullable();
            $table->char('descripcion',70)->default('')->comment('Descripción');
            $table->double('importe')->default(0);
            $table->bigInteger('impuesto_id');
            $table->timestamps();
            $table->index('basegestion_id');
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
