<?php

namespace App\Http\Livewire\Galeria;

use App\Models\Galeria;
use App\Models\Cliente;
use App\Models\Binarios;
use App\Models\Binarios2;
use App\Models\Binarios3;
use App\Models\Binarios4;
use App\Models\Binarios5;
use App\Models\User;
use App\Models\User2;
use App\Models\Formaspago;
use App\Models\Avisos;
use App\Models\Productosgaleria;
use App\Models\Downloadmail;
use Livewire\Component;
use File;
use App\Http\Utils;
use DB;
use URL;
use Log;
use Livewire\WithFileUploads;
use Image;
use Storage;
use Request;
use Response;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Charge;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use ZipArchive;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;

class Galeriacliente extends Component
{
    use WithFileUploads;
    public Galeria $ficha;
    public Cliente $cli;
    public $start=true;
    public $descargadisponible=false;
    public $idgaleria=0;
    public $rutadescarga="";
    public $md5="";
    public $logo="";
    public $iban="";
    public $empresa="";
    public $claveacceso="";
    public $pagado=false;
    public $pagadomanual=false;
    public $seleccionconfirmada=false;
    public $seleccionamostrar=1; // 1 todas 2 seleccionadas 3 no seleccionadas
    public $seccion = 1;
    public $precio = 0;
    public $seleccionadas = 0;
    public $procesable = 0;
    public $permitircomentarios = 1;
    public $galeria=[];
    public $galeriabis=[];
    public $galeriabiscount=0;
    public $formaspago;
    public $enlacedescarga="";
    public $desgloseado=[];
    public $amount=0;
    public $cardNumber="";
    public $cardExpiryMonth="";
    public $cardExpiryYear="";
    public $cardCVC="";
    public $stripetoken="";
    public $stripepublica="";
    public $stripesecreta="";
    public $stripeconfirmapago="";
    public $emailcliente="";
    public $error="";
    public $col=12;
    public $colmd=4;
    public $productos=[];
    public $productoseleccion=[];
    public $productoseleccionid=-1;
    public $versoloproductos=1; // 1 todos 2 seleccionados 3 no seleccionados
    public $versolofotosproductos=1; // 1 todos 2 seleccionados 3 no seleccionados
    public $versolofotosproductossolonotas=false; // 1 todos 2 seleccionados 3 no seleccionados
    public $textColor="";
    public $vista="livewire.galeria.galeriacliente";
    public $peticiondescarga=false;
    public $modoprueba=false;
    public $imagen="";

    public $haypaypal=false;
    public $paypalclientid="";
    public $paypalsecret="";
    public $paypalruta="";
    public $configppal;
    public $rutappal;
    public $rutappal2;
    public $paypalProduccion;

    public $hayredsys=false;
    public $redsyscodcomercio="";
    public $redsysclacomercio="";
    public $redsysterminal=1;
    public $redsys=[
        'tpvredsys'=>false, // tpv redsys
        'tpvredsysProduccion'=>false, // tpv redsys
        'tpvredsysMoneda'=>"", // tpv redsys
        'tpvredsysMerchantOrder'=>"", // tpv redsys
        'tpvredsysMerchantCode'=>"", // tpv redsys
        'tpvredsysMerchantSignature'=>"", // tpv redsys
        'tpvredsysSignature256'=>"", // tpv redsys
        'tpvredsysMerchantParameters256'=>"", // tpv redsys
        'tpvredsysMerchantTerminal'=>0, // tpv redsys
        'tpvredsysMerchantClaveComercio'=>"", // tpv redsys
        'tpvredsysBizum'=>false, // tpv redsys
        'rutaredsys'=>"" // tpv redsys
    ];

    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'ficha.tipodepago'=>'',
            'ficha.permitircomentarios'=>'',
            'ficha.selopc1' => '','ficha.selopc2' => '','ficha.selopc3' => '','ficha.selopc4' => '','ficha.selopc5' => '',
            'ficha.selopc6' => '','ficha.selopc7' => '','ficha.selopc8' => '','ficha.selopc9' => '','ficha.selopc10' => '',
            'amount' => 'required|numeric|between:5,500',
            'cardNumber' => 'required|regex:/^[45]\d{15}$/',
            'cardExpiryMonth' => 'required|numeric|between:1,12',
            'cardExpiryYear' => 'required|numeric|digits:4',
            'cardCVC' => 'required|numeric|digits:3',
        ];
    }

    public function mount($idgal,$md5,$error=""){
        $bi=Utils::browserInfo(); // no quitar establece variables de sesion en el arranque!!!
        $this->error=$error;
        if(strlen($error)>0)
            $this->start=false;
        $this->idgaleria=$idgal;
        $this->md5=$md5;
        $galmd5=md5($idgal."ckeck");
        if($md5!=$galmd5){
            // fallamos
            $this->vista="livewire.authentication.error.error404";
            return;
        }
        $regs = Galeria::where('id',$idgal)->where('archivada',0)->where('eliminada',0)->whereRaw('(caducidad>=curdate() or pagado=1 or pagadomanual=1)')->count();
        if($regs==0){
            // fallamos
            $this->vista="livewire.authentication.error.error404caducada";
            return;
        }
        $this->ficha = Galeria::whereRaw('(caducidad>=curdate() or pagado=1 or pagadomanual=1)')->find($idgal);
        if($this->ficha->user_id==6){
            //$this->modoprueba = true; // para mis pruebas
        }
        if($this->modoprueba){
            //$this->ficha->binario="";
        }

        // fuera binarios cargan el modelo transfieren muchos datos
        $this->imagen="";
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $filegal="galcli".md5($idgal).".jpg";
        File::delete($storage_path.$filegal);
        if(strlen($this->ficha->binario)>0){
            $bindata=base64_decode($this->ficha->binario);
            File::put($storage_path.$filegal,$bindata);
            $this->ficha->binario="";
            $this->imagen="/storage/tmpgallery/".$filegal;
            if(!Session('soporteavif')){
                Spatie::load($storage_path.$filegal)->save($storage_path.$filegal);
            }
        }
        //

        $this->ficha->archivada=$this->ficha->archivada==1?true:false;
        //$this->ficha->permitirdescarga=$this->ficha->permitirdescarga==1?true:false;
        $this->ficha->marcaagua=$this->ficha->marcaagua==1?true:false;
        $this->ficha->nombresfotos=$this->ficha->nombresfotos==1?true:false;
        //$this->ficha->clic ambiapago=$this->ficha->clic ambiapago==1?true:false;
        $this->ficha->pagado=$this->ficha->pagado==1?true:false;
        $this->ficha->pagadomanual=$this->ficha->pagadomanual==1?true:false;
        $this->ficha->descargada=$this->ficha->descargada==1?true:false;
        $this->ficha->seleccionconfirmada=$this->ficha->seleccionconfirmada==1?true:false;
        $this->ficha->selopc1=$this->ficha->selopc1==1?true:false;
        $this->ficha->selopc2=$this->ficha->selopc2==1?true:false;
        $this->ficha->selopc3=$this->ficha->selopc3==1?true:false;
        $this->ficha->selopc4=$this->ficha->selopc4==1?true:false;
        $this->ficha->selopc5=$this->ficha->selopc5==1?true:false;
        $this->ficha->selopc6=$this->ficha->selopc6==1?true:false;
        $this->ficha->selopc7=$this->ficha->selopc7==1?true:false;
        $this->ficha->selopc8=$this->ficha->selopc8==1?true:false;
        $this->ficha->selopc9=$this->ficha->selopc9==1?true:false;
        $this->ficha->selopc10=$this->ficha->selopc10==1?true:false;
        $this->ficha->pago1activo=$this->ficha->pago1activo==1?true:false;
        $this->ficha->pago2activo=$this->ficha->pago2activo==1?true:false;
        $this->ficha->pago3activo=$this->ficha->pago3activo==1?true:false;
        $this->ficha->pago4activo=$this->ficha->pago4activo==1?true:false;
        $this->ficha->pago5activo=$this->ficha->pago5activo==1?true:false;
        $this->ficha->pago6activo=$this->ficha->pago6activo==1?true:false;

        if($this->ficha->pagado||$this->ficha->pagadomanual||$this->ficha->seleccionconfirmada){
            $this->versoloproductos=2;
        }

        $this->formaspago = Formaspago::find($this->ficha->user_id);
        $this->haypaypal = $this->formaspago->paypal==1?true:false;
        $this->paypalclientid = $this->formaspago->ppalclientid;
        $this->paypalsecret = $this->formaspago->ppalsecret;
        $this->paypalProduccion = true;
        if($this->ficha->user_id==6){
            $this->paypalProduccion = false; // para mis pruebas
        }
        $this->stripepublica=$this->formaspago->stripe_publica;
        $this->stripesecreta=$this->formaspago->stripe_secreta;
        $this->hayredsys=$this->formaspago->redsys==1?true:false;
        if($this->hayredsys){
            $this->redsyscodcomercio=$this->formaspago->rscodcomercio;
            $this->redsysclacomercio=$this->formaspago->rsclacomercio;
            $this->redsysterminal=$this->formaspago->rsterminal;
            include_once(app_path() . DIRECTORY_SEPARATOR . 'RedsysAPIphp7.php');
        }
        $this->stripeconfirmapago=md5($idgal.'stripeando'.$md5);
        $this->configurapaypal();

        // evitar que haya seleccionado un tipo de pago no disponible
        $fallotipago=false;
        $tipago=($this->ficha->tipodepago);
        if($tipago==1 && $this->ficha->pago1activo==false)
            $fallotipago=true;
        if($tipago==2 && $this->ficha->pago2activo==false)
            $fallotipago=true;
        if($tipago==3 && $this->ficha->pago3activo==false)
            $fallotipago=true;
        if($tipago==4 && $this->ficha->pago4activo==false)
            $fallotipago=true;
        if($tipago==5 && $this->ficha->pago5activo==false)
            $fallotipago=true;
        if($tipago==6 && $this->ficha->pago6activo==false)
            $fallotipago=true;
        if($fallotipago && $this->ficha->pago1activo){
            $this->ficha->tipodepago=1;$fallotipago=false;
        }
        if($fallotipago && $this->ficha->pago2activo){
            $this->ficha->tipodepago=2;$fallotipago=false;
        }
        if($fallotipago && $this->ficha->pago3activo){
            $this->ficha->tipodepago=3;$fallotipago=false;
        }
        if($fallotipago && $this->ficha->pago4activo){
            $this->ficha->tipodepago=4;$fallotipago=false;
        }
        if($fallotipago && $this->ficha->pago5activo){
            $this->ficha->tipodepago=5;$fallotipago=false;
        }
        if($fallotipago && $this->ficha->pago6activo){
            $this->ficha->tipodepago=6;$fallotipago=false;
        }
        //
        
        if($this->ficha->permitircomentarios==0){
            $this->ficha->permitircomentarios=1;
        }
        $this->permitircomentarios=$this->ficha->permitircomentarios;
        if(!$this->ficha->binario){
            //$this->start=false;
        }
        if($this->ficha->user_id==6){
            //$this->start=false;
        }
        $this->pagado=$this->ficha->pagado;
        $this->pagadomanual=$this->ficha->pagadomanual;
        $this->seleccionconfirmada=$this->ficha->seleccionconfirmada;
        $cliente = Cliente::find($this->ficha->cliente_id);
        $this->emailcliente=$cliente->email;

        if($error==$this->stripeconfirmapago && $this->ficha->tipodepago==5){
            //stripestripestripestripestripestripestripestripestripestripestripestripestripe
            $this->error='';
            if(!$this->pagado){
                $this->error='pagado';
                $this->pagado=true;
                $this->seleccionconfirmada=true;
                Galeria::where('id',$idgal)->update(['pagado'=>true,'seleccionconfirmada'=>true,'fechapago'=>Carbon::now()]);
                $xuser = User::find($this->ficha->user_id);
                $xuser2 = User2::find($this->ficha->user_id);

                Avisos::insert([
                    'user_id'=>$this->ficha->user_id,
                    'galeria_id'=>$this->ficha->id,
                    'numerico'=>5,
                    'pendiente'=>true,
                    'notas'=>"Galeria '".$this->ficha->nombreinterno."' pagada",
                ]);
        
                $asunto="Confirmación de pago de sesión en ".$xuser2->nombre;
                $vista="email.galeriapagada";

                // email personalizado
                $texto="";
                $asu=$this->ficha->emailpagoasunto;
                $cue=$this->ficha->emailpagocuerpo;
                if(strlen($asu)>0 && strlen($cue)>0){
                    $vista="email.galeriapagadapersonalizado";
                    $asunto=$asu;
                    $texto=$cue;
                    //
                    $texto=str_replace("border-radius: 5px;","",$texto);
                    $texto=str_replace("text-align: center;","",$texto);
                    $texto=str_replace("padding: 4px 8px;","",$texto);
                    $texto=str_replace("color: white;","",$texto);
                    $texto=str_replace("background-color: orange;","",$texto);
                    //
                    $s='<span style="    ">nombreempresa</span>';
                    $texto=str_replace($s,$xuser2->nombre,$texto);
                    $s='<span style="    ">nombreempresa2</span>';
                    $texto=str_replace($s,$xuser2->nombre2,$texto);
                    $s='<span style="    ">domicilioempresa</span>';
                    $texto=str_replace($s,$xuser2->domicilio,$texto);
                    $s='<span style="    ">cpempresa</span>';
                    $texto=str_replace($s,$xuser2->codigopostal,$texto);
                    $s='<span style="    ">poblacionempresa</span>';
                    $texto=str_replace($s,$xuser2->poblacion,$texto);
                    $s='<span style="    ">provinciaempresa</span>';
                    $texto=str_replace($s,$xuser2->provincia,$texto);
                    $s='<span style="    ">telefonoempresa</span>';
                    $texto=str_replace($s,$xuser2->telefono,$texto);
                    $s='<span style="    ">emailempresa</span>';
                    $texto=str_replace($s,$xuser->email,$texto);
                    $s='<span style="    ">nombrecliente</span>';
                    $texto=str_replace($s,$cliente->nombre." ".$cliente->apellidos,$texto);
                    $s='<span style="    ">formapago</span>';
                    $texto=str_replace($s,"Stripe",$texto);
                    $s='<span style="    ">nombregaleria</span>';
                    $texto=str_replace($s,$this->ficha->nombre,$texto);
                    $s='<span style="    ">importepago</span>';
                    $texto=str_replace($s,$this->ficha->imppago."&euro;",$texto);
                    //
                    $s='<strong style="    ">nombreempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->nombre.'</strong>',$texto);
                    $s='<strong style="    ">nombreempresa2</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->nombre2.'</strong>',$texto);
                    $s='<strong style="    ">domicilioempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->domicilio.'</strong>',$texto);
                    $s='<strong style="    ">cpempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->codigopostal.'</strong>',$texto);
                    $s='<strong style="    ">poblacionempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->poblacion.'</strong>',$texto);
                    $s='<strong style="    ">provinciaempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->provincia.'</strong>',$texto);
                    $s='<strong style="    ">telefonoempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->telefono.'</strong>',$texto);
                    $s='<strong style="    ">emailempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser->email.'</strong>',$texto);
                    $s='<strong style="    ">nombrecliente</strong>';
                    $texto=str_replace($s,'<strong>'.$cliente->nombre." ".$cliente->apellidos.'</strong>',$texto);
                    $s='<strong style="    ">formapago</strong>';
                    $texto=str_replace($s,'<strong>'."Stripe".'</strong>',$texto);
                    $s='<strong style="    ">nombregaleria</strong>';
                    $texto=str_replace($s,'<strong>'.$this->ficha->nombre.'</strong>',$texto);
                    $s='<strong style="    ">importepago</strong>';
                    $texto=str_replace($s,'<strong>'.$this->ficha->imppago."&euro;".'</strong>',$texto);

                }
                //

                // cliente
                $datos=[
                    'ruta'=>'',
                    'logo'=>$xuser2->logo,
                    'empresa'=>$xuser2->nombre,
                    'nombregaleria'=>$this->ficha->nombre,
                    'nombrecliente'=>$cliente->nombre." ".$cliente->apellidos,
                    'pago'=>"Stripe",
                    'importe'=>$this->ficha->imppago,
                    'personalizado'=>$texto,
                ];
                $ok=Utils::sendmail($cliente->id,$this->ficha->user_id,$vista,$xuser2->nombre,"",$asunto,$datos);
                // empresa
                $datos['personalizado']=str_replace($this->ficha->nombre,$this->ficha->nombreinterno,$datos['personalizado']);
                $datos['nombregaleria']=$this->ficha->nombreinterno;
                $ok=Utils::sendmail(0,$this->ficha->user_id,$vista,$xuser2->nombre,$xuser->email,$asunto,$datos);
            }
        }

        if($error==$this->stripeconfirmapago && $this->ficha->tipodepago==3 && $error==$this->stripeconfirmapago){
            //redsysredsysredsysredsysredsysredsysredsysredsysredsys
            //Log: :info($error);
            //Log: :info($this->stripeconfirmapago);
            $this->error='';
            if(!$this->pagado){
                $x=Request::all();
                //log: :info($x);
                $this->error='pagado';
                $this->pagado=true;
                $this->seleccionconfirmada=true;
                Galeria::where('id',$idgal)->update(['pagado'=>true,'seleccionconfirmada'=>true,'fechapago'=>Carbon::now()]);
                $xuser = User::find($this->ficha->user_id);
                $xuser2 = User2::find($this->ficha->user_id);

                Avisos::insert([
                    'user_id'=>$this->ficha->user_id,
                    'galeria_id'=>$this->ficha->id,
                    'numerico'=>3,
                    'pendiente'=>true,
                    'notas'=>"Galeria '".$this->ficha->nombreinterno."' pagada",
                ]);

                $asunto="Confirmación de pago de sesión en ".$xuser2->nombre;
                $vista="email.galeriapagada";

                // email personalizado
                $texto="";
                $asu=$this->ficha->emailpagoasunto;
                $cue=$this->ficha->emailpagocuerpo;
                if(strlen($asu)>0 && strlen($cue)>0){
                    $vista="email.galeriapagadapersonalizado";
                    $asunto=$asu;
                    $texto=$cue;
                    //
                    $texto=str_replace("border-radius: 5px;","",$texto);
                    $texto=str_replace("text-align: center;","",$texto);
                    $texto=str_replace("padding: 4px 8px;","",$texto);
                    $texto=str_replace("color: white;","",$texto);
                    $texto=str_replace("background-color: orange;","",$texto);
                    //
                    $s='<span style="    ">nombreempresa</span>';
                    $texto=str_replace($s,$xuser2->nombre,$texto);
                    $s='<span style="    ">nombreempresa2</span>';
                    $texto=str_replace($s,$xuser2->nombre2,$texto);
                    $s='<span style="    ">domicilioempresa</span>';
                    $texto=str_replace($s,$xuser2->domicilio,$texto);
                    $s='<span style="    ">cpempresa</span>';
                    $texto=str_replace($s,$xuser2->codigopostal,$texto);
                    $s='<span style="    ">poblacionempresa</span>';
                    $texto=str_replace($s,$xuser2->poblacion,$texto);
                    $s='<span style="    ">provinciaempresa</span>';
                    $texto=str_replace($s,$xuser2->provincia,$texto);
                    $s='<span style="    ">telefonoempresa</span>';
                    $texto=str_replace($s,$xuser2->telefono,$texto);
                    $s='<span style="    ">emailempresa</span>';
                    $texto=str_replace($s,$xuser->email,$texto);
                    $s='<span style="    ">nombrecliente</span>';
                    $texto=str_replace($s,$cliente->nombre." ".$cliente->apellidos,$texto);
                    $s='<span style="    ">formapago</span>';
                    $texto=str_replace($s,"Tarjeta de crédito",$texto);
                    $s='<span style="    ">nombregaleria</span>';
                    $texto=str_replace($s,$this->ficha->nombre,$texto);
                    $s='<span style="    ">importepago</span>';
                    $texto=str_replace($s,$this->ficha->imppago."&euro;",$texto);
                    //
                    $s='<strong style="    ">nombreempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->nombre.'</strong>',$texto);
                    $s='<strong style="    ">nombreempresa2</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->nombre2.'</strong>',$texto);
                    $s='<strong style="    ">domicilioempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->domicilio.'</strong>',$texto);
                    $s='<strong style="    ">cpempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->codigopostal.'</strong>',$texto);
                    $s='<strong style="    ">poblacionempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->poblacion.'</strong>',$texto);
                    $s='<strong style="    ">provinciaempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->provincia.'</strong>',$texto);
                    $s='<strong style="    ">telefonoempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser2->telefono.'</strong>',$texto);
                    $s='<strong style="    ">emailempresa</strong>';
                    $texto=str_replace($s,'<strong>'.$xuser->email.'</strong>',$texto);
                    $s='<strong style="    ">nombrecliente</strong>';
                    $texto=str_replace($s,'<strong>'.$cliente->nombre." ".$cliente->apellidos.'</strong>',$texto);
                    $s='<strong style="    ">formapago</strong>';
                    $texto=str_replace($s,'<strong>'."Tarjeta de crédito".'</strong>',$texto);
                    $s='<strong style="    ">nombregaleria</strong>';
                    $texto=str_replace($s,'<strong>'.$this->ficha->nombre.'</strong>',$texto);
                    $s='<strong style="    ">importepago</strong>';
                    $texto=str_replace($s,'<strong>'.$this->ficha->imppago."&euro;".'</strong>',$texto);

                }
                //

                //cliente
                $datos=[
                    'ruta'=>'',
                    'logo'=>$xuser2->logo,
                    'empresa'=>$xuser2->nombre,
                    'nombregaleria'=>$this->ficha->nombre,
                    'nombrecliente'=>$cliente->nombre." ".$cliente->apellidos,
                    'pago'=>"Tarjeta de crédito",
                    'importe'=>$this->ficha->imppago,
                    'personalizado'=>$texto,
                ];
                $ok=Utils::sendmail($cliente->id,$this->ficha->user_id,$vista,$xuser2->nombre,"",$asunto,$datos);
                // empresa
                $datos['personalizado']=str_replace($this->ficha->nombre,$this->ficha->nombreinterno,$datos['personalizado']);
                $datos['nombregaleria']=$this->ficha->nombreinterno;
                $ok=Utils::sendmail(0,$this->ficha->user_id,$vista,$xuser2->nombre,$xuser->email,$asunto,$datos);
            }
        }

        if($error==$this->stripeconfirmapago && $this->ficha->tipodepago==4){
            //paypalpaypalpaypalpaypalpaypalpaypalpaypalpaypalpaypalpaypalpaypal
            $this->error='';
            if(!$this->pagado){
                $x=Request::all();
                $paymentId=$x['paymentId'];
                $PayerID=$x['PayerID'];
                $token=$x['token'];
                $gateway = Omnipay::create('PayPal_Rest');
                $gateway->setClientId($this->paypalclientid);
                $gateway->setSecret($this->paypalsecret);
                $gateway->setTestMode(!$this->paypalProduccion); //set it to 'false' when go live
                $paymentidreceived="";
                if ($paymentId && $PayerID) {
                    $transaction = $gateway->completePurchase(array(
                        'payer_id' => $PayerID,
                        'transactionReference' => $paymentId
                    ));            
                    $response = $transaction->send();
                    $arr = $response->getData();
                    //log: :info($arr);
                    if ($response->isSuccessful()) {
                        $paymentidreceived=$arr['id'];
                        $importepagado=$arr['transactions'][0]['amount']['total'];
                        //$this-> dispatch('mensaje',['type' => 'success',  'message' =>"Pago procesado correctamente." ,  'title' => 'ATENCIÓN']);
                        //$this-> dispatch('mensaje',['type' => 'success',  'message' =>"Payment of {$arr['transactions'][0]['amount']['total']} was accepted from {$arr['payer']['payer_info']['email']}. Transaction Id: {$arr['id']}." ,  'title' => 'ATENCIÓN']);
                    }
                    else{
                        //$this-> dispatch('mensaje',['type' => 'error',  'message' =>$response->getMessage(),  'title' => 'ATENCIÓN']);
                        //return $response->getMessage();
                    }
                }
                else{
                    //$this-> dispatch('mensaje',['type' => 'error',  'message' =>'El pago no se ha procesado, error indeterminado',  'title' => 'ATENCIÓN']);
                }

                if(strlen($paymentidreceived)==0){
                    $this->error='errorpago';
                    //Log: :info($response);
                }else{
                    $this->error='pagado';
                    $this->pagado=true;
                    $this->seleccionconfirmada=true;
                    Galeria::where('id',$idgal)->update([
                        'pagado'=>true,
                        'seleccionconfirmada'=>true,
                        'fechapago'=>Carbon::now(),
                        'idpago'=>$paymentidreceived,
                        'imppago'=>$importepagado
                    ]);
                    $xuser = User::find($this->ficha->user_id);
                    $xuser2 = User2::find($this->ficha->user_id);
    
                    Avisos::insert([
                        'user_id'=>$this->ficha->user_id,
                        'galeria_id'=>$this->ficha->id,
                        'numerico'=>4,
                        'pendiente'=>true,
                        'notas'=>"Galeria '".$this->ficha->nombreinterno."' pagada",
                    ]);
    
                    $asunto="Confirmación de pago de sesión en ".$xuser2->nombre;
                    $vista="email.galeriapagada";
    
                    // email personalizado
                    $texto="";
                    $asu=$this->ficha->emailpagoasunto;
                    $cue=$this->ficha->emailpagocuerpo;
                    if(strlen($asu)>0 && strlen($cue)>0){
                        $vista="email.galeriapagadapersonalizado";
                        $asunto=$asu;
                        $texto=$cue;
                        //
                        $texto=str_replace("border-radius: 5px;","",$texto);
                        $texto=str_replace("text-align: center;","",$texto);
                        $texto=str_replace("padding: 4px 8px;","",$texto);
                        $texto=str_replace("color: white;","",$texto);
                        $texto=str_replace("background-color: orange;","",$texto);
                        //
                        $s='<span style="    ">nombreempresa</span>';
                        $texto=str_replace($s,$xuser2->nombre,$texto);
                        $s='<span style="    ">nombreempresa2</span>';
                        $texto=str_replace($s,$xuser2->nombre2,$texto);
                        $s='<span style="    ">domicilioempresa</span>';
                        $texto=str_replace($s,$xuser2->domicilio,$texto);
                        $s='<span style="    ">cpempresa</span>';
                        $texto=str_replace($s,$xuser2->codigopostal,$texto);
                        $s='<span style="    ">poblacionempresa</span>';
                        $texto=str_replace($s,$xuser2->poblacion,$texto);
                        $s='<span style="    ">provinciaempresa</span>';
                        $texto=str_replace($s,$xuser2->provincia,$texto);
                        $s='<span style="    ">telefonoempresa</span>';
                        $texto=str_replace($s,$xuser2->telefono,$texto);
                        $s='<span style="    ">emailempresa</span>';
                        $texto=str_replace($s,$xuser->email,$texto);
                        $s='<span style="    ">nombrecliente</span>';
                        $texto=str_replace($s,$cliente->nombre." ".$cliente->apellidos,$texto);
                        $s='<span style="    ">formapago</span>';
                        $texto=str_replace($s,"Paypal",$texto);
                        $s='<span style="    ">nombregaleria</span>';
                        $texto=str_replace($s,$this->ficha->nombre,$texto);
                        $s='<span style="    ">importepago</span>';
                        $texto=str_replace($s,$importepagado."&euro;",$texto);
                        //
                        $s='<strong style="    ">nombreempresa</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser2->nombre.'</strong>',$texto);
                        $s='<strong style="    ">nombreempresa2</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser2->nombre2.'</strong>',$texto);
                        $s='<strong style="    ">domicilioempresa</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser2->domicilio.'</strong>',$texto);
                        $s='<strong style="    ">cpempresa</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser2->codigopostal.'</strong>',$texto);
                        $s='<strong style="    ">poblacionempresa</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser2->poblacion.'</strong>',$texto);
                        $s='<strong style="    ">provinciaempresa</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser2->provincia.'</strong>',$texto);
                        $s='<strong style="    ">telefonoempresa</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser2->telefono.'</strong>',$texto);
                        $s='<strong style="    ">emailempresa</strong>';
                        $texto=str_replace($s,'<strong>'.$xuser->email.'</strong>',$texto);
                        $s='<strong style="    ">nombrecliente</strong>';
                        $texto=str_replace($s,'<strong>'.$cliente->nombre." ".$cliente->apellidos.'</strong>',$texto);
                        $s='<strong style="    ">formapago</strong>';
                        $texto=str_replace($s,'<strong>'."Paypal".'</strong>',$texto);
                        $s='<strong style="    ">nombregaleria</strong>';
                        $texto=str_replace($s,'<strong>'.$this->ficha->nombre.'</strong>',$texto);
                        $s='<strong style="    ">importepago</strong>';
                        $texto=str_replace($s,'<strong>'.$importepagado."&euro;".'</strong>',$texto);

                    }
                    //

                    //cliente
                    $datos=[
                        'ruta'=>'',
                        'logo'=>$xuser2->logo,
                        'empresa'=>$xuser2->nombre,
                        'nombregaleria'=>$this->ficha->nombre,
                        'nombrecliente'=>$cliente->nombre." ".$cliente->apellidos,
                        'pago'=>"Paypal",
                        'importe'=>$importepagado,
                        'personalizado'=>$texto,
                    ];
                    $ok=Utils::sendmail($cliente->id,$this->ficha->user_id,$vista,$xuser2->nombre,"",$asunto,$datos);
                    // empresa
                    $datos['personalizado']=str_replace($this->ficha->nombre,$this->ficha->nombreinterno,$datos['personalizado']);
                    $datos['nombregaleria']=$this->ficha->nombreinterno;
                    $ok=Utils::sendmail(0,$this->ficha->user_id,$vista,$xuser2->nombre,$xuser->email,$asunto,$datos);
                }
            }
        }

        // pagado fechapago tipodepago 1 efectivo 2 transferencia 3 redsys 4 paypal 5 stripe 6 bizum manual

        $x = User2::select('logo','nombre','iban')->find($this->ficha->user_id);
        $this->logo=$x->logo;

        // fuera binarios cargan el modelo transfieren muchos datos
        $idsan=$this->ficha->user_id;
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $filegal="gallogo".$idsan."-".md5($idsan).".jpg";
        File::delete($storage_path.$filegal);
        if(strlen($this->logo)>0){
            $bindata=base64_decode($this->logo);
            File::put($storage_path.$filegal,$bindata);
            $this->logo="/storage/tmpgallery/".$filegal;
        }
        //

        $this->empresa=$x->nombre;
        $this->iban=$x->iban;

        $storage_path = storage_path('app/public/tmpgallery')."/";

        $this->galeria=[];
        if($this->idgaleria==1845){
            $x=Binarios::
                select('id','nombre','galeria_id','position','binario','anotaciones','selected')
                ->where('galeria_id',$this->idgaleria)
                ->orderby('position','asc')
                ->orderby('id','asc')
                ->get()
                ->toArray();
        }
        else{
            $x=Binarios::
                select('id','nombre','galeria_id','position','binario','anotaciones','selected')
                ->where('galeria_id',$this->idgaleria)
                ->orderby('position','asc')
                ->orderby('id','asc')
                ->get()
                ->toArray();

        }
        foreach($x as $key=>$y){
            $x[$key]['selected']=$x[$key]['selected']==1?true:false;
            //tmpgallery

            // falta securizar con md5
            $namesecure=$this->idgaleria."-".$x[$key]['id'].".jpg";
            $namesecure=$this->idgaleria."-".$x[$key]['id']."-".md5($x[$key]['id'].'gllery').".jpg";
            //Log: :info($namesecure);
            $filee=$storage_path.$namesecure;
            $bindata=base64_decode($x[$key]['binario']);
            $x[$key]['binario']="";
            $x[$key]['file']=$filee;
            $x[$key]['file']=$namesecure;
            File::put($filee,$bindata);
        }
        $this->galeria=Utils::objectToArray($x);

        if($this->ficha->maxfotos<$this->ficha->numfotos){
            $this->ficha->maxfotos=count($this->galeria);
            Galeria::where('id',$idgal)->update(['maxfotos'=>count($this->galeria)]);
        }

        if($this->ficha->maxfotos<count($this->galeria)&&$this->ficha->preciogaleriacompleta>0){
            $this->ficha->maxfotos=count($this->galeria);
            Galeria::where('id',$idgal)->update(['maxfotos'=>count($this->galeria)]);
        }
        $this->cargalistaproductos();
        $this->calcularprecio();
        if($this->pagado||$this->pagadomanual||$this->seleccionconfirmada){
            $this->alertpago();
            return;
        }
    }

    public function zoom($masmenos){
        // 1 -1
        //public $col=12; 12 6 4
        //public $colmd=4; 2 3 4 6 12
        $coll=$this->col;
        $collm=$this->colmd;
        if($masmenos==1){
            switch($coll){
                case 12:
                    break;
                case 6:
                    $coll=12;
                    break;
                case 4:
                    $coll=6;
                    break;
            }
            switch($collm){
                case 12:
                    break;
                case 6:
                    $collm=12;
                    break;
                case 4:
                    $collm=6;
                    break;
                case 3:
                    $collm=4;
                    break;
                case 2:
                    $collm=3;
                    break;
            }
        }
        if($masmenos==-1){
            switch($coll){
                case 12:
                    $coll=6;
                    break;
                case 6:
                    $coll=4;
                    break;
                case 4:
                    break;
            }
            switch($collm){
                case 12:
                    $collm=6;
                    break;
                case 6:
                    $collm=4;
                    break;
                case 4:
                    $collm=3;
                    break;
                case 3:
                    $collm=2;
                    break;
                case 2:
                    break;
            }
        }
        $this->col=$coll;
        $this->colmd=$collm;
    }
    public function mostrarsolo($cual){
        $this->seleccionamostrar=$cual; // 1 todas 2 seleccionadas 3 no seleccionadas
        //$this->dispatch('refreshFsLightbox',[]);
    }

    public function cargalistaproductos(){
        $this->productos=Productosgaleria::where('user_id',$this->ficha->user_id)->where('galeria_id',$this->idgaleria)->orderBy('position','asc')->orderBy('id','asc')->get()->toArray();
        $this->productos=Utils::objectToArray($this->productos);
        //Log: :info($this->productos);
        foreach($this->productos as $key=>$producto){
            $this->productos[$key]['anotaciones']=Utils::anotacionesproductotoexternalphoto($this->productos[$key]['anotaciones'],$producto['id'],$this->ficha->user_id);
            $this->productos[$key]['selopc1']=$producto['selopc1']==1?true:false;
            $this->productos[$key]['selopc2']=$producto['selopc2']==1?true:false;
            $this->productos[$key]['selopc3']=$producto['selopc3']==1?true:false;
            $this->productos[$key]['selopc4']=$producto['selopc4']==1?true:false;
            $this->productos[$key]['selopc5']=$producto['selopc5']==1?true:false;
            $this->productos[$key]['seleccionada']=$producto['seleccionada']==1?true:false;
            $this->productos[$key]['pre1obligatorio']=$producto['pre1obligatorio']==1?true:false;
            $this->productos[$key]['pre2obligatorio']=$producto['pre2obligatorio']==1?true:false;
            $this->productos[$key]['pre3obligatorio']=$producto['pre3obligatorio']==1?true:false;
            $this->productos[$key]['pre4obligatorio']=$producto['pre4obligatorio']==1?true:false;
            $this->productos[$key]['pre5obligatorio']=$producto['pre5obligatorio']==1?true:false;
            $this->productos[$key]['imagen']="/oh/img/gallery-generic.jpg";
            if(!$this->productos[$key]['seleccionada'])
                $this->productos[$key]['cantidad']=0;
            // fuera binarios cargan el modelo transfieren muchos datos
            $idsan=$this->productos[$key]['id'];
            $storage_path = storage_path('app/public/tmpgallery')."/";
            $filegal="galprod".$idsan."-".md5($idsan).".jpg";
            File::delete($storage_path.$filegal);
            if(strlen($this->productos[$key]['binario'])>0){
                $bindata=base64_decode($this->productos[$key]['binario']);
                File::put($storage_path.$filegal,$bindata);
                $this->productos[$key]['binario']="";
                $this->productos[$key]['imagen']="/storage/tmpgallery/".$filegal;
                if(!Session('soporteavif')){
                    Spatie::load($storage_path.$filegal)->save($storage_path.$filegal);
                }
            }
            // end fuera binarios
            if($this->modoprueba){
                //$this->productos[$key]['binario']="";
            }
        }
    }

    // producto sele imagenes
    public function cancelarseleccionfotoproducto(){
        $this->galeriabis=$this->galeria;
        $this->galeriabiscount=0;
        $this->productoseleccionid=-1;
    }

    public function cargarselecciondefotosproducto($key){
        $this->versolofotosproductos=1;
        $this->productoseleccionid=$key;
        $prod=$this->productos[$key];
        $selec=$prod['seleccionfotos'];
        $desdedonde=$prod['fotosdesde']; // 2 seleccionadas 3 no seleccionadas
        $seleccion=[];
        if(strlen($selec)>0){
            $seleccion=json_decode($selec,true);
        }
        //$this-> galeriabis=null;
        //Log: :info($this->galeria);
        $this->galeriabis=$this->galeria;
        $this->galeriabiscount=0;
        foreach($this->galeriabis as $key2=>$gal){
            $this->galeriabis[$key2]['selectedprod']=false;
            $this->galeriabis[$key2]['cantidad']=0; // la cantidad
            $this->galeriabis[$key2]['notas']="";
            if($desdedonde==2 && $this->galeriabis[$key2]['selected']==false){
                continue;
            }
            if($desdedonde==3 && $this->galeriabis[$key2]['selected']==true){
                continue;
            }
            //Log: :info($this->galeriabis[$key]['id']);
            if(count($seleccion)>0){
                $found_key = array_search($gal['id'], array_column($seleccion, 'id'));
                //Log: :info($found_key);
                if($found_key===false){
                    continue;
                }
                $this->galeriabiscount+=$seleccion[$found_key]['cantidad'];
                $this->galeriabis[$key2]['selectedprod']=true;
                $this->galeriabis[$key2]['cantidad']=$seleccion[$found_key]['cantidad']; // la cantidad
                $this->galeriabis[$key2]['notas']=$seleccion[$found_key]['notas']; // la cantidad
            }
        }
        $this->dispatch('focusonproductosel', ['key' => $key]);
        //Log: :info($this->galeriabis);
    }

    public function cantiimagenesproducto($key2,$masmenos){
        $actual=$this->galeriabis[$key2]['cantidad'];
        if($actual==0&&$masmenos==1)
            return;
        if($masmenos==1)
            $actual--;
        if($masmenos==2)
            $actual++;
        $this->galeriabis[$key2]['cantidad']=$actual;
        $this->recuentaimagenesproducto();
    }

    public function marcarimagenproducto($keyy){
        // marca/desmarca la imagen que corresponda
        if($this->ficha->pagado||$this->ficha->pagadomanual||$this->seleccionconfirmada){
            $this->dispatch('mensaje',['type' => 'info',  'message' => 'esta galería ya se ha pagado ó confirmado, no se harán cambios, solo puede verla',  'title' => 'ATENCIÓN']);
            return;
        }
        $this->galeriabis[$keyy]['selectedprod']=!$this->galeriabis[$keyy]['selectedprod'];
        $this->galeriabis[$keyy]['cantidad']=0;
        if($this->galeriabis[$keyy]['selectedprod']){
            $this->galeriabis[$keyy]['cantidad']=1;
        }
        $this->galeriabiscount=0;
        foreach($this->galeriabis as $key=>$gal){
            if($this->galeriabis[$key]['selectedprod']){
                $this->galeriabiscount+=$this->galeriabis[$key]['cantidad'];
            }
        }
        $this->recuentaimagenesproducto();
    }

    public function guardarseleccionfotoproducto($key){
        if($this->ficha->pagado||$this->ficha->pagadomanual||$this->seleccionconfirmada){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'No se puede modificar la selección, la galería está pagada.',  'title' => 'ATENCIÓN']);
            return;
        }
        $prodid=$this->productos[$key]['id'];

        if($this->galeriabiscount>0){
            if($this->galeriabiscount<$this->productos[$key]['numfotos']||$this->galeriabiscount>$this->productos[$key]['numfotos']+$this->productos[$key]['numfotosadicionales']){
                $mens='Es necesario seleccionar '.$this->productos[$key]['numfotos'].' fotografías';
                if($this->productos[$key]['numfotosadicionales']>0)
                    $mens='Es necesario seleccionar entre '.$this->productos[$key]['numfotos'].' y '.$this->productos[$key]['numfotos']+$this->productos[$key]['numfotosadicionales'].' fotografías';
                $this->dispatch('mensaje',['type' => 'error',  'message' => $mens,  'title' => 'ATENCIÓN']);
                return;
            }
        }

        //if($this->galeriabiscount!=$this->productos[$key]['numfotos']&&$this->galeriabiscount>0){
        //    $this->dispatch('mensaje',['type' => 'error',  'message' => 'Es necesario seleccionar '.$this->productos[$key]['numfotos'].' fotografías',  'title' => 'ATENCIÓN']);
        //    return;
        //}
        
        $seleccion=[];
        foreach($this->galeriabis as $key2=>$gal){
            if($gal['selectedprod']){
                $seleccion[]=[
                    'id'=>$gal['id'],
                    'cantidad'=>$gal['cantidad'],
                    'notas'=>$gal['notas'],
                ];
            }
        }
        //Log: :info($seleccion);
        $datos=json_encode($seleccion);
        $this->productos[$key]['seleccionfotos']=$datos;
        Productosgaleria::where('id',$prodid)->update([
            'seleccionfotos'=>$datos,
        ]);

        $preguntasok=true;
        if($this->productos[$key]['pre1obligatorio']&&strlen($this->productos[$key]['pregunta1'])>0&&strlen($this->productos[$key]['respuesta1'])==0){
            $preguntasok=false;
        }
        if($this->productos[$key]['pre2obligatorio']&&strlen($this->productos[$key]['pregunta2'])>0&&strlen($this->productos[$key]['respuesta2'])==0){
            $preguntasok=false;
        }
        if($this->productos[$key]['pre3obligatorio']&&strlen($this->productos[$key]['pregunta3'])>0&&strlen($this->productos[$key]['respuesta3'])==0){
            $preguntasok=false;
        }
        if($this->productos[$key]['pre4obligatorio']&&strlen($this->productos[$key]['pregunta4'])>0&&strlen($this->productos[$key]['respuesta4'])==0){
            $preguntasok=false;
        }
        if($this->productos[$key]['pre5obligatorio']&&strlen($this->productos[$key]['pregunta5'])>0&&strlen($this->productos[$key]['respuesta5'])==0){
            $preguntasok=false;
        }
        if(!$preguntasok){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Responda a las preguntas obligatorias antes de seleccionar el producto',  'title' => 'ATENCIÓN']);
        }

        if($this->galeriabiscount>=$this->productos[$key]['numfotos']&&$preguntasok){
            $this->productos[$key]['seleccionada']=true;

            if($this->productos[$key]['cantidad']==0)
                $this->productos[$key]['cantidad']=1;

            Productosgaleria::where('id',$prodid)->update([
                'seleccionada'=>true,
                'cantidad'=>$this->productos[$key]['cantidad']
            ]);
        }
        if($this->galeriabiscount==0){
            $this->productos[$key]['seleccionada']=false;
            $this->productos[$key]['cantidad']=0;
            Productosgaleria::where('id',$prodid)->update([
                'seleccionada'=>false,
                'cantidad'=>0,
            ]);
        }
        $this->calcularprecio();
        $this->cancelarseleccionfotoproducto();
        //$this->dispatch('focusonproducto', ['key' => $key]);
        $this->dispatch('closemodalclass', ['id' => 'seleccionfotos']);
    }

    public function recuentaimagenesproducto(){
        // marca/desmarca la imagen que corresponda
        $this->galeriabiscount=0;
        foreach($this->galeriabis as $key=>$gal){
            $canti=$this->galeriabis[$key]['cantidad'];
            if(strlen($canti)==0)
                $this->galeriabis[$key]['cantidad']=0;
            if($this->galeriabis[$key]['cantidad']>0){
                $this->galeriabis[$key]['selectedprod']=true;
            }
            if($this->galeriabis[$key]['cantidad']==0){
                $this->galeriabis[$key]['selectedprod']=false;
            }
            if($this->galeriabis[$key]['selectedprod']){
                $this->galeriabiscount+=$this->galeriabis[$key]['cantidad'];
            }
        }
    }

    public function mostrarsolofotosproductos($num){
        $this->versolofotosproductossolonotas=false;
        if($num==4){
            $this->versolofotosproductossolonotas=true;
            $num=2;
        }
        $this->versolofotosproductos=$num; // 1 todos 2 seleccionados 3 no seleccionados 4 con anotaciones
    }
    // fin interior producto sele imagenes

    public function marcarproducto($key){
        //if($this->ficha->pagado||$this->ficha->pagadomanual||$this->seleccionconfirmada){
        if($this->ficha->pagado||$this->ficha->pagadomanual||$this->seleccionconfirmada){
            $this->dispatch('mensaje',['type' => 'info',  'message' => 'esta galería ya se ha pagado ó confirmado, no se harán cambios, solo puede verla',  'title' => 'ATENCIÓN']);
            return;
        }
        $valor=!$this->productos[$key]['seleccionada'];
        if($valor && $this->productos[$key]['numfotos']>0){
            $this->cargarselecciondefotosproducto($key);
            if($this->galeriabiscount!=$this->productos[$key]['numfotos']){
                $this->dispatch('openmodalid', ['id' => 'seleccionfotos'.$key]);
                return;
            }
        }
        $preguntasok=true;
        if($valor){
            if($this->productos[$key]['pre1obligatorio']&&strlen($this->productos[$key]['pregunta1'])>0&&strlen($this->productos[$key]['respuesta1'])==0){
                $preguntasok=false;
            }
            if($this->productos[$key]['pre2obligatorio']&&strlen($this->productos[$key]['pregunta2'])>0&&strlen($this->productos[$key]['respuesta2'])==0){
                $preguntasok=false;
            }
            if($this->productos[$key]['pre3obligatorio']&&strlen($this->productos[$key]['pregunta3'])>0&&strlen($this->productos[$key]['respuesta3'])==0){
                $preguntasok=false;
            }
            if($this->productos[$key]['pre4obligatorio']&&strlen($this->productos[$key]['pregunta4'])>0&&strlen($this->productos[$key]['respuesta4'])==0){
                $preguntasok=false;
            }
            if($this->productos[$key]['pre5obligatorio']&&strlen($this->productos[$key]['pregunta5'])>0&&strlen($this->productos[$key]['respuesta5'])==0){
                $preguntasok=false;
            }
            if(!$preguntasok){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'Responda a las preguntas obligatorias antes de seleccionar el producto',  'title' => 'ATENCIÓN']);
                return;
            }
        }

        $this->productos[$key]['seleccionada']=$valor;
        if($valor&&$this->productos[$key]['cantidad']==0)
            $this->productos[$key]['cantidad']=1;
        if(!$valor)
            $this->productos[$key]['cantidad']=0;
        Productosgaleria::where('id',$this->productos[$key]['id'])->update([
            'seleccionada'=>$valor,
            'cantidad'=>$this->productos[$key]['cantidad'],
        ]);
        $this->calcularprecio();
        //$this->cargali staproductos();
    }

    public function clonarproducto($key){
        if($this->ficha->pagado||$this->ficha->pagadomanual||$this->seleccionconfirmada){
            $this->dispatch('mensaje',['type' => 'info',  'message' => 'esta galería ya se ha pagado ó confirmado, no se harán cambios, solo puede verla',  'title' => 'ATENCIÓN']);
            return;
        }
        $prodid=$this->productos[$key];
        $iddupli=$prodid['id'];

        // lo tengo q cargar de nuevo por que no tengo el binario
        $prodid=Productosgaleria::where('id',$iddupli)->get()->toArray()[0];
        //

        $prodid['cantidad']=1;
        $prodid['seleccionfotos']="";
        $prodid['seleccionada']=false;
        unset($prodid['id']);
        unset($prodid['imagen']);
        unset($prodid['created_at']);
        unset($prodid['updated_at']);
        DB::connection()->getPdo()->exec("lock tables productosgaleria write");
        $ids = Productosgaleria::select('id')
            ->where('user_id',$this->ficha->user_id)
            ->where('galeria_id',$this->idgaleria)
            ->where('id','>',$iddupli)
            ->orderBy('id','asc')->get()->toArray();
        $lastid=Productosgaleria::insertGetId($prodid);
        foreach($ids as $ss){
            $lastid++;
            Productosgaleria::where('id',$ss['id'])->update(['id'=>$lastid]);
        }
        DB::connection()->getPdo()->exec("unlock tables");
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Ya puede configurar el nuevo producto.',  'title' => 'ATENCIÓN']);
        $this->cargalistaproductos();
    }

    public function marcarproductocantidad($key,$masmenos){
        if($this->ficha->pagado||$this->ficha->pagadomanual||$this->seleccionconfirmada){
            $this->dispatch('mensaje',['type' => 'info',  'message' => 'esta galería ya se ha pagado ó confirmado, no se harán cambios, solo puede verla',  'title' => 'ATENCIÓN']);
            return;
        }
        $actual=$this->productos[$key]['cantidad'];
        if($actual==0&&$masmenos==1)
            return;


        if($actual==0&&$this->productos[$key]['numfotos']>0){
            $this->cargarselecciondefotosproducto($key);
            if($this->galeriabiscount<$this->productos[$key]['numfotos']){
                $this->dispatch('openmodalid', ['id' => 'seleccionfotos'.$key]);
                return;
            }
        }
       


        if($masmenos==1)
            $actual--;
        if($masmenos==2)
            $actual++;
        $this->productos[$key]['cantidad']=$actual;
        $this->productos[$key]['seleccionada']=$actual==0?false:true;
        Productosgaleria::where('id',$this->productos[$key]['id'])->update([
            'cantidad'=>$actual,
            'seleccionada'=>$this->productos[$key]['seleccionada'],
        ]);
        $this->calcularprecio();
        //$this->cargali staproductos();
    }

    public function grabarespuesta($key,$opcion){
        $fld="respuesta".$opcion;
        //Log: :info($this->productos[$key]);
        //Log: :info($fld);
        //return;
        $idprod=$this->productos[$key]['id'];
        $valor=$this->productos[$key][$fld];
        Productosgaleria::where('id',$idprod)->update([
            $fld=>$valor
        ]);
    }

    public function marcaropcionadicional($key,$opcion){
        if($this->ficha->pagado||$this->ficha->pagadomanual){
            return;
        }
        $fld="selopc".$opcion;
        $idprod=$this->productos[$key]['id'];
        $valor=$this->productos[$key][$fld];
        Productosgaleria::where('id',$idprod)->update([
            $fld=>$valor
        ]);
        $this->calcularprecio();
    }

    public function confirmarseleccion()
    {
        if(!$this->procesable){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'no ha seleccionado el mínimo de fotografías',  'title' => 'ATENCIÓN']);
            return;
        }
        foreach($this->productos as $prod){
            if($prod['incluido']&&!$prod['seleccionada']){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'seleccione al menos los complementos incluidos antes de continuar',  'title' => 'ATENCIÓN']);
                return;
            }
        }
        Avisos::insert([
            'user_id'=>$this->ficha->user_id,
            'galeria_id'=>$this->ficha->id,
            'numerico'=>10,
            'pendiente'=>true,
            'notas'=>"Selección de galeria '".$this->ficha->nombreinterno."' confirmada",
        ]);

        // email
        $cliente = Cliente::find($this->ficha->cliente_id);
        $xuser = User::find($this->ficha->user_id);
        $xuser2 = User2::find($this->ficha->user_id);

        $asunto="Confirmación de selección de fotografías en ".$xuser2->nombre;
        $vista="email.galeriaconfirmada";

        // email personalizado
        $texto="";
        $asu=$this->ficha->emailconfirmaasunto;
        $cue=$this->ficha->emailconfirmacuerpo;
        if(strlen($asu)>0 && strlen($cue)>0){
            $vista="email.galeriaconfirmadapersonalizado";
            $asunto=$asu;
            $texto=$cue;
            //
            $texto=str_replace("border-radius: 5px;","",$texto);
            $texto=str_replace("text-align: center;","",$texto);
            $texto=str_replace("padding: 4px 8px;","",$texto);
            $texto=str_replace("color: white;","",$texto);
            $texto=str_replace("background-color: orange;","",$texto);
            //
            $s='<span style="    ">nombreempresa</span>';
            $texto=str_replace($s,$xuser2->nombre,$texto);
            $s='<span style="    ">nombreempresa2</span>';
            $texto=str_replace($s,$xuser2->nombre2,$texto);
            $s='<span style="    ">domicilioempresa</span>';
            $texto=str_replace($s,$xuser2->domicilio,$texto);
            $s='<span style="    ">cpempresa</span>';
            $texto=str_replace($s,$xuser2->codigopostal,$texto);
            $s='<span style="    ">poblacionempresa</span>';
            $texto=str_replace($s,$xuser2->poblacion,$texto);
            $s='<span style="    ">provinciaempresa</span>';
            $texto=str_replace($s,$xuser2->provincia,$texto);
            $s='<span style="    ">telefonoempresa</span>';
            $texto=str_replace($s,$xuser2->telefono,$texto);
            $s='<span style="    ">emailempresa</span>';
            $texto=str_replace($s,$xuser->email,$texto);
            $s='<span style="    ">nombrecliente</span>';
            $texto=str_replace($s,$cliente->nombre." ".$cliente->apellidos,$texto);
            $s='<span style="    ">nombregaleria</span>';
            $texto=str_replace($s,$this->ficha->nombre,$texto);
            $s='<span style="    ">importepago</span>';
            $texto=str_replace($s,$this->precio."&euro;",$texto);
            //
            $s='<strong style="    ">nombreempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->nombre.'</strong>',$texto);
            $s='<strong style="    ">nombreempresa2</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->nombre2.'</strong>',$texto);
            $s='<strong style="    ">domicilioempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->domicilio.'</strong>',$texto);
            $s='<strong style="    ">cpempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->codigopostal.'</strong>',$texto);
            $s='<strong style="    ">poblacionempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->poblacion.'</strong>',$texto);
            $s='<strong style="    ">provinciaempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->provincia.'</strong>',$texto);
            $s='<strong style="    ">telefonoempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->telefono.'</strong>',$texto);
            $s='<strong style="    ">emailempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser->email.'</strong>',$texto);
            $s='<strong style="    ">nombrecliente</strong>';
            $texto=str_replace($s,'<strong>'.$cliente->nombre." ".$cliente->apellidos.'</strong>',$texto);
            $s='<strong style="    ">nombregaleria</strong>';
            $texto=str_replace($s,'<strong>'.$this->ficha->nombre.'</strong>',$texto);
            $s='<strong style="    ">importepago</strong>';
            $texto=str_replace($s,'<strong>'.$this->precio."&euro;".'</strong>',$texto);

        }
        //

        //cliente
        $datos=[
            'ruta'=>'',
            'logo'=>$xuser2->logo,
            'empresa'=>$xuser2->nombre,
            'nombregaleria'=>$this->ficha->nombre,
            'nombrecliente'=>$cliente->nombre." ".$cliente->apellidos,
            'pago'=>"",
            'importe'=>$this->precio,
            'personalizado'=>$texto,
        ];
        $ok=Utils::sendmail($cliente->id,$this->ficha->user_id,$vista,$xuser2->nombre,"",$asunto,$datos);
        // empresa
        $datos['personalizado']=str_replace($this->ficha->nombre,$this->ficha->nombreinterno,$datos['personalizado']);
        $datos['nombregaleria']=$this->ficha->nombreinterno;
        $ok=Utils::sendmail(0,$this->ficha->user_id,$vista,$xuser2->nombre,$xuser->email,$asunto,$datos);
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Se ha confirmado la selección de fotografías de la galería',  'title' => 'ATENCIÓN']);
        // fin email

        $this->seleccionconfirmada=true;
        $this->ficha->fechafirma=Carbon::now();
        $this->ficha->seleccionconfirmada=true;
        if($this->ficha->user_id==6){
            //$this->seleccionconfirmada=false;
            //$this->ficha->seleccionconfirmada=false;
        }
        $this->ficha->update();
    }

    public function confirmarseleccionypago0()
    {

        $this->confirmarseleccion();
        $this->pagado=true;
        $this->ficha->pagado=true;
        $this->pagadomanual=true;
        $this->ficha->pagadomanual=true;
        $this->ficha->update();

        return;
        foreach($this->productos as $prod){
            if($prod['incluido']&&!$prod['seleccionada']){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'seleccione al menos los complementos incluidos antes de continuar',  'title' => 'ATENCIÓN']);
                return;
            }
        }
        if(!$this->procesable){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'no ha seleccionado el mínimo de fotografías',  'title' => 'ATENCIÓN']);
            return;
        }
        $this->seleccionconfirmada=true;
        $this->ficha->seleccionconfirmada=true;
        $this->pagado=true;
        $this->ficha->pagado=true;
        $this->pagadomanual=true;
        $this->ficha->pagadomanual=true;
        $this->ficha->tipodepago=100;
        $this->ficha->fechapago=Carbon::now();
        $this->ficha->update();
        $this->finalizarpagomanual();
    }

    public function vseccion($num)
    {
        $this->seccion=$num;
        $this->dispatch('$refresh');
    }

    public function vergaleria()
    {
        if($this->ficha->clavecliente==$this->claveacceso){
            $this->start=false;
            //$this->dispatch('refreshFsLightbox',[]);
            return;
        }
        $this->dispatch('mensaje',['type' => 'error',  'message' => 'clave de acceso incorrecta',  'title' => 'ATENCIÓN']);
    }

    public function marcarimagen($keyy){
        // marca/desmarca la imagen que corresponda
        if($this->pagado||$this->pagadomanual||$this->seleccionconfirmada){
            $this->alertpago();
            return;
        }
        
        $idd=$this->galeria[$keyy]['id'];
        //$this->galeria[$keyy]['selected']=!$this->galeria[$keyy]['selected'];
        $vall=$this->galeria[$keyy]['selected'];
        Binarios::where('id',$idd)->update(['selected'=>$vall]);
        $this->calcularprecio();
    }

    public function seleccionartodo($todo=true){
        // marca todo
        $this->cancelarseleccionfotoproducto();
        if($this->pagado||$this->pagadomanual||$this->seleccionconfirmada){
            $this->alertpago();
            return;
        }
        foreach($this->galeria as $key=>$gal){
            $idd=$this->galeria[$key]['id'];
            $this->galeria[$key]['selected']=$todo;
            $vall=$todo;
            Binarios::where('id',$idd)->update(['selected'=>$vall]);
        }
        $this->calcularprecio();
    }

    public function marcarimagennocheck($keyy){
        // marca/desmarca la imagen que corresponda
        $this->cancelarseleccionfotoproducto();
        if($this->pagado||$this->pagadomanual||$this->seleccionconfirmada){
            $this->alertpago();
            return;
        }
        $idd=$this->galeria[$keyy]['id'];
        $this->galeria[$keyy]['selected']=!$this->galeria[$keyy]['selected'];
        $vall=$this->galeria[$keyy]['selected'];



        // no marcar/desmarcar si hay algo en los productos marcado/desmarcado que pueda interferir
        //Log: :info($this->productos);
        //'nombre' => 'seleccion 3 de las no seleccionadas',
        //'fotosdesde' => 3, // 2 seleccionadas 3 no seleccionadas
        //'seleccionfotos' => '[]',
        //'seleccionfotos' => '[{"id":89260,"cantidad":1,"notas":""},{"id":89261,"cantidad":1,"notas":""},{"id":89262,"cantidad":1,"notas":""}]',
        //'seleccionada' => false,
        foreach($this->productos as $key=>$prod){
            $seleccionada=$prod['seleccionada'];
            $fotosdesde=$prod['fotosdesde'];
            if(!$seleccionada||$fotosdesde==1){
                continue;
            }
            $nombre=$prod['nombre'];
            $seleccionfotos=$prod['seleccionfotos'];
            $seleccion=[];
            if(strlen($seleccionfotos)>0){
                $seleccion=json_decode($seleccionfotos,true);
            }
            $found_key = array_search($idd, array_column($seleccion, 'id'));
            if($found_key===false){
                continue;
            }
            if($vall && $fotosdesde==3){
                // queremos seleccionar una foto que está en un producto donde seleccionamos de las no seleccionada
                $mens="está intentando marcar una fotografía que está seleccionada en el producto '".$nombre."' donde tiene que elegir fotografías de las no seleccionadas, desmarque en el producto y reintente";
                $this->dispatch('mensajelargo',['type' => 'info',  'message' => $mens,  'title' => 'ATENCIÓN']);
                $this->galeria[$keyy]['selected']=!$vall;
                return;
            }
            if(!$vall && $fotosdesde==2){
                // queremos desseleccionar una foto que está en un producto donde seleccionamos de las seleccionadas
                $mens="está intentando desmarcar una fotografía que está seleccionada en el producto '".$nombre."' donde tiene que elegir fotografías de las seleccionadas, desmarque en el producto y reintente";
                $this->dispatch('mensajelargo',['type' => 'info',  'message' => $mens,  'title' => 'ATENCIÓN']);
                $this->galeria[$keyy]['selected']=!$vall;
                return;
            }
        }
        //
    




        
        Binarios::where('id',$idd)->update(['selected'=>$vall]);
        $this->calcularprecio();
    }

    public function notas($keyy){
        $idd=$this->galeria[$keyy]['id'];
        $vall=$this->galeria[$keyy]['anotaciones'];
        //Log: :info($vall);
        Binarios::where('id',$idd)->update(['anotaciones'=>$vall]);
    }

    public function fpago(){
        // cambia la forma de pago
        if($this->pagado||$this->pagadomanual){
            $this->alertpago();
            return;
        }
        $this->ficha->update();
        $this->calcularprecio();
    }

    public function adicional($selopc){
        // marca/desmarca la opcion adicional
        if($this->pagado||$this->pagadomanual){
            $this->alertpago();
            return;
        }

        Galeria::where('id',$this->idgaleria)->update([$selopc=>$this->ficha->$selopc]);

        $this->calcularprecio();
    }

    public function alertpago(){
        $this->dispatch('mensaje',['type' => 'info',  'message' => 'esta galería ya se ha pagado ó confirmado, no se harán cambios, solo puede verla',  'title' => 'ATENCIÓN']);
        //$this->dispatch('alerta',['type' => 'success',  'message' => 'esta galería no se puede modificar',  'title' => 'ATENCIÓN']);
    }

    public function calcularprecio(){
        $result=Utils::calcularpreciogaleria($this->idgaleria,$this->ficha,$this->galeria,$this->formaspago,$this->productos);
        $this->precio=$result['precio'];
        $this->procesable=$result['procesable'];
        $this->seleccionadas=$result['seleccionadas'];
        $this->desgloseado=$result['desgloseado'];
        if($this->ficha->pagado||$this->ficha->pagadomanual){
            $this->procesable=false;
        }
        // paypal
        if($this->haypaypal && 1==2){
        }
        //
        // redsys
        if($this->hayredsys){
            $merchantorder = substr(rand(1001, 9999) . strtoupper(MD5(date("d") . date("m") . date("Y") . trim($this->idgaleria))), 0, 12);
            $rsysproduccion=true;
            $bizum=false;
            if($this->ficha->user_id==6){
                $rsysproduccion=false;
                $bizum=false;
            }
            $this->redsys=[
                'tpvredsys'=>true, // tpv redsys
                'tpvredsysProduccion'=>$rsysproduccion, // tpv redsys
                'tpvredsysMoneda'=>"978", // tpv redsys
                'tpvredsysMerchantOrder'=>$merchantorder, // tpv redsys
                'tpvredsysMerchantCode'=>$this->redsyscodcomercio, // tpv redsys
                'tpvredsysMerchantSignature'=>"", // tpv redsys
                'tpvredsysSignature256'=>"", // tpv redsys
                'tpvredsysMerchantParameters256'=>"", // tpv redsys
                'tpvredsysMerchantTerminal'=>$this->redsysterminal, // tpv redsys
                'tpvredsysMerchantClaveComercio'=>$this->redsysclacomercio, // tpv redsys
                'tpvredsysBizum'=>$bizum, // tpv redsys
                'rutaredsys'=> $rsysproduccion?'https://sis.redsys.es/sis/realizarPago':'https://sis-t.redsys.es:25443/sis/realizarPago' // tpv redsys
            ];
            //$urlretorno=route('galeriacliente',[$this->idgaleria,$this->md5,$this->stripeconfirmapago]);
            $urlretorno=route('galeriacliente',[$this->idgaleria,$this->md5]);
            $urlok=route('galeriacliente',[$this->idgaleria,$this->md5,$this->stripeconfirmapago]);
            $urlko=route('galeriacliente',[$this->idgaleria,$this->md5,'errorpago']);
            //Log: :info($urlok);
            //Log: :info($urlko);
    
            $this->redsys['tpvredsysMerchantSignature'] = sha1(Utils::numFormat($this->precio * 100, 0) . // Ds_Merchant_Amount
                $this->redsys['tpvredsysMerchantOrder'] . // Ds_Merchant_Order
                $this->redsys['tpvredsysMerchantCode'] . // Ds_Merchant_MerchantCode
                $this->redsys['tpvredsysMoneda'] . // DS_Merchant_Currency
                "0" . // Ds_Merchant_TransactionType
                $urlretorno . // Ds_Merchant_MerchantURL
                $this->redsys['tpvredsysMerchantClaveComercio'] . //
                "");
                $this->redsys['tpvredsysMerchantParameters256'] = "";
                $this->redsys['tpvredsysSignature256'] = "";
                $rsys = new \App\RedsysAPIphp7();
                // Se Rellenan los campos
                $rsys->setParameter("DS_MERCHANT_PRODUCTDESCRIPTION",'Pago galería en '.$this->empresa);
                $rsys->setParameter("DS_MERCHANT_AMOUNT", Utils::numFormat($this->precio * 100, 0));
                $rsys->setParameter("DS_MERCHANT_ORDER", $merchantorder);
                $rsys->setParameter("DS_MERCHANT_MERCHANTCODE", $this->redsyscodcomercio);
                $rsys->setParameter("DS_MERCHANT_CURRENCY", "978");
                $rsys->setParameter("DS_MERCHANT_TRANSACTIONTYPE", "0");
                if ($this->redsys['tpvredsysBizum']) {
                    $rsys->setParameter("DS_MERCHANT_PAYMETHODS", "z"); // bizum
                }
                $rsys->setParameter("DS_MERCHANT_TERMINAL", $this->redsysterminal);
                $rsys->setParameter("DS_MERCHANT_MERCHANTURL", $urlretorno);
                $rsys->setParameter("DS_MERCHANT_URLOK", $urlok);
                $rsys->setParameter("DS_MERCHANT_URLKO", $urlko);
                $rsys->setParameter("DS_MERCHANT_MERCHANTDATA", "$this->idgaleria");
                $this->redsys['tpvredsysMerchantParameters256'] = $rsys->createMerchantParameters();
                $this->redsys['tpvredsysSignature256'] = $rsys->createMerchantSignature($this->redsys['tpvredsysMerchantClaveComercio']);
                $rsys = null;
            //Log: :info($this->redsys);
        }
        //
    }

    public function finalizarpagomanual(){
        foreach($this->productos as $prod){
            if($prod['incluido']&&!$prod['seleccionada']){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'seleccione al menos los complementos incluidos antes de continuar',  'title' => 'ATENCIÓN']);
                return;
            }
        }
        $current_date_time = Carbon::now()->toDateTimeString(); // Produces something like "2019-03-11 12:25:00"
        $this->ficha->fechapago=$current_date_time;
        $this->pagadomanual=true;
        $this->pagado=false;
        $this->seleccionconfirmada=true;
        $this->ficha->pagadomanual=true;
        $this->ficha->pagado=false;
        $this->ficha->seleccionconfirmada=true;
        $this->ficha->update();
        $cliente = Cliente::find($this->ficha->cliente_id);

        Galeria::where('id',$this->idgaleria)->update([
            'pagado'=>false,
            'pagadomanual'=>true,
            'seleccionconfirmada'=>true,
            'tipodepago'=>$this->ficha->tipodepago,
            'fechapago'=>Carbon::now(),
            'imppago'=>$this->precio
        ]);
        $xuser = User::find($this->ficha->user_id);
        $xuser2 = User2::find($this->ficha->user_id);

        switch($this->ficha->tipodepago){
            case 1:
                $tipp="Efectivo (al tratarse de un pago no automatizado, queda pendiente de confirmación por nuestra parte)";
                break;
            case 2:
                $tipp="Transferencia bancaria (al tratarse de un pago no automatizado, queda pendiente de confirmación por nuestra parte)";
                break;
            case 6:
                $tipp="Bizum (al tratarse de un pago no automatizado, queda pendiente de confirmación por nuestra parte)";
                break;
            case 100:
                $tipp="Sin coste";
                $this->ficha->tipodepago=1;
                $this->ficha->pagado=true;
                $this->pagado=true;
                $this->ficha->update();
                break;
        }

        Avisos::insert([
            'user_id'=>$this->ficha->user_id,
            'galeria_id'=>$this->ficha->id,
            'numerico'=>$this->ficha->tipodepago,
            'pendiente'=>true,
            'notas'=>"Galeria '".$this->ficha->nombreinterno."' pagada",
        ]);

        $asunto="Confirmación de pago de sesión en ".$xuser2->nombre;
        $vista="email.galeriapagada";

        // email personalizado
        $texto="";
        $asu=$this->ficha->emailpagoasunto;
        $cue=$this->ficha->emailpagocuerpo;
        if(strlen($asu)>0 && strlen($cue)>0){
            $vista="email.galeriapagadapersonalizado";
            $asunto=$asu;
            $texto=$cue;
            //
            $texto=str_replace("border-radius: 5px;","",$texto);
            $texto=str_replace("text-align: center;","",$texto);
            $texto=str_replace("padding: 4px 8px;","",$texto);
            $texto=str_replace("color: white;","",$texto);
            $texto=str_replace("background-color: orange;","",$texto);
            //
            $s='<span style="    ">nombreempresa</span>';
            $texto=str_replace($s,$xuser2->nombre,$texto);
            $s='<span style="    ">nombreempresa2</span>';
            $texto=str_replace($s,$xuser2->nombre2,$texto);
            $s='<span style="    ">domicilioempresa</span>';
            $texto=str_replace($s,$xuser2->domicilio,$texto);
            $s='<span style="    ">cpempresa</span>';
            $texto=str_replace($s,$xuser2->codigopostal,$texto);
            $s='<span style="    ">poblacionempresa</span>';
            $texto=str_replace($s,$xuser2->poblacion,$texto);
            $s='<span style="    ">provinciaempresa</span>';
            $texto=str_replace($s,$xuser2->provincia,$texto);
            $s='<span style="    ">telefonoempresa</span>';
            $texto=str_replace($s,$xuser2->telefono,$texto);
            $s='<span style="    ">emailempresa</span>';
            $texto=str_replace($s,$xuser->email,$texto);
            $s='<span style="    ">nombrecliente</span>';
            $texto=str_replace($s,$cliente->nombre." ".$cliente->apellidos,$texto);
            $s='<span style="    ">formapago</span>';
            $texto=str_replace($s,$tipp,$texto);
            $s='<span style="    ">nombregaleria</span>';
            $texto=str_replace($s,$this->ficha->nombre,$texto);
            $s='<span style="    ">importepago</span>';
            $texto=str_replace($s,$this->precio."&euro;",$texto);
            //
            $s='<strong style="    ">nombreempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->nombre.'</strong>',$texto);
            $s='<strong style="    ">nombreempresa2</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->nombre2.'</strong>',$texto);
            $s='<strong style="    ">domicilioempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->domicilio.'</strong>',$texto);
            $s='<strong style="    ">cpempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->codigopostal.'</strong>',$texto);
            $s='<strong style="    ">poblacionempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->poblacion.'</strong>',$texto);
            $s='<strong style="    ">provinciaempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->provincia.'</strong>',$texto);
            $s='<strong style="    ">telefonoempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser2->telefono.'</strong>',$texto);
            $s='<strong style="    ">emailempresa</strong>';
            $texto=str_replace($s,'<strong>'.$xuser->email.'</strong>',$texto);
            $s='<strong style="    ">nombrecliente</strong>';
            $texto=str_replace($s,'<strong>'.$cliente->nombre." ".$cliente->apellidos.'</strong>',$texto);
            $s='<strong style="    ">formapago</strong>';
            $texto=str_replace($s,'<strong>'.$tipp.'</strong>',$texto);
            $s='<strong style="    ">nombregaleria</strong>';
            $texto=str_replace($s,'<strong>'.$this->ficha->nombre.'</strong>',$texto);
            $s='<strong style="    ">importepago</strong>';
            $texto=str_replace($s,'<strong>'.$this->precio."&euro;".'</strong>',$texto);

        }
        //

        //cliente
        $datos=[
            'ruta'=>'',
            'logo'=>$xuser2->logo,
            'empresa'=>$xuser2->nombre,
            'nombregaleria'=>$this->ficha->nombre,
            'nombrecliente'=>$cliente->nombre." ".$cliente->apellidos,
            'pago'=>$tipp,
            'importe'=>$this->precio,
            'personalizado'=>$texto,
        ];
        $ok=Utils::sendmail($cliente->id,$this->ficha->user_id,$vista,$xuser2->nombre,"",$asunto,$datos);
        // empresa
        $datos['personalizado']=str_replace($this->ficha->nombre,$this->ficha->nombreinterno,$datos['personalizado']);
        $datos['nombregaleria']=$this->ficha->nombreinterno;
        $ok=Utils::sendmail(0,$this->ficha->user_id,$vista,$xuser2->nombre,$xuser->email,$asunto,$datos);
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Se ha confirmado el pago de la galería',  'title' => 'ATENCIÓN']);
        //$this->dispatch('alerta',['type' => 'success',  'message' => 'Se ha confirmado el pago de la galería',  'title' => 'ATENCIÓN']);
    }

    public function configurapaypal(){
        $this->paypalruta = $this->paypalProduccion?'https://api.paypal.com':'https://api.sandbox.paypal.com';
        $this->rutappal=route('galeriacliente',[$this->idgaleria,$this->md5,$this->stripeconfirmapago]);
        $this->rutappal2=route('galeriacliente',[$this->idgaleria,$this->md5,'errorpago']);
        return;
        //Log: :info($this->rutappal);
        //Log: :info($this->rutappal2);
        $this->configppal=[
            'mode'    => $this->paypalProduccion?'live':'sandbox',
            'sandbox' => [
                'client_id'         => $this->paypalclientid,
                'client_secret'     => $this->paypalsecret,
                'app_id'            => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id'         => $this->paypalclientid,
                'client_secret'     => $this->paypalsecret,
                'app_id'            => '',
            ],
            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => 'EUR',
            'notify_url'     => $this->rutappal, // Change this accordingly for your application.
            'locale'         => 'es_ES', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            //'validate_ssl'   => true, // Validate SSL when creating api client.
            'validate_ssl'   => $this->paypalProduccion?true:false, // Validate SSL when creating api client.
        ];
        //log: :info($this->configppal);
    }

    public function finalizarpagopaypal(){
        foreach($this->productos as $prod){
            if($prod['incluido']&&!$prod['seleccionada']){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'seleccione al menos los complementos incluidos antes de continuar',  'title' => 'ATENCIÓN']);
                return;
            }
        }
        //omnipay
        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId($this->paypalclientid);
        $gateway->setSecret($this->paypalsecret);
        $gateway->setTestMode(!$this->paypalProduccion); //set it to 'false' when go live
        $response = $gateway->purchase(array(
            'amount' => $this->precio,
            'currency' => "EUR",
            'returnUrl' => $this->rutappal,
            'cancelUrl' => $this->rutappal2,
        ))->send();
        //log: :info(print_r($response,true));
        //log: :info($response->isSuccessful());
        //log: :info($response->isRedirect());
        //log: :info($response->getMessage());
        // Process response
        if ($response->isRedirect()) {
            // Redirect to PayPal
            //log: :info($response->getRedirectUrl());
            return redirect()->away($response->getRedirectUrl());
            return $response->redirect();
        } else {
            // Payment failed
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Se ha producido un error en el intento de pago: '.$response->getMessage(),  'title' => 'ATENCIÓN']);
        }
    }

    public function grabaimportepago(){
        // graba el importe cuando pulsas pagar con redsys
        foreach($this->productos as $prod){
            if($prod['incluido']&&!$prod['seleccionada']){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'seleccione al menos los complementos incluidos antes de continuar',  'title' => 'ATENCIÓN']);
                return;
            }
        }
        Galeria::where('id',$this->idgaleria)->update(['imppago'=>$this->precio]);
    }

    public function finalizarpagostripe()
    {
        foreach($this->productos as $prod){
            if($prod['incluido']&&!$prod['seleccionada']){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'seleccione al menos los complementos incluidos antes de continuar',  'title' => 'ATENCIÓN']);
                return;
            }
        }
        Stripe::setApiKey($this->stripesecreta);
        $ruta=route('galeriacliente',[$this->idgaleria,$this->md5,$this->stripeconfirmapago]);
        $ruta2=route('galeriacliente',[$this->idgaleria,$this->md5,'errorpago']);
        Galeria::where('id',$this->idgaleria)->update(['imppago'=>$this->precio]);
        $session=\Stripe\Checkout\Session::create([
            'success_url' => $ruta,
            'cancel_url' => $ruta2,
            'line_items' => [
              [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $this->precio *100,
                    'product_data' => [
                        'name' => 'Pago galería en '.$this->empresa,
                    ],
                ],
                'quantity' => 1,
                  ],
            ],
            'mode' => 'payment',
          ]);
        return redirect()->away($session->url);
    }

    public function descargarimagenporcliente($keyy){
        if($this->ficha->permitirdescarga==2){
            return; // por si pudieran meter mano por ahi
        }
        if($this->ficha->permitirdescarga==1 && $this->ficha->pagado==false){
            return; // por si pudieran meter mano por ahi
        }
        $binid=($this->galeria[$keyy]['id']);
        $nombrefoto=($this->galeria[$keyy]['nombre']);
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->ficha->user_id."/";
        $bindata=$disks3->get($filePaths3.$binid.".jpg");
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $filee=$storage_path.$nombrefoto;
        File::put($filee,$bindata);
        return response()->file($filee, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename='.str_replace(" ","_",$nombrefoto)
        ]);
    }

    public function descargalista(){
        //$this->peticiondescarga=false;
        //public $enlacedescarga="";
        //Log: :info("poll");
        //$storage_path = storage_path('app/livewire-tmp')."/";
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $nombrezip="galeria_".$this->idgaleria."_".md5('cucu'.$this->idgaleria).".zip";
        $this->rutadescarga=URL('/storage/tmpgallery/'.$nombrezip);
        //Log: :info("waiting ".$storage_path.$nombrezip);
        if(File::exists($storage_path.$nombrezip)){
            $this->dispatch('postprocesado_end5',['type' => 'error',  'message' => '',  'title' => 'ATENCIÓN']);
            $this->descargadisponible=true;
        }
    }
    
    public function descargarclienteend(){
        $this->peticiondescarga=false;
        //$storage_path = storage_path('app/livewire-tmp')."/";
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $nombrezip="galeria_".$this->idgaleria."_".md5('cucu'.$this->idgaleria).".zip";
        return response()->file($storage_path.$nombrezip, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$nombrezip
        ])->deleteFileAfterSend(false);
    }

    public function descargarcliente(){
        if($this->ficha->permitirdescarga==2){
            //Log: :info("fail1");
            return; // por si pudieran meter mano por ahi
        }
        if($this->ficha->permitirdescarga==1 && $this->ficha->pagado==false){
            //Log: :info("fail2");
            return; // por si pudieran meter mano por ahi
        }
        //Log: :info(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]));
        //$response = Http::get(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]));
        $url=route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]);
        $opts = array(
            'http'=>array(
              'method'=>"GET",
              "timeout" => 5,
              'header'=>"Accept-language: en" 
            )
        );
        $context = stream_context_create($opts);
        // Open the file using the HTTP headers set above
        try {
            $file = file_get_contents($url, false, $context);
        } catch (\Throwable $th) {
        }
        //$response = Http::timeout(3)->get(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]));
        //$promise = Http::async()->get(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]));
        //file_get_contents(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]));
        //$responses = Http::pool(fn (Pool $pool) => [
        //    $pool->get(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)])),
        //]);
        //Log: :info($response);
        $this->peticiondescarga=true;
        return;

        //Downloadmail::where('galeria_id',$this->idgaleria)->delete();
        //Downloadmail::insert([
        //    'user_id'=>$this->ficha->user_id,
        //    'galeria_id'=>$this->idgaleria,
        //    'notas'=>"Galeria para descarga",
        //]);
        //$this->dispatch('mensaje',['type' => 'success',  'message' => 'en breve recibirá un email con el enlace de descarga',  'title' => 'ATENCIÓN']);
        //return;


        $x=Galeria::select('nombre','id')->find($this->idgaleria);
        if(!$x){
            $this->dispatch('postprocesado_end5',['type' => 'error',  'message' => '',  'title' => 'ATENCIÓN']);
            return;
        }
        //$storage_path = storage_path('app/public/tmpgallery')."/";
        $storage_path = storage_path('app/livewire-tmp')."/";
        //$nombrezip=str_replace(" ","_",$x->nombre.".zip");
        $nombrezip="galeria_".$x->id."_".md5('cucu'.$x->id).".zip";
        $zip = new ZipArchive;
        $zipFileName = $nombrezip;
        if ($zip->open($storage_path.$nombrezip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        }else{
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al crear el paquete comprimido',  'title' => 'ATENCIÓN']);
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            return;
        }
        //Log: :info("discharge");
        $x=Binarios::select('id','nombre')
        ->where('galeria_id',$this->idgaleria)
        ->where('selected',1)
        ->get();
        if(count($x)==0){
        $x=Binarios::select('id','nombre')
            ->where('galeria_id',$this->idgaleria)
            ->get();
        }
        foreach($x as $y){
            //Log: :info($y->nombre);
            //Log: :info($y->id);
            //$nombrefoto="oh myphoto_".$this->idgaleria."_".$y->nombre;
            $nombrefoto=$y->nombre;
            //Log: :info($nombrefoto);return;
            $disks3 = Storage::disk('sftp');
            $filePaths3 = '/galerias/'.$this->ficha->user_id."/";
            $bindata=$disks3->get($filePaths3.$y->id.".jpg");
            $filee=$storage_path.$nombrefoto;
            $zip->addFromString($nombrefoto, $bindata);
        }
        $zip->close();
        
        // fichero muy grande falla descarga php.ini memory_limit = 4096M
        
        $this->enlacedescarga=$nombrezip;
        //Log::info("endzip ".$storage_path.$nombrezip);
        Galeria::where('id',$this->idgaleria)->update(['descargada'=>true,'fechadescarga'=>Carbon::now()]);
        
        //return;
        
        $this->dispatch('postprocesado_end5',['type' => 'error',  'message' => '',  'title' => 'ATENCIÓN']);
        //$this->dispatch('postprocesado_endd',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        
        return response()->file($storage_path.$nombrezip, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$nombrezip
        ])->deleteFileAfterSend(true);
        
        return response()->download($storage_path.$nombrezip, $nombrezip,
            array('Content-Type: application/octet-stream','Content-Length: '. filesize($storage_path.$nombrezip)))
            ->deleteFileAfterSend(true);
        return response()->file($storage_path.$nombrezip, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$nombrezip
        ]);

    }

    public function render()
    {
        return view($this->vista);
    }
}
