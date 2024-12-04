<?php
namespace App\Http;

use Illuminate\Support\Facades\Crypt;
use DB;
use Config;
use Session;
use Request;
use File;
use Log;
use App\Models\Cliente;
use Illuminate\Support\Facades\Mail;
use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use Jenssegers\Agent\Agent;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Utils
{
    public static function vacialog()
    {
        $logFile = base_path() . '/storage/logs/laravel.log';
        file_put_contents($logFile, '');
    }
    public static function browserInfo($usuarioid=0){
        $agent = new Agent(); // https://github.com/jenssegers/agent
        $desktop=$agent->isDesktop();
        if($desktop){
        }
        $platform = $agent->platform();
        //$safari=$agent->isSafari(); // no muy fiable, con chrome marca true
        $browser = $agent->browser();
        $version = $agent->version($browser);
        //Log::info("browser info");
        //Log::info($platform);
        //Log::info($safari);
        //Log::info($browser);
        //Log::info($version);
        //Log::info("end");
        
        // bendito Safari apple salvará al mundo
        $aviff=true;
        try {
            if($browser=="Safari"){
                $xv=explode(".",$version);
                $v1=$xv[0]+0;
                $v2=$xv[1]+0;
                if($v1==16&&$v2<4)
                    $aviff=false;
                if($v1<16)
                    $aviff=false;
            }
        } catch (\Throwable $th) {
        }
        if($usuarioid==-6||$usuarioid==-6){
            Log::info("avif disabled for dev");
            $aviff=false;
        }
        //
        Session::put('soporteavif',$aviff);
        return [
            'plataforma'=>$platform,
            'navegador'=>$browser,
            'desktop'=>$desktop,
            'version'=>$version,
            'soporteavif'=>$aviff,
        ];
    }

    public static function suscripcionactiva(){
        // 0 no tiene licencia 1 periodo de prueba 2 tiene una suscripcion
        $hoy=Carbon::today();
        //$hoy=Carbon::create(2024, 12, 2, 0, 0, 0, 'Europe/Madrid');
        $caduca = Carbon::parse(Auth::user()->caducidad);
        //self::vacialog();
        //Log::info('hoy '.$hoy);
        //Log::info('caducidad '.$caduca);
        $diferencia=$hoy->diffInDays($caduca); // positivo, los dias que me quedan de licencia 0 el dia que caduca
        //Log::info('diferencia '.$diferencia);
        $periodogratuito=false;
        if($diferencia>=0){
            $periodogratuito=true;
        }
        $suscrito=Auth::user()->subscribed('default');
        if($suscrito){
            return 2;
        }
        if($periodogratuito){
            return 1;
        }
        return 0;
    }

    public static function inMacGallery($filename){
        if(Session('soporteavif')){
            return "";
        }
        // NO USO LA ETIQUETA PICTURE PARECE QUE NO CACHEA
        //Log::info($filename);
        $ruta=storage_path('app/public/tmpgallery')."/".$filename;
        $ruta2=storage_path('app/public/tmpgallery')."/".$filename.".mac.jpg";
        if(!File::exists($ruta2)){
            Spatie::load($ruta)->save($ruta2);
        }
        //Log::info(URL("/"));
        return "/storage/tmpgallery/".$filename.".mac.jpg";
        return URL("/")."/storage/tmpgallery/".$filename.".mac.jpg";
    }

    public static function inMacBase64($data){
        //Log::info($data);
        $rut = storage_path('app/public/tmpgallery')."/".Utils::randomString(10).".jpg";
        $bindata=base64_decode($data);
        File::put($rut,$bindata);
        $su=Spatie::load($rut)->base64();
        //Log::info($su);
        File::delete($rut);
        return $su;
    }

    public static function heztnerBoxFileSystem(){
        $filesystem = new Filesystem(new SftpAdapter(
            new SftpConnectionProvider(
                env('SFTP_RUTA'), // host (required)
                env('SFTP_USER'), // username (required)
                env('SFTP_PASS'), // password (optional, default: null) set to null if privateKey is used
                null, // private key (optional, default: null) can be used instead of password, set to null if password is set
                null, // passphrase (optional, default: null), set to null if privateKey is not used or has no passphrase
                22, // port (optional, default: 22)
                false, // use agent (optional, default: false)
                30, // timeout (optional, default: 10)
                10, // max tries (optional, default: 4)
                null, // host fingerprint (optional, default: null),
                null, // connectivity checker (must be an implementation of 'League\Flysystem\PhpseclibV2\ConnectivityChecker' to check if a connection can be established (optional, omit if you don't need some special handling for setting reliable connections)
            ),
            '/ohmyphoto-pruebas', // root path (required)
            PortableVisibilityConverter::fromArray([
                'file' => [
                    'public' => 0640,
                    'private' => 0604,
                ],
                'dir' => [
                    'public' => 0740,
                    'private' => 7604,
                ],
            ])
        ));
        return $filesystem;
    }
    public static function espaciousado($userid){
        // la comentada tarda bastante
        //$x=DB::select("select sum(originalsize)+sum(length(binarios.binario)) as tamano from binarios left join galerias on galerias.id=binarios.galeria_id where user_id=$userid");
        $x=DB::select("
        select sum(tamano) as tamano from (
        (select sum(originalsize) as tamano from binarios left join galerias on galerias.id=binarios.galeria_id where user_id=$userid)
        union all 
        (select sum(originalsize) as tamano from binarios2 left join galerias on galerias.id=binarios2.galeria_id where user_id=$userid)
        union all 
        (select sum(originalsize) as tamano from binarios3 left join galerias on galerias.id=binarios3.galeria_id where user_id=$userid)
        union all 
        (select sum(originalsize) as tamano from binarios4 left join galerias on galerias.id=binarios4.galeria_id where user_id=$userid)
        union all 
        (select sum(originalsize) as tamano from binarios5 left join galerias on galerias.id=binarios5.galeria_id where user_id=$userid)
        ) x
        ");
        $datos=$x[0]->tamano;
        if(is_null($datos))
            return 0;
        return round($datos/1024/1024/1024,2);
    }

    public static function espaciousadototal($userid){
        if($userid!=6&&$userid!=1)
            return 0;
        // la comentada tarda bastante
        //$x=DB::select("select sum(originalsize)+sum(length(binarios.binario)) as tamano from binarios left join galerias on galerias.id=binarios.galeria_id");
        $x=DB::select("
        select sum(tamano) as tamano from (
        (select sum(originalsize) as tamano from binarios left join galerias on galerias.id=binarios.galeria_id)
        union all 
        (select sum(originalsize) as tamano from binarios2 left join galerias on galerias.id=binarios2.galeria_id)
        union all 
        (select sum(originalsize) as tamano from binarios3 left join galerias on galerias.id=binarios3.galeria_id)
        union all 
        (select sum(originalsize) as tamano from binarios4 left join galerias on galerias.id=binarios4.galeria_id)
        union all 
        (select sum(originalsize) as tamano from binarios5 left join galerias on galerias.id=binarios5.galeria_id)
        ) x
        ");
        $datos=$x[0]->tamano;
        if(is_null($datos))
            return 0;
        return round($datos/1024/1024/1024,2);
    }

    public static function str_replace_first($search, $replace, $subject)
    {
        $search = '/'.preg_quote($search, '/').'/';
        return preg_replace($search, $replace, $subject, 1);
    }

    public static function anotacionesproductotoexternalphoto($body,$idproducto,$userid){
        $dom = new \DOMDocument();
        //Log::info($body);
        if(strlen($body)==0)
            return $body;
        if(1==2){
            return $body;
        }
        $dom->loadHTML($body);
        $images = $dom->getElementsByTagName('img');
        $inum=0;
        foreach ($images as $image) {
            $dataimage=$image->getAttribute('src');
            if(self::left($dataimage,23)=="data:image/jpeg;base64,"||self::left($dataimage,22)=="data:image/png;base64,"){
                $inum++;
                $filename=storage_path('app/public/tmpgallery')."/prodexternal-".$idproducto."-".$inum.".jpg";
                $filename2="/storage/tmpgallery/prodexternal-".$idproducto."-".$inum.".jpg";
                $body=str_replace($dataimage,$filename2,$body);
                $dataimage=str_replace("data:image/jpeg;base64,","",$dataimage);
                $dataimage=str_replace("data:image/png;base64,","",$dataimage);
                File::put($filename,base64_decode($dataimage));
            }
        }
        return $body;
    }

    public static function emailtocid($body){
        //Log::info($body);
        //src="data:image/jpeg;base64,
        //src="data:image/png;base64,
        $bodyfull=[];
        $attach=[];
        $dom = new \DOMDocument();
        $dom->loadHTML($body);
        $images = $dom->getElementsByTagName('img');
        $inum=0;
        foreach ($images as $image) {
          $dataimage=$image->getAttribute('src');
          if(self::left($dataimage,23)=="data:image/jpeg;base64,"||self::left($dataimage,22)=="data:image/png;base64,"){
            $inum++;
            $body=str_replace($dataimage,"cid:ima".$inum.'.jpg',$body);
            $dataimage=str_replace("data:image/jpeg;base64,","",$dataimage);
            $dataimage=str_replace("data:image/png;base64,","",$dataimage);
            $attach[]=['cid'=>'ima'.$inum.'.jpg','base64'=>$dataimage];
          }
        }
        
        $bodyfull['body']=$body;
        $bodyfull['attach']=$attach;
        //Log::info($bodyfull);
        //$dom->saveHTML($dom);

        return $bodyfull;
    }

    public static function sendmail($clienteid,$userid,$vista,$nombreempresa,$mailadicional,$asunto,$datos){
        $direcciones = [];
        if($clienteid>0){
            $x=Cliente::where('user_id',$userid)->where('id',$clienteid)->limit(1)->get();
            $clienteemail=$x[0]->email;
            $clientenombre=$x[0]->nombre." ".$x[0]->apellidos;
            $direcciones[]=['address'=>$clienteemail,'name'=>$clientenombre];
        }
        if(strlen($mailadicional)>0){
            $direcciones[]=['address'=>$mailadicional,'name'=>$mailadicional];
        }
        $ok = true;
        $reply=self::cargarconfiguracionemailempresa($userid);

        //Log::info($reply);
        //Log::info(Config::get('mail'));

        $body = view($vista,['datos' => $datos])->render();
        $bodyfull=self::emailtocid($body);
        try {
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
            Log::info(Utils::extraererrormail($ex)); // envía el error al registro de logs
            $ok = false;
        }
        
        
        if($ok){
        }
        return $ok;
    }

    public static function variablecontrato($tip){
        $s='<span style="background-color: orange;color: white;padding: 4px 8px;text-align: center;border-radius: 5px;">NewByte</span> ';
        switch($tip){
            case 1:
                // check obligatorio
                $s=str_replace("NewByte","check",$s);
                break;
            case 2:
                // check opcional
                $s=str_replace("NewByte","check-opcional",$s);
                break;
            case 3:
                // nombre empresa
                $s=str_replace("NewByte","nombreempresa",$s);
                break;
            case 4:
                // nombre propio empresa
                $s=str_replace("NewByte","nombreempresa2",$s);
                break;
            case 5:
                // nif empresa
                $s=str_replace("NewByte","nifempresa",$s);
                break;
            case 6:
                // domicilio empresa
                $s=str_replace("NewByte","domicilioempresa",$s);
                break;
            case 7:
                // cp empresa
                $s=str_replace("NewByte","cpempresa",$s);
                break;
            case 8:
                // poblacion empresa
                $s=str_replace("NewByte","poblacionempresa",$s);
                break;
            case 9:
                // provincia empresa
                $s=str_replace("NewByte","provinciaempresa",$s);
                break;
            case 10:
                // telefono empresa
                $s=str_replace("NewByte","telefonoempresa",$s);
                break;
            case 11:
                // email empresa
                $s=str_replace("NewByte","emailempresa",$s);
                break;
            case 12:
                // nombre cliente
                $s=str_replace("NewByte","nombrecliente",$s);
                break;
            case 13:
                // nif cliente
                $s=str_replace("NewByte","nifcliente",$s);
                break;
            case 14:
                // domicilio cliente
                $s=str_replace("NewByte","domiciliocliente",$s);
                break;
            case 15:
                // cp cliente
                $s=str_replace("NewByte","cpcliente",$s);
                break;
            case 16:
                // poblacion cliente
                $s=str_replace("NewByte","poblacioncliente",$s);
                break;
            case 17:
                // provincia cliente
                $s=str_replace("NewByte","provinciacliente",$s);
                break;
            case 18:
                // telefono cliente
                $s=str_replace("NewByte","telefonocliente",$s);
                break;
            case 19:
                // email cliente
                $s=str_replace("NewByte","emailcliente",$s);
                break;
            case 20:
                // nombre pareja
                $s=str_replace("NewByte","nombrepareja",$s);
                break;
            case 21:
                // nif pareja
                $s=str_replace("NewByte","nifpareja",$s);
                break;
            case 22:
                // hijo
                $s=str_replace("NewByte","hijo1",$s);
                break;
            case 23:
                // hijo
                $s=str_replace("NewByte","hijo2",$s);
                break;
            case 24:
                // hijo
                $s=str_replace("NewByte","hijo3",$s);
                break;
            case 25:
                // hijo
                $s=str_replace("NewByte","hijo4",$s);
                break;
            case 26:
                // hijo
                $s=str_replace("NewByte","hijo5",$s);
                break;
            case 27:
                // hijo
                $s=str_replace("NewByte","hijo6",$s);
                break;
            case 28:
                // fecha contrato
                $s=str_replace("NewByte","fechacontrato",$s);
                break;
            case 29:
                // cuadro de firma
                $s=str_replace("NewByte","cuadrofirma",$s);
                break;
        }

        return $s;
        //$this->ficha->texto=$code;
        //$this->dispatch('refresh quill', ['ob' => $this->ficha->texto]);
        //$this->dispatch('addtoquill', ['ob' => $s]);

    }

    public static function cargarconfiguracionemailempresaarray($userid){
        $x=DB::table('users2')
            ->where('id',$userid)
            ->where('mail_direccion',"<>","")
            ->where('mail_username',"<>","")
            ->where('mail_smtp',"<>","")
            ->get()->toArray();
        if(count($x)==0){
            $x=DB::table('users')->where('id',$userid)->get()->toArray();
            return [
                'direccion'=>$x[0]->email,
                'configuradocliente'=>false,
            ];
        }
        Config::set('mail.driver',"smtp"); // si es gmail solo funciona con driver mail
        if($x[0]->mail_smtp=="smtp.gmail.com"){
            Config::set('mail.port',465);
            Config::set('mail.encryption',"ssl");
            Config::set('mail.port',587); // gmail 465 en hetzner parece que está bloqueado
            Config::set('mail.encryption',"tls"); // gmail 587 se supone q es tls
        }
        Config::set('mail.port',587); // gmail 465 en hetzner parece que está bloqueado
        Config::set('mail.encryption',"tls"); // gmail 587 se supone q es tls


        Config::set('mail.host',$x[0]->mail_smtp);
        Config::set('mail.from',array(
                'address'=>$x[0]->mail_direccion,
                'name'=>$x[0]->nombre));
        Config::set('mail.username',$x[0]->mail_username);
        Config::set('mail.password',self::codificarPassword($x[0]->mail_password,"d"));
        return [
            'direccion'=>$x[0]->mail_direccion,
            'configuradocliente'=>true,
        ];
        return $x[0]->mail_direccion;
    }

    public static function extraererrormail($data){
        //Stack trace
        $data=str_replace("Symfony\Component\Mailer\Exception\TransportException: ","",$data);
        $pos = strpos($data, "Stack trace");
        $pos = strpos($data, "in /var");
        return "Descripción del error: ".substr($data,0,$pos);
    }

    public static function cargarconfiguracionemailempresa($useridxa){
        Config::set('mail.driver',"smtp"); // si es gmail solo funciona con driver mail
        
        $x=DB::table('users2')
            ->where('id',$useridxa)
            ->where('mail_direccion',"<>","")
            ->where('mail_username',"<>","")
            ->where('mail_smtp',"<>","")
            ->get()->toArray();
        if(count($x)==0){
            Config::set('mail.port',env('MAIL_PORT')); // gmail 465 en hetzner parece que está bloqueado
            Config::set('mail.encryption',env('MAIL_ENCRYPTION')); // gmail 587 se supone q es tls
            Config::set('mail.host',env('MAIL_HOST'));
            Config::set('mail.username',env('MAIL_USERNAME'));
            Config::set('mail.password',env('MAIL_PASSWORD'));
            $xy=DB::table('users')->where('id',$useridxa)->get()->toArray();
            Config::set('mail.from',array(
                    'address'=>env('MAIL_FROM_ADDRESS'),
                    'name'=>$xy[0]->name));
        //Log::info(Config::get('mail'));
        return $xy[0]->email;


        }
        Config::set('mail.port',587); // gmail 465 en hetzner parece que está bloqueado
        Config::set('mail.encryption',"tls"); // gmail 587 se supone q es tls
        Config::set('mail.host',$x[0]->mail_smtp);
        Config::set('mail.from',array(
                'address'=>$x[0]->mail_direccion,
                'name'=>$x[0]->nombre));
        Config::set('mail.username',$x[0]->mail_username);
        Config::set('mail.password',self::codificarPassword($x[0]->mail_password,"d"));
        return $x[0]->mail_direccion;
    }

    public static function calcularpreciogaleria($idgaleria,$ficha,$galeria,$formaspago,$productos)
    {
        $desgloseado=[];
        $preciogaleria=$ficha->preciogaleria+0;
        $desgloseado[0]=['texto'=>'precio de la galería','importe'=>$preciogaleria];
        $preciogaleriacompleta=$ficha->preciogaleriacompleta;
        $entregado=$ficha->entregado;
        $numfotos=$ficha->numfotos+0;
        $maxfotos=$ficha->maxfotos+0;
        $totalfotos=count($galeria);
        if($maxfotos<$numfotos){
            $maxfotos=$totalfotos;
        }
        $preciofoto=$ficha->preciofoto;
        $seleccionadas=\App\Models\Binarios::where('galeria_id',$idgaleria)->where('selected',true)->count();
        $totalfotos=\App\Models\Binarios::where('galeria_id',$idgaleria)->count();
        $x=DB::select("select sum(originalsize) as tamano from binarios where galeria_id=$idgaleria");
        $datos=$x[0]->tamano;
        if(is_null($datos))
            $sizegallery=0;
        $sizegallery=round($datos/1024/1024,0);
        // lo siguiente se deja pero siempre va a estar a 0, se ha quitado de las pantallas
        $opcional1=$ficha->opcional1;
        $precioopc1=$ficha->precioopc1;
        $selopc1=$ficha->selopc1;
        $opcional2=$ficha->opcional2;
        $precioopc2=$ficha->precioopc2;
        $selopc2=$ficha->selopc2;
        $opcional3=$ficha->opcional3;
        $precioopc3=$ficha->precioopc3;
        $selopc3=$ficha->selopc3;
        $opcional4=$ficha->opcional4;
        $precioopc4=$ficha->precioopc4;
        $selopc4=$ficha->selopc4;
        $opcional5=$ficha->opcional5;
        $precioopc5=$ficha->precioopc5;
        $selopc5=$ficha->selopc5;
        $opcional6=$ficha->opcional6;
        $precioopc6=$ficha->precioopc6;
        $selopc6=$ficha->selopc6;
        $opcional7=$ficha->opcional7;
        $precioopc7=$ficha->precioopc7;
        $selopc7=$ficha->selopc7;
        $opcional8=$ficha->opcional8;
        $precioopc8=$ficha->precioopc8;
        $selopc8=$ficha->selopc8;
        $opcional9=$ficha->opcional9;
        $precioopc9=$ficha->precioopc9;
        $selopc9=$ficha->selopc9;
        $opcional10=$ficha->opcional10;
        $precioopc10=$ficha->precioopc10;
        $selopc10=$ficha->selopc10;
        // fin de opciones quitadas
        $pack1=$ficha->pack1;
        $pack2=$ficha->pack2;
        $pack3=$ficha->pack3;
        $pack1p=$ficha->pack1precio;
        $pack2p=$ficha->pack2precio;
        $pack3p=$ficha->pack3precio;

        $precio=$preciogaleria;
        $sobrebase=$seleccionadas-$numfotos;

        $haypacks=true;
        if($seleccionadas==$totalfotos && $preciogaleriacompleta>0){
            // el cliente ha marcado galeria completa y tiene precio, los packs no se aplican
            $haypacks=false;
        }

        if($pack3<=$sobrebase&&$pack3>0 && $haypacks){
            $precio+=$pack3p;
            $sobrebase=$sobrebase-$pack3;
            $desgloseado[]=['texto'=>'pack de '.$pack3.' fotografías','importe'=>$pack3p+0];
        }
        if($pack2<=$sobrebase&&$pack2>0 && $haypacks){
            $precio+=$pack2p;
            $sobrebase=$sobrebase-$pack2;
            $desgloseado[]=['texto'=>'pack de '.$pack2.' fotografías','importe'=>$pack2p+0];
        }
        if($pack1<=$sobrebase&&$pack1>0 && $haypacks){
            $precio+=$pack1p;
            $sobrebase=$sobrebase-$pack1;
            $desgloseado[]=['texto'=>'pack de '.$pack1.' fotografías','importe'=>$pack1p+0];
        }

        if($sobrebase>0&&($seleccionadas<$totalfotos || $preciogaleriacompleta==0)){
            //$dife=$seleccionadas-$numfotos;
            $precio+=$preciofoto*$sobrebase;
            $desgloseado[]=['texto'=>$sobrebase.' fotografías adicionales','importe'=>$preciofoto*$sobrebase];
        }

        if($seleccionadas==$totalfotos && $preciogaleriacompleta>0){
            //$precio=$preciogaleriacompleta+$preciogaleria;
            //$desgloseado[0]=['texto'=>'precio de la galería completa','importe'=>$preciogaleriacompleta+$preciogaleria+0];
            $precio=$preciogaleriacompleta;
            $desgloseado[0]=['texto'=>'precio de la galería completa','importe'=>$preciogaleriacompleta+0];
        }

        // lo siguiente se deja pero siempre va a estar a 0, se ha quitado de las pantallas
        if($selopc1)
            $precio+=$precioopc1;
        if($selopc2)
            $precio+=$precioopc2;
        if($selopc3)
            $precio+=$precioopc3;
        if($selopc4)
            $precio+=$precioopc4;
        if($selopc5)
            $precio+=$precioopc5;
        if($selopc6)
            $precio+=$precioopc6;
        if($selopc7)
            $precio+=$precioopc7;
        if($selopc8)
            $precio+=$precioopc8;
        if($selopc9)
            $precio+=$precioopc9;
        if($selopc10)
            $precio+=$precioopc10;
        // fin opciones quitadas

        //self::vacialog();
        //log::info($productos);
        foreach($productos as $prod){
            if($prod['seleccionada']==false){
                continue;
            }
            if($prod['incluido']){
                // cantidad seleccionadas
                $x=0;
                $cantidadseleccionadas=0;

                if(strlen($prod['seleccionfotos'])>2){
                    $c=json_decode($prod['seleccionfotos']);
                    foreach($c as $cc)
                        $cantidadseleccionadas+=$cc->cantidad;
                }
                
                //Log::info($cantidadseleccionadas);
                if($cantidadseleccionadas>$prod['numfotos']){
                    $aditionals=$cantidadseleccionadas-$prod['numfotos'];
                    $x+=$aditionals*$prod['preciofotoadicional'];
                    $precio+=$aditionals*$prod['preciofotoadicional'];
                }
                $desgloseado[]=['texto'=>''.$prod['nombre'].' (incluído)','importe'=>$x];
                continue;
            }
            $y=$prod['cantidad'];
            $x=($prod['precioproducto']*$y);
            $precio+=($prod['precioproducto']*$y);

            // cantidad seleccionadas
            $cantidadseleccionadas=0;
            if(strlen($prod['seleccionfotos'])>2){
                $c=json_decode($prod['seleccionfotos']);
                foreach($c as $cc)
                    $cantidadseleccionadas+=$cc->cantidad;
            }
            //Log::info($cantidadseleccionadas);
            //'numfotos' => 3,
            //'numfotosadicionales' => 2,
            //'preciofotoadicional' => 12.0,
            if($cantidadseleccionadas>$prod['numfotos']){
                $aditionals=$cantidadseleccionadas-$prod['numfotos'];
                $x+=$aditionals*$prod['preciofotoadicional']*$y;
                $precio+=$aditionals*$prod['preciofotoadicional']*$y;
            }
            //

            if($prod['selopc1']){
                $precio+=($prod['precio1']*$y);
                $x+=($prod['precio1']*$y);
            }
            if($prod['selopc2']){
                $precio+=($prod['precio2']*$y);
                $x+=($prod['precio2']*$y);
            }
            if($prod['selopc3']){
                $precio+=($prod['precio3']*$y);
                $x+=($prod['precio3']*$y);
            }
            if($prod['selopc4']){
                $precio+=($prod['precio4']*$y);
                $x+=($prod['precio4']*$y);
            }
            if($prod['selopc5']){
                $precio+=($prod['precio5']*$y);
                $x+=($prod['precio5']*$y);
            }
            if($y>1)
                $desgloseado[]=['texto'=>$y.' x '.$prod['nombre'],'importe'=>$x+0];
            if($y==1)
                $desgloseado[]=['texto'=>$prod['nombre'],'importe'=>$x+0];
        }

        if($ficha->tipodepago==4){
            $prcc=$formaspago->ppalprc;
            if($prcc>0){
                $precio=round($precio*(1+($prcc/100)),2);
                $desgloseado[]=['texto'=>'incremento pago PayPal '.$prcc.'%','importe'=>round($precio*(0+($prcc/100)),2)];
            }
        }
        //if($ficha->tipodepago==5){
        //    $prcc=$formaspago->stripeprc;
        //    if($prcc>0){
        //        $precio=round($precio*(1+($prcc/100)),2);
        //    }
        //}
        if($entregado>0){
            $desgloseado[]=['texto'=>'entregado señal','importe'=>($entregado*(-1))+0];
        }
        $precio-=$entregado;
        $procesable=false;
        //$seleccionadas=$seleccionadas;
        if($numfotos==0 && $maxfotos==0){
            //$seleccionadas=0; // galeria en la que no tienes que seleccionar fotos
            $procesable=true;
        }
        if($seleccionadas>=$numfotos&&$seleccionadas<=$maxfotos){
            $procesable=true;
        }
        //log: :info($desgloseado);
        return [
            'precio'=>$precio,
            'procesable'=>$procesable,
            'seleccionadas'=>$seleccionadas,
            'totalfotos'=>$totalfotos,
            'size'=>$sizegallery,
            //'desgloseado'=>['texto'=>'entregado','importe'=>0],
            'desgloseado'=>$desgloseado,
        ];
    }
    public static function right($string,$numchars,$tolower=false){
        // el right de foxpro
        $string=trim($string);
        if($tolower)
            $string=strtolower($string);
        return substr($string,$numchars*(-1));
    }
    public static function left($string,$numchars,$tolower=false){
        // el left de foxpro
        $string=trim($string);
        if($tolower)
            $string=strtolower($string);
        return substr($string,0,$numchars);
    }
    public static function fechaesptoansi($dato){
        $dato=substr($dato,0,10);
        $dia=substr($dato,0,2);
        $mes=substr($dato,3,2);
        $ano=substr($dato,-4);
        return $ano."-".$mes."-".$dia;
    }
    public static function md5Password($valor)
    {
        return strtoupper(md5("xgest@xweb" . $valor));
    }
    public static function truefalse($valor_tf)
    {
        if (trim($valor_tf) == "S") {
            return true;
        }
        return false;
    }
    public static function mysqlRealEscape($valor, $comillas = true)
    {
        $valor = str_replace(chr(92), chr(92) . chr(92), $valor);
        $valor = str_replace(chr(0), "\0", $valor);
        $valor = str_replace("'", "\'", $valor);
        $valor = str_replace(chr(34), chr(92) . chr(34), $valor);
        $valor = str_replace(chr(8), "\b", $valor);
        $valor = str_replace(chr(10), "\n", $valor);
        $valor = str_replace(chr(13), "\r", $valor);
        $valor = str_replace(chr(9), "\t", $valor);
        $valor = str_replace(chr(26), "\Z", $valor);
        if ($comillas) {
            return "'" . $valor . "'";
        } else {
            return $valor;
        }
    }
    public static function numeroRegistrosObjeto($objeto)
    {
        return count((array) $objeto);
    }
    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function charToAsciiEncodePHP($tto_1)
    {
        // codificacion en ascii con un toque de encriptación personalizada portada tambien a PHP
        $tto_1 = trim($tto_1);
        $ttofin_1 = "";
        $controu = 0;
        for ($ttt_1 = 0; $ttt_1 < strlen($tto_1); $ttt_1++) {
            $prt_1 = substr($tto_1, $ttt_1, 1);
            $controu = $controu + ord($prt_1);
            $prt_1 = ord($prt_1) + $ttt_1 + 1;
            $prt_1 = substr("000" . trim($prt_1), -3); // revisar funcion str_pad
            $ttofin_1 .= $prt_1;
        }
        $ccontrol = substr("000"+trim($controu), -3);
        return $ttofin_1 . "" . $ccontrol;
    }
    public static function charToAsciiDecodePHP($tto_1)
    {
        // codificacion en ascii con un toque de encriptación personalizada portada tambien a PHP
        $tto_1 = trim($tto_1);
        // compruebo que es numerico
        $blan = $tto_1;
        $blan = str_replace("0", "", $blan);
        $blan = str_replace("1", "", $blan);
        $blan = str_replace("2", "", $blan);
        $blan = str_replace("3", "", $blan);
        $blan = str_replace("4", "", $blan);
        $blan = str_replace("5", "", $blan);
        $blan = str_replace("6", "", $blan);
        $blan = str_replace("7", "", $blan);
        $blan = str_replace("8", "", $blan);
        $blan = str_replace("9", "", $blan);
        if (strlen($blan) > 0) {
            return "errorLess";
        }
        //
        $reto = "";
        $actu = 0;
        $ccontrol = substr($tto_1, -3);
        $tto_1 = substr($tto_1, 0, strlen($tto_1) - 3);
        $controu = 0;
        $lentto_1 = strlen($tto_1);
        for ($tth_1 = 0; $tth_1 < $lentto_1; $tth_1 += 3) {
            $actu = $actu + 1;
            $prt_1 = (substr($tto_1, 0, 3)) - $actu;
            $controu = $controu + $prt_1;
            $tto_1 = substr($tto_1, 3);
            $reto = $reto . chr($prt_1);
        }
        $ccontrol2 = substr("000" . trim($controu), -3); // revisar funcion str_pad
        if ($ccontrol !== $ccontrol2) {
            return $reto;
            return "error";
        }
        return $reto;
    }
    public static function soloNumLet($texo, $dejarespacios = true, $dejarpuntos = true, $dejarcomas = false)
    {
        $cadbus = "1234567890abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ" . ($dejarpuntos ? "." : "") . ($dejarcomas ? "," : "") . ($dejarespacios ? " " : "");
        $rexo = "";
        for ($pas = 0; $pas < strlen($texo); $pas++) {
            $actual = substr($texo, $pas, 1);
            $pos = strpos($cadbus, $actual);
            if ($pos === false) {
                continue;
            }
            $rexo .= $actual;
        }
        return $rexo;
    }
    public static function matrizProvincias($todo=1)
    {
        $prov = [];
        $prov[]=['nombre'=>'','geo'=>'ES'];
        if(!File::exists(app_path('Xgest'.DIRECTORY_SEPARATOR.'geo.txt'))) {
            Log::info("error al leer provincias ".app_path('Xgest'.DIRECTORY_SEPARATOR.'geo.txt'));
            return $prov;
        }

        $geo="ES"; // ES
        $file = fopen(app_path('Xgest'.DIRECTORY_SEPARATOR.'geo.txt'), "r");
        while(!feof($file)) {
            $line = trim(fgets($file));
            if($line=="-especiales"){
                $geo="ESP";
                continue;
            }
            if($line=="-comunitario"){
                $geo="COM";
                continue;
            }
            if($line=="-extranjero"){
                $geo="EXT";
                continue;
            }
            $prov[]=['nombre'=>$line,'geo'=>$geo];
        }
        fclose($file);
        //L og::info($prov);
        return $prov;
    }
    public static function datetime($nped){
        return date('d/m/Y H:i:s',strtotime($nped));
    }
    public static function datestr($nped){
        return date('d/m/Y',strtotime($nped));
    }

    public static function pedidobarras($nped)
    {
        /**
         * Devuelve el valor introducido $nped en formato XXXX XXXX/XXXXXX.
         * Por ejemplo, el número de pedido 302012123456 se devuelve en '30 2012/123456'.
         * Esta funcion se utiliza a nivel interno del Framework, no se usa directamente, sino desde otras funciones del Framework.
         */
        $noo = "         " . $nped;
        $noo = substr($noo, strlen($noo) - 14);
        $noo1 = substr($noo, 0, 4);
        $noo2 = substr($noo, 4, 4);
        $noo3 = substr($noo, 8, 6);
        return trim("$noo1 $noo2/$noo3");
    }
    public static function fechaEsp($fcaa)
    {
        /**
         * Devuelve una fecha en formato BRITISH de una fecha en formato ANSI:<br/>
         * convierte AAAA-MM-DD en DD/MM/AAAA
         */
        return substr($fcaa, 8, 2) . "/" . substr($fcaa, 5, 2) . "/" . substr($fcaa, 0, 4);
    }
    public static function numFormat($numero, $digitos = 2, $sepdec = ",", $sepmil = "", $enterosindecimales = false)
    {
        /**
         * Devuelve la variable numerica $numero en formato caracter con el numero de digitos establecido en $digitos y con el separador decimal $sepdec y separador de miles $sepmil especificados.
         * Esta funcion se utiliza a nivel interno del Framework, no se usa directamente, sino desde otras funciones del Framework.
         */
        if ($enterosindecimales) {
            if (intval($numero) == $numero) {
                return number_format($numero, 0, $sepdec, $sepmil);
            }
        }
        return number_format(round($numero, $digitos), $digitos, $sepdec, $sepmil);
    }
    public static function objectToArray($objin)
    {
        foreach ($objin as $key => $obe) {
            $objin[$key] = (array) $objin[$key];
        }
        return $objin;
    }
    public static function urlenc($data, $encdec = 1)
    {
        switch ($encdec) {
            case 1:
                // encode
                $data = rawurlencode($data);
                $data = str_replace("%2F", "%252F", $data);
                return $data;
                break;
            case 2:
                // decode
                $data = str_replace("%252F", "%2F", $data);
                $data = rawurldecode($data);
                return $data;
                break;
        }
    }
    public static function urlenc2($data)
    {
        $data = str_replace(" ", "_", $data);
        $data = str_replace("__", "_", $data);
        $data = str_replace("/", "_", $data);
        $data = rawurlencode($data);
        $data = str_replace("__", "_", $data);
        $data = str_replace("__", "_", $data);
        $data = str_replace("%2F", "_", $data);
        $data = str_replace("%20", "_", $data);
        $data = str_replace("%2", "_", $data);
        return $data;
    }
    public static function fechaactual($sumaresta = 0)
    {

        // $sumaresta=días a sumar ó restar a la fecha actual
        /**
         * Devuelve la fecha actual en formato:<br/>
         * AAAA/MM/DD<br/>
         * año / mes / día<br/>
         * Si se usa el parámetro, se sumarán o restarán la cantidad de días a la fecha actual
         */
        if ($sumaresta == 0) {
            return date('Y-m-d');
        }
        return date('Y-m-d', strtotime("$sumaresta days"));
    }

    public static function unidadesmedida()
    {
        return [
            '0.25',
            '0.50',
            '0.75',
            '1.00',
            '1.25',
            '1.50',
            '1.75',
            '2.00',
            '2.25',
            '2.50',
            '2.75',
            '3.00',
            '3.25',
            '3.50',
            '3.75',
            '4.00',
            '4.25',
            '4.50',
            '4.75',
            '5.00',
            '5.25',
            '5.50',
            '5.75',
            '6.00',
            '6.25',
            '6.50',
            '6.75',
            '7.00',
            '7.25',
            '7.50',
            '7.75',
            '8.00',
            '8.25',
            '8.50',
            '8.75',
            '9.00',
            '9.25',
            '9.50',
            '9.75',
            '10.00',
        ];
    }

    public static function SEOFriendly($objeto)
    {
        $objeto = str_replace(" ", "-", $objeto);
        $objeto = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", '', $objeto);
        return $objeto;
    }

    public static function my323()
    {
        return;
        try {
            DB::connection()->getPdo()->exec("set session sql_mode='MYSQL323'"); // soluciona los pu**s null que llevan años dando castigo
        } catch (\Throwable $th) {}
    }

    public static function vaciaimagenesarticulos()
    {
        $ruta = base_path() . "/public/imgproductos";
        $filesInFolder = File::allFiles($ruta);
        foreach ($filesInFolder as $path) {
            $files = pathinfo($path);
            $filee = $files['basename'];
            if(str_contains(strtolower($filee),'_nofoto.jpg'))
                continue;
            if(str_contains(strtolower($filee),'.jpg'))
                unlink($ruta.DIRECTORY_SEPARATOR.$filee);
        }

    }

    public static function rawUrlEncodeDecode($data, $encdec = 1)
    {
        switch ($encdec) {
            case 1:
                // encode
                $data = rawurlencode($data);
                $data = str_replace("%2F", "%252F", $data);
                return $data;
                break;
            case 2:
                // decode
                $data = str_replace("%252F", "%2F", $data);
                $data = rawurldecode($data);
                return $data;
                break;
        }
    }

    public static function precioFormat($numero, $digitos = 2, $sepdec = ",", $sepmil = "",$decimalesfijos=false)
    {
        /**
         * Devuelve la variable numerica $numero en formato caracter con el numero de digitos establecido en $digitos y con el separador decimal $sepdec y separador de miles $sepmil especificados.
         * Esta funcion se utiliza a nivel interno del Framework, no se usa directamente, sino desde otras funciones del Framework.
         * Si el número es igual a la parte entera del número no muestra los decimales.
         */
        if (intval($numero) == $numero && !$decimalesfijos) {
            return number_format($numero, 0, $sepdec, $sepmil);
        }
        return number_format(round($numero, $digitos), $digitos, $sepdec, $sepmil);
    }
    public static function precioMenosDescuento($cantidad, $dto)
    {
        return $cantidad - ($cantidad * $dto / 100);
    }
    public static function calculaporcentaje($total, $porcentaje, $redondeo = 2)
    {
        /**
         * Devuelve el el tanto por ciento $porcentaje del valor $total -> ($total*$porcentaje)/100.
         * Esta funcion se utiliza a nivel interno del Framework, no se usa directamente, sino desde otras funciones del Framework.
         */
        if ($total == 0 || $porcentaje == 0) {
            return 0;
        }

        return round(($total * $porcentaje) / 100, $redondeo);
    }

    public static function codificarPassword($string, $action = 'e')
    {
        // you may change these values to your own
        $secret_key = 'xgest@xweb';
        $secret_iv = 'xgest@xweb';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    
    public static function _creblo()
    {
        return;
        //L og::info("ct");
        DB::statement("c reate table if not exists aaaa_web_bloqueoip
        (ip char(80),
        dtime timestamp not null default current_timestamp,
        bloquear int(1) not null default 0,
        index ip (ip),
        index bloquear (bloquear))");
    }
    public static function _array2String($data)
    {
        $log_a = "";
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $log_a .= "[" . $key . "] => (" . self::array2string($value) . ") \n";
            } else {
                $log_a .= "[" . $key . "] => " . $value . "\n";
            }

        }
        return $log_a;
    }

    public static function _entero_decimal($data, $parte = 1)
    {
        $pentera = intval($data);
        $pdecimal = round($data - $pentera, 2) * 100;
        $pentera = $pentera . "";
        $pdecimal = substr("00" . $pdecimal, -2);
        switch ($parte) {
            case 1:
                return $pentera;
                break;
            case 2:
                return $pdecimal;
                break;
        }
    }

}
