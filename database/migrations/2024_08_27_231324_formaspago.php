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
        Schema::create('formaspago', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('efectivo')->default(true); // 1
            $table->boolean('transferencia')->default(true); // 2
            $table->boolean('redsys')->default(false); // 3
            $table->boolean('rsreal')->default(false);
            $table->char('rscodcomercio', 100)->default('');
            $table->char('rsclacomercio', 100)->default('');
            $table->integer('rsterminal')->default(1);
            $table->boolean('paypal')->default(false); // 4
            $table->char('ppalemail', 200)->default('');
            $table->char('ppalclientid', 200)->default('');
            $table->char('ppalsecret', 200)->default('');
            $table->double('ppalprc')->default(0);
            $table->boolean('stripe')->default(false); // 5
            $table->char('stripe_publica', 200)->default('');
            $table->char('stripe_secreta', 200)->default('');
            $table->double('stripeprc')->default(0);
            $table->boolean('bizum')->default(false); // 6 bizum manual
            $table->char('bizumtelefono', 20)->default('');
            $table->timestamps();
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
