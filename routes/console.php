<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Http\Utils;
use Carbon\Carbon;
use App\Models\User2;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Galeria;
use App\Models\Productosgaleria;
use App\Models\Binarios;
use App\Models\Binarios2;
use App\Models\Binarios3;
use App\Models\Binarios4;
use App\Models\Binarios5;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;

//Schedule::call(function () {
    //DB::table('citas temporal')->insert(['nombre'=>'x','created_at'=>Carbon::now()]);
//})->everyMinute();

// recordatorio de que la galería va a caducar al día siguiente
Schedule::call(function () {
    // recordatorio de caducidad
    $xaxa=DB::select("select id,cliente_id,user_id,nombre,clavecliente 
        from galerias where date_add(curdate(),interval 1 day)=caducidad 
        and pagado=0 
        and pagadomanual=0 
        and seleccionconfirmada=0 
        and enviado=1 
        and archivada=0 
        and eliminada=0 
        and cliente_id>0
        and user_id>=0 
        order by user_id limit 999999
        ");
    Log::info("recordatorios de caducidad");
    //Log::info($xaxa);
    //$x=DB::select("select id,cliente_id,user_id,nombre,clavecliente 
    //$x=DB::select("select id,cliente_id,user_id,nombre,clavecliente 
    //    from galerias where id=1 and user_id=6");
    //$x=DB::select("SELECT id,cliente_id,user_id,nombre,clavecliente FROM galerias where cliente_id>0 and user_id=6");
    //Log::info($x);
    foreach($xaxa as $y){
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        Artisan::call('config:clear');
        //Artisan::call('cache:clear');
        //Artisan::call('config:cache'); // si ponemos este despues no manda los mails normales o eso parece
        //Artisan::call('optimize:clear'); // si ponemos este despues no manda los mails normales o eso parece
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        $ruta=route('galeriacliente',[$y->id,md5($y->id."ckeck")]);
        $cliente = Cliente::find($y->cliente_id);
        $xuser = User::find($y->user_id);
        $xuser2 = User2::find($y->user_id);
        $asunto="Su sesión ".$y->nombre." caducará mañana";
        $vista="email.galeriacaducada";
        $logo=$xuser2->logo;
        $empresa=$xuser2->nombre;
        $clientenombre=$cliente->nombre." ".$cliente->apellidos;
        $clienteemail=$cliente->email;
        
        $direcciones = [];
        $direcciones=['address'=>$clienteemail,'name'=>$clientenombre];
        //$asunto="Su sesión ".$y->nombre." caducará mañana userid ".$cliente->user_id." ".$xuser->name;
        //$direcciones=['address'=>'josej69@gmail.com','name'=>'jose javier'];
        
        $ok = true;
        $datos=[
            'ruta'=>$ruta,
            'clave'=>$y->clavecliente,
            'logo'=>$logo,
            'nombregaleria'=>$y->nombre,
            'empresa'=>$empresa,
        ];
        
        //$reply=Utils::cargarconfiguracionemailempresa($y->user_id);
        Config::set('mail.driver',"smtp"); // si es gmail solo funciona con driver mail
        $useridxa=$y->user_id;
        $x=DB::table('users2')
            ->where('id',$useridxa)
            ->where('mail_direccion',"<>","")
            ->where('mail_username',"<>","")
            ->where('mail_smtp',"<>","")
            ->get()->toArray();
        if(count($x)==0){
            // no hay cuenta de correo del cliente
            $mailport=env('MAIL_PORT');
            $mailencryption=env('MAIL_ENCRYPTION');
            $mailhost=env('MAIL_HOST');
            $mailusername=env('MAIL_USERNAME');
            $mailpassword=env('MAIL_PASSWORD');
            $xy=DB::table('users')->where('id',$useridxa)->get()->toArray();
            $mailfrom=array(
                    'address'=>env('MAIL_FROM_ADDRESS'),
                    'name'=>$xy[0]->name);
            $reply=$xy[0]->email;
        }else{
            $mailport=587;
            $mailencryption="tls";
            $mailhost=$x[0]->mail_smtp;
            $mailusername=$x[0]->mail_username;
            $mailpassword=Utils::codificarPassword($x[0]->mail_password,"d");
            $mailfrom=array(
                    'address'=>$x[0]->mail_direccion,
                    'name'=>$x[0]->nombre);
            $reply=$x[0]->mail_direccion;
        }

        //$transport = new EsmtpTransport($mailhost, $mailport, $mailencryption);
        $transport = new EsmtpTransport($mailhost, $mailport, false);
        $transport->setUsername($mailusername);
        $transport->setPassword($mailpassword);
        $mailer = new Mailer($transport);

        $body = view($vista,['datos' => $datos])->render();
        $bodyfull=Utils::emailtocid($body);
    
        $email = (new Email())
            ->from(new Address($mailfrom['address'],$mailfrom['name']))
            ->to(new Address($direcciones['address'],$direcciones['name']))
            //->from($mailfrom)
            //->to($direcciones)
            ->replyTo($reply)
            ->subject($asunto)
            ->html($bodyfull['body']);
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->text('Sending emails is fun again!')
        foreach($bodyfull['attach'] as $bf){
            $email->addPart((new DataPart(base64_decode($bf['base64']), $bf['cid'], 'image/jpeg'))->asInline());
            //$email->attachData(base64_decode($bf['base64']),$bf['cid'], [
            //    'as' => $bf['cid'],
            //    'mime' => 'image/jpeg',
            //]);
        }
            
        try {
            $mailer->send($email);
        } catch (\Exception $ex) {
            //Utils::vacialog();
            log::info("fallo envio mail console.php userid ".$cliente->user_id." direccion ".$direcciones['address']); // envía el error al registro de logs
            log::info(Utils::extraererrormail($ex)); // envía el error al registro de logs
            $ok = false;
        }
        
        continue;

        try {
            $body = view($vista,['datos' => $datos])->render();
            $bodyfull=Utils::emailtocid($body);
            Mail::html($bodyfull['body'], function ($message) use ($direcciones,$asunto,$reply,$bodyfull) {
                $message->to($direcciones)->subject($asunto)->replyTo([$reply]);
                foreach($bodyfull['attach'] as $bf){
                    $message->attachData(base64_decode($bf['base64']),$bf['cid'], [
                        'as' => $bf['cid'],
                        'mime' => 'image/jpeg',
                    ]);
                }
            });
        } catch (\Exception $ex) {
            //Utils::vacialog();
            log::info("fallo envio mail console.php userid ".$cliente->user_id); // envía el error al registro de logs
            log::info(Utils::extraererrormail($ex)); // envía el error al registro de logs
            $ok = false;
        }
    }
})->dailyAt('13:30'); // })->everyMinute();







// recordatorio de citas de calendario
Schedule::call(function () {
    // recordatorio de cita
    //return;
    $xwaxa=DB::select("SELECT cl.id,user_id,basecalendario_id,cliente_id,start,title,cuerpo FROM calendario cl LEFT JOIN basecalendario bc ON bc.id=basecalendario_id 
        WHERE cliente_id>0 and reservado=1 AND (DATE(START)=DATE_ADD(CURDATE(),interval 7 DAY) OR DATE(START)=DATE_ADD(CURDATE(),interval 1 DAY))");
    //$xwaxa=DB::select("SELECT cl.id,user_id,basecalendario_id,cliente_id,start,title,cuerpo FROM calendario cl LEFT JOIN basecalendario bc ON bc.id=basecalendario_id 
    //    WHERE cliente_id>0 and user_id=6 and reservado=1 AND (DATE(START)=DATE_ADD(CURDATE(),interval 7 DAY) OR DATE(START)=DATE_ADD(CURDATE(),interval 1 DAY))");
    //Log::info($xwa);
    //return;
    foreach($xwaxa as $y){
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        // IMPORTANTISIMO SI NO MANDA LOS MAILS DESDE CUENTAS QUE NO CORRESPONDE
        $idid=$y->id;
        //$userid=$y->user_id;
        //$clienteid=$y->cliente_id;
        $start=$y->start;
        $titulo=$y->title;
        $descripcion=$y->cuerpo;
        $z = User2::select('logo','nombre','iban')->find($y->user_id);
        $logo=$z->logo;
        $empresa=$z->nombre;
        $z=Cliente::where('user_id',$y->user_id)->where('id',$y->cliente_id)->limit(1)->get();
        $clienteemail=$z[0]->email;
        $clientenombre=$z[0]->nombre." ".$z[0]->apellidos;
        $direcciones=['address'=>$clienteemail,'name'=>$clientenombre];
        //$direcciones=['address'=>'josej69@gmail.com','name'=>'jose javier'];
        $vista = "email.recordatoriocita";
        $asunto="Recordatorio: sesión reservada en ".$empresa;
        $mmdd5=md5($y->user_id.'-'.$y->cliente_id.'-'.$idid.Carbon::now());
        $ok = true;
        $datos=[
            'ruta'=>"",
            'logo'=>$logo,
            'empresa'=>$empresa,
            'start'=>$start,
            'titulo'=>$titulo,
            'descripcion'=>$descripcion,
        ];
        $reply=Utils::cargarconfiguracionemailempresa($y->user_id);
        //Log::info($reply);
        try {
            $body = view($vista,['datos' => $datos])->render();
            $bodyfull=Utils::emailtocid($body);
            Mail::html($bodyfull['body'], function ($message) use ($direcciones,$asunto,$reply,$bodyfull) {
                $message->to($direcciones)->subject($asunto)->replyTo([$reply]);
                foreach($bodyfull['attach'] as $bf){
                    $message->attachData(base64_decode($bf['base64']),$bf['cid'], [
                        'as' => $bf['cid'],
                        'mime' => 'image/jpeg',
                    ]);
                }
            });
        } catch (\Exception $ex) {
            //Utils::vacialog();
            //log: :info($ex); // envía el error al registro de logs
            $ok = false;
        }
    }
})->dailyAt('13:30'); // })->everyMinute(); //

// me envío los logs todos los dias
Schedule::call(function () {
    // envio de logs
    $sto = storage_path('logs')."/laravel.log";
    $content = File::get($sto);
    if(strlen($content)>0){
        $reply=Utils::cargarconfiguracionemailempresa(1);
        $direcciones=['josej69@gmail.com'];
        $asunto="registro de log OhMyPhoto";
        try {
            Mail::raw($content, function ($message) use ($direcciones,$asunto) {
                $message->to($direcciones)->subject($asunto);
            });
            Utils::vacialog();
        } catch (\Exception $ex) {
            Log::info("falla mail registro errores ".$ex);
        }
    }
})->dailyAt('03:00'); // })->everyMinute();

// eliminar galerias archivadas
Schedule::call(function () {
    // eliminar galerias archivadas
    //log: :info("list delete trashed");
    $xa=DB::select("SELECT id,user_id,eliminada,fechaeliminada,DATE_SUB(NOW(),interval 7 DAY) FROM galerias WHERE eliminada=1 AND fechaeliminada<DATE_SUB(NOW(),interval 7 DAY)");
    foreach($xa as $ya){
        //log: :info("elimina galeria trashed ".$ya->id);
        //continue;
        $idd=$ya->id;
        $uid=$ya->user_id;
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$uid."/";
        $x=Binarios::where('galeria_id',$idd)->select('id')->get();
        foreach($x as $y){
            $disks3->delete($filePaths3.$y->id.".jpg");
        }
        Binarios::where('id',$idd)->delete();
        Binarios::where('galeria_id',$idd)->delete();
        Productosgaleria::where('galeria_id',$idd)->delete();
        Galeria::find($idd)->delete();
    }
})->dailyAt('05:00');//})->everyMinute();//











//Schedule::call(function () {
//})->dailyAt('13:00'); // })->everyMinute();

//Schedule::call(function () {
//    Log::info("cron ok");
//})->everyMinute();

