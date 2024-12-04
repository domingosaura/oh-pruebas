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
        Schema::create('packs', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id');
            $table->char('nombre', 100)->default('');
            $table->char('nombreinterno', 100)->default('');
            $table->mediumText('anotaciones')->nullable();
            $table->mediumText('anotaciones2')->nullable();
            $table->boolean('activa')->default(true);
            $table->boolean('sinfecha')->default(false);
            $table->double('preciopack')->default(0);
            $table->double('precioreserva')->default(0);
            $table->integer('minutos')->default(0);
            $table->binary('binario')->nullable()->comment('imagen de fondo');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->index('user_id');
            $table->index('nombre');
            $table->index('nombreinterno');
        });
        DB::statement("alter table packs change column binario binario longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
