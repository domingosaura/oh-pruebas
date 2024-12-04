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
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('galeria_id')->nullable();
            $table->integer('numerico')->nullable()->default(0); // con numerico+galeria_id: 10 confirmada selección, <10 pagado
            $table->string('notas', length: 200)->default('');
            $table->boolean('pendiente')->default(true)->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
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
