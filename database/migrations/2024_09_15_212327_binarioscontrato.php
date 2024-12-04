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
        Schema::create('binarioscontrato', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('contrato_id')->index();
            $table->binary('binario')->nullable()->comment('imagen de fondo');
            $table->timestamps();
        });
        DB::statement("alter table binarioscontrato change column binario binario longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
