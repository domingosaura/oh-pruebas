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
        Schema::create('users2', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->char('nombre',100)->default('');
            $table->char('nombre2',100)->default('');
            $table->string('nif', length: 15)->default('');
            $table->string('telefono', length: 12)->default('');
            $table->char('domicilio',100)->default('');
            $table->char('codigopostal',10)->default('');
            $table->char('poblacion',50)->default('');
            $table->char('provincia',50)->default('');
            $table->char('iban',30)->default('');
            $table->binary('logo')->nullable();
            $table->binary('marcaagua')->nullable();
            $table->binary('firma')->nullable();

            $table->char('mail_direccion',200)->default('');
            $table->char('mail_username',200)->default('');
            $table->char('mail_password',200)->default('');
            $table->char('mail_smtp',200)->default('');

            //MAIL_USERNAME=info@ohmyphoto.es
            //MAIL_FROM_ADDRESS=info@ohmyphoto.es
            //MAIL_PASSWORD=
            //MAIL_HOST=smtp.ohmyphoto.es
            //MAIL_PORT=587
            //MAIL_ENCRYPTION=ssl

            $table->timestamps();
        });
        DB::statement("alter table users2 change column logo logo longblob null");
        DB::statement("alter table users2 change column marcaagua marcaagua longblob null");
        DB::statement("alter table users2 change column firma firma longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
