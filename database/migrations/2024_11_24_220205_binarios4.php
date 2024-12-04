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
        Schema::create('binarios4', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nombre', 200)->default('');
            $table->foreignId('galeria_id')->index();
            $table->integer('position')->default(0);
            $table->binary('binario')->nullable()->comment('imagen de fondo');
            $table->binary('binariomin')->nullable()->comment('imagen de fondo');
            $table->mediumText('anotaciones');
            $table->double('originalsize');
            $table->boolean('selected')->default(false);
            $table->timestamps();
        });
        DB::statement("alter table binarios4 change column binario binario longblob null");
        DB::statement("alter table binarios4 change column binariomin binariomin longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
