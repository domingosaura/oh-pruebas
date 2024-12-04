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
        Schema::create('contratos', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id');
            $table->foreignId('cliente_id')->nullable();
            $table->foreignId('galeria_id')->nullable();
            $table->char('nombre', 250)->default('');
            $table->longText('texto')->nullable();
            $table->boolean('firmado')->default(false);
            $table->boolean('enviado')->default(false);
            $table->char('ipfirma', 150)->default('')->nullable();
            $table->dateTime('dtfirma')->nullable();
            $table->dateTime('dtenvio')->nullable();
            $table->binary('firma')->nullable();
            $table->timestamps();
            $table->index('cliente_id');
            $table->index('user_id');
            $table->index('firmado');
        });
        DB::statement("alter table contratos change column firma firma longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
