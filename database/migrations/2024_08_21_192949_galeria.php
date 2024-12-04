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
        Schema::create('galerias', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id');
            $table->foreignId('cliente_id')->nullable();
            $table->char('nombre', 100)->default('');
            $table->char('nombreinterno', 100)->default('');
            $table->mediumText('anotaciones')->nullable();
            $table->mediumText('anotaciones2')->nullable();
            $table->binary('binario')->nullable()->comment('imagen de fondo');
            $table->binary('binariomin')->nullable()->comment('imagen de fondo');
            $table->integer('numfotos')->default(0);
            $table->double('preciogaleria')->default(0);
            $table->double('preciogaleriacompleta')->default(0);
            $table->double('entregado')->default(0); // si el cliente entregó dinero
            $table->integer('maxfotos')->default(0);
            $table->double('preciofoto')->default(0);
            $table->date('caducidad')->nullable(); // fecha caducidad desde que se comparte - rellenable en plantilla
            $table->integer('diascaducidad')->nullable()->default(7); // dias de caducidad desde que se comparte - rellenable en galeria
            $table->integer('permitirdescarga')->default(2); // 1 permitir despues del pago 2 no permitir 3 permitir siempre
            $table->boolean('nohaydescarga')->default(false);
            $table->integer('diascaducidaddescarga')->nullable()->default(7); // NO USADO dias de caducidad desde que se comparte - rellenable en galeria
            $table->boolean('descargada')->default(false);
            $table->dateTime('fechadescarga')->nullable();
            $table->foreignId('contrato_id')->nullable();
            $table->boolean('contratofirmado')->default(false);
            $table->dateTime('fechafirma')->nullable(); // asignado a cuando confirman la seleccion
            $table->boolean('pagado')->default(false);
            $table->boolean('pagadomanual')->default(false);
            $table->boolean('seleccionconfirmada')->default(false);
            $table->dateTime('fechapago')->nullable();
            $table->integer('tipodepago')->nullable()->default(1); // tipodepago 1 efectivo 2 transferencia 3 redsys 4 paypal 5 stripe 6 bizum manual
            $table->char('idpago', 100)->default('');
            $table->double('imppago')->default(0); // importe pago sin contar la entrega a cuenta
            $table->boolean('pago1activo')->default(false);
            $table->boolean('pago2activo')->default(false);
            $table->boolean('pago3activo')->default(false);
            $table->boolean('pago4activo')->default(false);
            $table->boolean('pago5activo')->default(false);
            $table->boolean('pago6activo')->default(false);
            $table->boolean('clicambiapago')->default(false); // no usado al final
            $table->boolean('marcaagua')->default(false);
            $table->boolean('nombresfotos')->default(false);
            $table->char('emailpagoasunto', 200)->default('');
            $table->longText('emailpagocuerpo')->nullable();
            $table->char('emailconfirmaasunto', 200)->default('');
            $table->longText('emailconfirmacuerpo')->nullable();
            $table->char('emailenvioasunto', 200)->default('');
            $table->longText('emailenviocuerpo')->nullable();
            $table->boolean('archivada')->default(false);
            $table->boolean('eliminada')->default(false);
            $table->dateTime('fechaeliminada')->nullable();
            $table->boolean('enviado')->default(false);
            $table->dateTime('dtenvio')->nullable();
            $table->dateTime('dtenvio2')->nullable();
            $table->dateTime('dtenvio3')->nullable();
            $table->dateTime('dtenvio4')->nullable();
            $table->dateTime('dtenvio5')->nullable();
            $table->dateTime('dtenvio6')->nullable();
            $table->dateTime('dtenvio7')->nullable();
            $table->dateTime('dtenvio8')->nullable();
            $table->dateTime('dtenvio9')->nullable();
            $table->dateTime('dtenvio10')->nullable();
            $table->integer('permitircomentarios')->nullable()->default(1);
            $table->char('incluido1', 100)->default('');
            $table->char('incluido2', 100)->default('');
            $table->char('incluido3', 100)->default('');
            $table->char('incluido4', 100)->default('');
            $table->char('incluido5', 100)->default('');
            $table->char('incluido6', 100)->default('');
            $table->char('incluido7', 100)->default('');
            $table->char('incluido8', 100)->default('');
            $table->char('incluido9', 100)->default('');
            $table->char('incluido10', 100)->default('');
            $table->char('opcional1', 100)->default('');
            $table->char('opcional2', 100)->default('');
            $table->char('opcional3', 100)->default('');
            $table->char('opcional4', 100)->default('');
            $table->char('opcional5', 100)->default('');
            $table->char('opcional6', 100)->default('');
            $table->char('opcional7', 100)->default('');
            $table->char('opcional8', 100)->default('');
            $table->char('opcional9', 100)->default('');
            $table->char('opcional10', 100)->default('');
            $table->double('precioopc1')->default(0);
            $table->double('precioopc2')->default(0);
            $table->double('precioopc3')->default(0);
            $table->double('precioopc4')->default(0);
            $table->double('precioopc5')->default(0);
            $table->double('precioopc6')->default(0);
            $table->double('precioopc7')->default(0);
            $table->double('precioopc8')->default(0);
            $table->double('precioopc9')->default(0);
            $table->double('precioopc10')->default(0);
            $table->boolean('selopc1')->default(false);
            $table->boolean('selopc2')->default(false);
            $table->boolean('selopc3')->default(false);
            $table->boolean('selopc4')->default(false);
            $table->boolean('selopc5')->default(false);
            $table->boolean('selopc6')->default(false);
            $table->boolean('selopc7')->default(false);
            $table->boolean('selopc8')->default(false);
            $table->boolean('selopc9')->default(false);
            $table->boolean('selopc10')->default(false);
            $table->char('clavecliente', 20)->default('');
            $table->integer('host')->default(1); // en que hetznerbox se almacena

            $table->integer('pack1')->nullable()->default(0); // pack de x fotos adicionales
            $table->double('pack1precio')->nullable()->default(0); // pack de x fotos adicionales
            $table->integer('pack2')->nullable()->default(0); // pack de x fotos adicionales
            $table->double('pack2precio')->nullable()->default(0); // pack de x fotos adicionales
            $table->integer('pack3')->nullable()->default(0); // pack de x fotos adicionales
            $table->double('pack3precio')->nullable()->default(0); // pack de x fotos adicionales

            $table->timestamps();
            $table->index('user_id');
            $table->index('cliente_id');
            $table->index('nombre');
        });
        DB::statement("alter table galerias change column binario binario longblob null");
        DB::statement("alter table galerias change column binariomin binariomin longblob null");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
