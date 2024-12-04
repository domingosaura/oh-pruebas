<?php

namespace App\Http\Livewire\Calendarios;

use Livewire\Component;
use DB;
use Log;
use Auth;
use App\Http\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\User2;
use App\Models\Formaspago;
use App\Models\Cliente;
use App\Models\Calendario;
use App\Models\Sesiones;
use App\Models\Packs;
use App\Models\Citastemporal;
use Session;
use Request;
use Stripe\Stripe;
use Stripe\Charge;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Omnipay\Omnipay;

class Calendariocliente extends Component
{
    public $userid;
    public $md5;
    public $browserid;
    public $calendarid;
    public $vista = "livewire.calendarios.calendariocliente";
    public $empresa;
    public $logo;
    public $notifsesion = ""; // para enlace mail solicitar sesion
    public $calendartitle = "";
    public $calendarservices;
    public $calendarbin = "";
    public $emailempresa = "";
    public $telefonoempresa = "";
    public $emailsesion=""; // para enlace mail solicitar sesion
    public $telefsesion=""; // para enlace mail solicitar sesion
    public $clienteid = 0; // para enlace mail solicitar sesion
    public $clientenombre = "";
    public $clienteapellidos = "";
    public $clienteemail = "";
    public $title;
    public $descripcion;
    public $inicio;
    public $eventos;
    public $eventosjson;
    public $txbus = "";
    public $confirmada = false;
    public $servicios=[];
    public $servicio=[];
    public $pack=[];
    public $packtitle="";
    public $multi;
    public $seleccion=[];
    public $pagpago=1; // ventana de finalizar reserva
    public $permitirpago=false;
    public $idclientepago=0;
    public $buscadorcliente="";
    public $permitereserva=1;
    public $stage=1;
    //
    public $stripetoken="";
    public $stripepublica="";
    public $stripesecreta="";
    public $stripeconfirmapago="";
    public $haypaypal=false;
    public $paypalclientid="";
    public $paypalsecret="";
    public $paypalruta="";
    public $ppalprc=0;
    public $configppal;
    public $rutappal;
    public $rutappal2;
    public $paypalProduccion;
    public $hayredsys=false;
    public $redsyscodcomercio="";
    public $redsysclacomercio="";
    public $redsysterminal=1;
    public $mostrarreservadas=false;
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
    //
    public $efectivo;
    public $transferencia;
    public $tfredsys;
    public $paypal;
    public $stripe;
    public $bizum;
    public $iban;
    public $formaspago;

    public $fichanuevocliente=[
        'nombre'=>'',
        'apellidos'=>'',
        'nif'=>'',
        'telefono'=>'',
        'email'=>'',
    ];
    //protected $listeners = ['mensaje' => 'mensaje'];
    protected function rules()
    {
        return [
            //'ds.title' => 'required|max:50',
        ];
    }

    public function mount($idempresa, $idcalendario, $md5recibido,$error="")
    {
        //$this->userid=Auth::id();//auth()->user()->id;
        $this->browserid = 'oh'.Session::getId();
        // variable publica
        //Log: :info($this->sessionid);
        //Log: :info(session('idbrowser',""));
        //session(['idbrowser'=>$this->sessionid]);
        //
        $this->userid = $idempresa;
        if($idempresa==6)
            $this->idclientepago=2;
        $this->clienteid = 0;
        $this->calendarid = $idcalendario;
        $md5formado = 'uid' . Utils::left(md5($this->calendarid . 'calendar'), 4);
        if ($md5recibido != $md5formado) {
            // fallamos
            $this->vista = "livewire.authentication.error.error404";
            return;
        }
        $this->md5=$md5formado;
        $x = User::select('email')->find($idempresa);
        $this->emailempresa = $x->email;
        $x = User2::select('nombre','iban','logo','telefono')->find($idempresa);
        $this->telefonoempresa = $x->telefono;
        $this->logo=$x->logo;
        $this->empresa = $x->nombre;
        $this->iban = $x->iban;
        // calendario
        $x = DB::table("basecalendario")
            ->select('nombre','binario','permitereserva','activo','servicios','efectivo','transferencia','redsys','paypal','stripe','bizum','mostrarreservadas')
            ->where('id', $this->calendarid)
            ->get();
        $this->calendartitle = $x[0]->nombre;
        $this->calendarservices = $x[0]->servicios;
        $this->calendarbin = $x[0]->binario;
        $this->efectivo=$x[0]->efectivo==1?true:false;
        $this->transferencia=$x[0]->transferencia==1?true:false;
        $this->tfredsys=$x[0]->redsys==1?true:false;
        $this->paypal=$x[0]->paypal==1?true:false;
        $this->stripe=$x[0]->stripe==1?true:false;
        $this->bizum=$x[0]->bizum==1?true:false;
        $this->mostrarreservadas=$x[0]->mostrarreservadas==1?true:false;
        // formas de pago
        $this->formaspago = Formaspago::find($this->userid);
        $this->haypaypal = $this->formaspago->paypal==1?true:false;
        $this->paypalclientid = $this->formaspago->ppalclientid;
        $this->paypalsecret = $this->formaspago->ppalsecret;
        $this->ppalprc = $this->formaspago->ppalprc;
        $this->paypalProduccion = true;
        if($this->userid==6){
            $this->paypalProduccion = false; // para mis pruebas
        }
        // configuraciones formas de pago
        $this->stripepublica=$this->formaspago->stripe_publica;
        $this->stripesecreta=$this->formaspago->stripe_secreta;
        $this->hayredsys=$this->formaspago->redsys==1?true:false;
        if($this->hayredsys){
            $this->redsyscodcomercio=$this->formaspago->rscodcomercio;
            $this->redsysclacomercio=$this->formaspago->rsclacomercio;
            $this->redsysterminal=$this->formaspago->rsterminal;
            include_once(app_path() . DIRECTORY_SEPARATOR . 'RedsysAPIphp7.php');
        }
        //
        $this->procesarpago($error);
        //$this->cargardatos();
        $this->resetsesion(); // asigna $this->multi
        $this->permitereserva=1; // all ok
        if($x[0]->permitereserva==0){
            $this->permitereserva=-1;
        }
        if($x[0]->activo==0){
            $this->permitereserva=-2;
        }
        $this->cargar_servicios_calendario();
        $this->anular_prereserved();
        //$this->stage=4;
    }
    public function render()
    {
        return view($this->vista);
    }
    public function comenzarReserva()
    {
        // boton al arranque para mostrar los servicios (tabla sesiones)
        $this->stage=2;
    }

    public function cargar_servicios_calendario(){
        $calid=$this->calendarid;
        $servicios=$this->calendarservices;
        if($servicios==""){
            //$this->dispatch('mensajelargo',['type' => 'error',  'message' => 'Este calendario ya no tiene ningún servicio disponible.',  'title' => 'ATENCIÓN']);
            $this->permitereserva=-3;
            return;
        }
        $servicios=json_decode($servicios,true);
        $wherein="-1";
        foreach($servicios as $ser){
            $wherein.=",".$ser['id'];
        }
        $this->servicios = Sesiones::
            where('user_id',$this->userid)
            ->whereRaw("id in (".$wherein.")")
            ->where('packs',"<>",'')
            ->orderBy('id','asc')
            ->get()->toArray();

        foreach($this->servicios as $key=>$servicio){
            $packs=$servicio['packs'];
            $wherein="-1";
            if(strlen($packs)>0){
                $packs=json_decode($packs,true);
                foreach($packs as $ser){
                    $wherein.=",".$ser['id'];
                }
            }
            $packss = Packs::
                where('user_id',$this->userid)
                ->whereRaw("id in (".$wherein.")")
                ->orderBy('id','asc')
                ->get()->toArray();
            $this->servicios[$key]['packs']=$packss;
            $minmin=1000;
            $minmax=0;
            foreach($packss as $p){
                $minu=$p['minutos'];
                $minmin=$minu<$minmin?$minu:$minmin;
                $minmax=$minu>$minmax?$minu:$minmax;
            }
            if($minmin==$minmax)
                $this->servicios[$key]['txminutos']="Sesiones de $minmin minutos";
            if($minmin!=$minmax)
                $this->servicios[$key]['txminutos']="Sesiones entre $minmin y $minmax minutos";
        }
        //Log: :info($this->servicios);
    }

    public function seleccionarservicio($key){
        $this->servicio=$this->servicios[$key];
        $this->stage=3;
        //Log: :info($this->servicio);
    }

    public function seleccionarpack($key){
        //$this->servicio=$this->servicios[$key];
        $this->pack=$this->servicio['packs'][$key];
        //Log: :info($this->pack);
        $this->packtitle=$this->pack['nombre'];
        $tf=$this->cargardisponibles();
        if(!$tf){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Lo siento, no quedan citas disponibles para este pack.',  'title' => 'ATENCIÓN']);
            $this->dispatch('redrawallevents', ['eventos' => json_encode("") ]);
            return;
        }
        
        //$this->eventosjson = json_encode($this->eventos);
        $this->dispatch('redrawallevents', ['eventos' => $this->eventosjson ]);
        //$this->dispatch('redrawallevents', ['eventos' => $this->eventosjson]);

        $this->stage=4;
        //Log: :info($this->servicio);
    }

    public function volver(){
        $this->stage--;
    }

    public function cargardisponibles()
    {
        $minutos=$this->pack['minutos'];
        $clave='\'%"'.$this->servicio['id']."-".$this->pack['id'].'"%\'';
        $sel = DB::table("calendario")
            ->select('title','localizador','start', DB::raw('date_add(start,interval '.$minutos.' minute) as end'), 'minutos', 'calendario.id','servicios',
                DB::raw("'disponible' as calendarId"),'updated_at')
            ->where('basecalendario_id', $this->calendarid)
            ->where('cliente_id', 0)
            ->where('nodisponible', 0)
            ->whereRaw("servicios like ".$clave)
            ->where('reservado', false)
            ->where('confirmado', false)
            ->where('sinfecha', false)
            ->where('start','>=',Carbon::now())
            ->orderBy('id', 'asc');
        $x = $sel->get()->toArray();
        foreach ($x as $key => $obe) {
            $x[$key] = (array)$x[$key];
        }
        $this->eventos = (array)$x;
        foreach($this->eventos as $key=>$e){
            //$this->eventos[$key]['title']=substr($this->eventos[$key]['start'],11,5).' '.$this->eventos[$key]['title'];
            $this->eventos[$key]['title']=substr($this->eventos[$key]['start'],11,5).' - '.substr($this->eventos[$key]['end'],11,5);
            $this->eventos[$key]['minutos']=$this->pack['minutos'];
            $ok=$this->realmentedisponible($this->eventos[$key]['start'],$this->eventos[$key]['minutos']);
            if(!$ok){
                unset($this->eventos[$key]);
            }
        }
        //Utils::vacialog();
        if(count($this->eventos)==0)
            return false;


        if($this->mostrarreservadas){
            $sel = DB::table("calendario")
                ->select('title','localizador','start', DB::raw('date_add(start,interval '.$minutos.' minute) as end'), 'minutos', 'calendario.id','servicios',
                DB::raw("'nodisponible' as calendarId"),'updated_at')
                ->where('basecalendario_id', $this->calendarid);
            $sel->where(function($query) {
                $query->orWhere('cliente_id','>',0);
                $query->orWhere('reservado',true);
                $query->orWhere('nodisponible',true);
            });
            $sel->where('sinfecha', false)
                ->where('start','>=',Carbon::now())
                ->orderBy('id', 'asc');
            $x = $sel->get()->toArray();
            $x = $sel->get()->toArray();
            foreach ($x as $key => $obe) {
                $x[$key] = (array)$x[$key];
            }
            $eventosuso = (array)$x;
            //Log::info($eventosuso);
            foreach($eventosuso as $key=>$e){
                $eventosuso[$key]['title']=substr($eventosuso[$key]['start'],11,5).' - '.substr($eventosuso[$key]['end'],11,5);
                $this->eventos[]=$eventosuso[$key];
            }
        }



        //Log: :info($this->eventos);
        $this->eventos = array_values($this->eventos); // si habia huecos fallaba
        $this->eventosjson = json_encode($this->eventos);
        return true;
    }

    public function realmentedisponible($datetimeinicio,$minutos){
        // citas disponibles en este datetime?
        $citassimultaneas=Calendario::select('id')
            ->where('basecalendario_id', $this->calendarid)
            ->where('reservado',false)
            ->where('nodisponible',false)
            ->where('sinfecha',false)
            ->whereRaw('(date_add(prereserved_at, interval 10 minute)<now() or prereserved_at is null)')
            ->where('start',$datetimeinicio)
            ->get();
        $citassimultaneas=count($citassimultaneas);
        // disponibilidad
        $inicio=$datetimeinicio;
        $final=Carbon::parse($inicio)->addMinutes($minutos-1);
        // $citassimultaneas son las que empiezan a la misma hora
        // citas reservadas que empiezan antes
        $cogidasantes=Calendario::select('id')
        ->where('basecalendario_id', $this->calendarid)
        ->whereRaw("((reservado=1 or date_add(prereserved_at, interval 10 minute)>now()) and date_add(start,interval $minutos-1 minute) between '$inicio' and '$final')")
        ->where('start','<>',$datetimeinicio)
        ->where('sinfecha',false)
        ->where('nodisponible',false)
        ->get();
        $cogidasantes=count($cogidasantes);
        // fin citas reservadas que empiezan antes
        // citas reservadas que empiezan despues
        $cogidasdespues=Calendario::select('id')
        ->where('basecalendario_id', $this->calendarid)
        ->whereRaw("((reservado=1 or date_add(prereserved_at, interval 10 minute)>now()) and start between '$inicio' and '$final')")
        ->where('start','<>',$datetimeinicio)
        ->where('sinfecha',false)
        ->where('nodisponible',false)
        ->get();
        $cogidasdespues=count($cogidasdespues);
        // fin citas reservadas que empiezan despues
        $disponiblesdespues=Calendario::select('id')
        ->where('basecalendario_id', $this->calendarid)
        ->whereRaw("((reservado=0 or date_add(prereserved_at, interval 10 minute)<now()) and start between '$inicio' and '$final')")
        ->where('start','<>',$datetimeinicio)
        ->where('sinfecha',false)
        ->where('nodisponible',false)
        ->get();
        $disponiblesdespues=count($disponiblesdespues);
        //Log: :info($citassimultaneas."simultaneas");
        //Log: :info($cogidasantes."cogidas antes");
        //Log: :info($cogidasdespues."cogidas despues");
        //Log: :info($disponiblesdespues." dispo despues");
        $disponible=true;
        if($cogidasantes>=$citassimultaneas){
            $disponible=false;
        }
        if($cogidasdespues-$disponiblesdespues>=$citassimultaneas){
            $disponible=false;
        }
        return $disponible;
    }

    public function openeventclient($idid){
        //Log: :info("abre event $idid");return;
        $this->pagpago=1;
        $minutos=$this->pack['minutos'];
        $sel=DB::table("calendario")
            ->leftJoin('clientes','clientes.id','=','calendario.cliente_id')
            ->select('title','localizador','start','minutos','calendario.id','cuerpo','cliente_id','clientes.nombre as nombrecliente',
            DB::raw('DATE_ADD(START,interval '.$minutos.' MINUTE) as end'),'calendario.updated_at',
                'reservado','nodisponible','confirmado','pagado','importepagado','tipodepago','servicios','pregunta1','respuesta1','pregunta2',
                'respuesta2','pregunta3','respuesta3','pregunta4','respuesta4','pregunta5','respuesta5',
                'pregunta6','respuesta6','pregunta7','respuesta7','pregunta8','respuesta8','pregunta9','respuesta9','pregunta10','respuesta10')
            ->where('calendario.id',$idid)
            ->get()->toArray();
        if($sel[0]->nodisponible||$sel[0]->cliente_id>0||$sel[0]->reservado){
            return;
        }
        //$i=date(('d/m/Y h'),strtotime($sel[0]->start));
        //$i=Utils::left(Carbon::parse(Utils::left($sel[0]->start,10))->format('d/m/Y')." ".substr($sel[0]->start."",-8),16);
        //$f=Utils::left(Carbon::parse(Utils::left($sel[0]->end,10))->format('d/m/Y')." ".substr($sel[0]->end."",-8),16);
        $this->resetsesion(); // asigna public $multi
        $this->multi['id']=$idid;
        $this->multi['nuevo']=false;
        $this->multi['title']=$sel[0]->title;
        $this->multi['localizador']=$sel[0]->localizador;
        $this->multi['descripcion']=$sel[0]->cuerpo;
        $this->multi['inicio']=$sel[0]->start;
        $this->multi['fin']=$sel[0]->end;
        $this->multi['id']=$idid;
        $this->multi['minutos']=$minutos;
        $this->multi['cliente_id']=$sel[0]->cliente_id;
        $this->multi['nombrecliente']=$sel[0]->nombrecliente;
        $this->multi['reservado']=$sel[0]->reservado==1?true:false;
        $this->multi['confirmado']=$sel[0]->confirmado==1?true:false;
        $this->multi['pagado']=$sel[0]->pagado==1?true:false;
        $this->multi['importepagado']=$sel[0]->importepagado;
        $this->multi['tipodepago']=$sel[0]->tipodepago;
        if($this->pack['precioreserva']==0)
            $this->multi['tipodepago']=1;
        $this->multi['pregunta1']=$sel[0]->pregunta1;
        $this->multi['pregunta2']=$sel[0]->pregunta2;
        $this->multi['pregunta3']=$sel[0]->pregunta3;
        $this->multi['pregunta4']=$sel[0]->pregunta4;
        $this->multi['pregunta5']=$sel[0]->pregunta5;
        $this->multi['pregunta6']=$sel[0]->pregunta6;
        $this->multi['pregunta7']=$sel[0]->pregunta7;
        $this->multi['pregunta8']=$sel[0]->pregunta8;
        $this->multi['pregunta9']=$sel[0]->pregunta9;
        $this->multi['pregunta10']=$sel[0]->pregunta10;
        $this->multi['respuesta1']=$sel[0]->respuesta1;
        $this->multi['respuesta2']=$sel[0]->respuesta2;
        $this->multi['respuesta3']=$sel[0]->respuesta3;
        $this->multi['respuesta4']=$sel[0]->respuesta4;
        $this->multi['respuesta5']=$sel[0]->respuesta5;
        $this->multi['respuesta6']=$sel[0]->respuesta6;
        $this->multi['respuesta7']=$sel[0]->respuesta7;
        $this->multi['respuesta8']=$sel[0]->respuesta8;
        $this->multi['respuesta9']=$sel[0]->respuesta9;
        $this->multi['respuesta10']=$sel[0]->respuesta10;
        $this->multi['respuesta1']="";
        $this->multi['respuesta2']="";
        $this->multi['respuesta3']="";
        $this->multi['respuesta4']="";
        $this->multi['respuesta5']="";
        $this->multi['respuesta6']="";
        $this->multi['respuesta7']="";
        $this->multi['respuesta8']="";
        $this->multi['respuesta9']="";
        $this->multi['respuesta10']="";
        //$tx="";
        //$tx=$sel[0]->tipodepago==1?" (efectivo)":$tx;
        //$tx=$sel[0]->tipodepago==2?" (transferencia)":$tx;
        //$tx=$sel[0]->tipodepago==3?" (redsys)":$tx;
        //$tx=$sel[0]->tipodepago==4?" (paypal)":$tx;
        //$tx=$sel[0]->tipodepago==5?" (stripe)":$tx;
        //$tx=$sel[0]->tipodepago==6?" (bizum)":$tx;

        $this->dispatch('showmodalcita', []);
    }

    public function resetsesion()
    {
        //$this->servicios_seleccionados = [];
        $this->multi=[
            'id'=>0,
            'title'=>'',
            'localizador'=>'',
            'descripcion'=>'',
            'inicio'=>null,
            'fin'=>null,
            'minutos'=>60,
            //'numerosesiones'=>1,
            //'repeticiones'=>1,
            'cliente_id'=>0,
            'reservado'=>false,
            'confirmado'=>false,
            'pagado'=>false,
            'importepagado'=>0,
            'tipodepago'=>0,
            'updated_at'=>'',
            'pregunta1'=>'',
            'respuesta1'=>'',
            'pregunta2'=>'',
            'respuesta2'=>'',
            'pregunta3'=>'',
            'respuesta3'=>'',
            'pregunta4'=>'',
            'respuesta4'=>'',
            'pregunta5'=>'',
            'respuesta5'=>'',
            'pregunta6'=>'',
            'respuesta6'=>'',
            'pregunta7'=>'',
            'respuesta7'=>'',
            'pregunta8'=>'',
            'respuesta8'=>'',
            'pregunta9'=>'',
            'respuesta9'=>'',
            'pregunta10'=>'',
            'respuesta10'=>'',
        ];
    }

    public function anular_prereserved(){
        Calendario::where('basecalendario_id', $this->calendarid)->where('ipcontrato',$this->browserid)->update([
            'ipcontrato'=>'',
            'prereserved_at'=>null,
        ]);
    }

    public function anular_prereserved2(){
        $this->anular_prereserved();
        $this->dispatch('closemodalconfirmarcitacliente', []); // no es necesario aqui pero no molesta
    }

    public function prereservar($idcal){
        Calendario::where('basecalendario_id', $this->calendarid)->where('id',$idcal)->update([
            'ipcontrato'=>$this->browserid,
            'prereserved_at'=>Carbon::now(),
        ]);
    }

    public function paginareserva($x){
        // $x 1 suma 1 - -1 resta 1
        $idcal=$this->multi['id'];
        $this->permitirpago=false;
        $this->pagpago+=$x;

        //$this->servicio;
        //$this->pack;

        if($this->pagpago==2){
            // hemos pasado de la pagina primera de detalle a comenzar la reserva
            $this->lock();
            $ok=$this->realmentedisponible($this->multi['inicio'],$this->multi['minutos']);
            if(!$ok){
                $this->unlock();
                $this->dispatch('closemodalconfirmarcitacliente', []); // no es necesario aqui pero no molesta
                $this->dispatch('removeevent', ['id' => $idcal]);
                $this->dispatch('mensaje',['type' => 'error',  'message' =>"Lo siento, esta cita ya se ha reservado durante el proceso de selección",  'title' => 'ATENCIÓN']);
                return;
            }
            $this->prereservar($idcal);
            $this->unlock();
        }
        if($this->pagpago==1){
            $this->anular_prereserved();
        }
        if($this->pagpago>=2){
            $this->prereservar($idcal); // en cada paso preseserva de nuevo para actualizar datetime por si el nene tarda mas de 10 minutos
        }

        if($this->hayredsys){
            $merchantorder = substr(rand(1001, 9999) . strtoupper(MD5(date("d") . date("m") . date("Y"))), 0, 12);
            $rsysproduccion=true;
            $bizum=false;
            if($this->userid==6){
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

            $urlretorno=route('reservas',[$this->userid,$this->calendarid,$this->md5,$this->multi['id'].'-'.md5($this->multi['id']."reservando")]);
            //$urlretorno=route('reservas',[$this->userid,$this->calendarid,$this->md5]);
            $urlok=route('reservas',[$this->userid,$this->calendarid,$this->md5,$this->multi['id'].'-'.md5($this->multi['id']."reservando")]);
            $urlko=route('reservas',[$this->userid,$this->calendarid,$this->md5,$this->multi['id'].'-'.'errorpagoSesion']);
            $this->redsys['tpvredsysMerchantSignature'] = sha1(Utils::numFormat($this->pack['precioreserva'] * 100, 0) . // Ds_Merchant_Amount
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
                $rsys->setParameter("DS_MERCHANT_PRODUCTDESCRIPTION",'Reserva de sesión');
                $rsys->setParameter("DS_MERCHANT_AMOUNT", Utils::numFormat($this->pack['precioreserva'] * 100, 0));
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
                $rsys->setParameter("DS_MERCHANT_MERCHANTDATA", "mdata");
                $this->redsys['tpvredsysMerchantParameters256'] = $rsys->createMerchantParameters();
                $this->redsys['tpvredsysSignature256'] = $rsys->createMerchantSignature($this->redsys['tpvredsysMerchantClaveComercio']);
                $rsys = null;
        }
    }
    
    public function buscar_cliente(){
        $texto=$this->buscadorcliente;
        if(strlen($texto)==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'No se han introducido datos.',  'title' => 'ATENCIÓN']);
            return;
        }
        $x=Cliente::select('id')->where('user_id',$this->userid)->where('email',$texto)->get()->toArray();
        if(count($x)>0){
            $this->idclientepago=$x[0]['id'];
            return;
        }
        $x=Cliente::select('id')->where('user_id',$this->userid)->where('telefono',$texto)->get()->toArray();
        if(count($x)>0){
            $this->idclientepago=$x[0]['id'];
            return;
        }
        $x=Cliente::select('id')->where('user_id',$this->userid)->where('nif',$texto)->get()->toArray();
        if(count($x)>0){
            $this->idclientepago=$x[0]['id'];
            return;
        }
        $this->dispatch('mensaje',['type' => 'error',  'message' => 'Lo siento, cliente no localizado.',  'title' => 'ATENCIÓN']);
    }

    public function nuevo_cliente(){
        $nombre=$this->fichanuevocliente['nombre'];
        $apellidos=$this->fichanuevocliente['apellidos'];
        $nif=$this->fichanuevocliente['nif'];
        $telefono=$this->fichanuevocliente['telefono'];
        $email=$this->fichanuevocliente['email'];
        if(strlen($nombre)<3||strlen($apellidos)<3||strlen($nif)<3||strlen($telefono)<3||strlen($email)<3){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Rellene todos los datos.',  'title' => 'ATENCIÓN']);
            return;
        }
        $x=Cliente::select('id')->where('user_id',$this->userid)->where('email',$email)->get()->toArray();
        if(count($x)>0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'La dirección de email ya está en uso. Si está registrado rellene el apartado superior.',  'title' => 'ATENCIÓN']);
            return;
        }
        $x=Cliente::select('id')->where('user_id',$this->userid)->where('telefono',$telefono)->get()->toArray();
        if(count($x)>0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'El teléfono ya está en uso. Si está registrado rellene el apartado superior.',  'title' => 'ATENCIÓN']);
            return;
        }
        $x=Cliente::select('id')->where('user_id',$this->userid)->where('nif',$nif)->get()->toArray();
        if(count($x)>0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'El NIF ya está en uso. Si está registrado rellene el apartado superior.',  'title' => 'ATENCIÓN']);
            return;
        }
        $this->idclientepago=Cliente::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>$nombre,
            'apellidos'=>$apellidos,
            'nif'=>$nif,
            'telefono'=>$telefono,
            'email'=>$email,
            'notasinternas'=>"este cliente se ha registrado desde la reserva de sesión en el calendario",
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }

    public function confirmar_reserva(){
        //$this->stripeconfirmapago=md5($idgal.'stripeando'.$md5);
        $ses=$this->servicio;
        if($this->multi['tipodepago']==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Por favor seleccione una forma de pago.',  'title' => 'ATENCIÓN']);
            return;
        }
        if(
            ($ses['obliga1']==1&&strlen($ses['respuesta1'])<1)||
            ($ses['obliga2']==1&&strlen($ses['respuesta2'])<1)||
            ($ses['obliga3']==1&&strlen($ses['respuesta3'])<1)||
            ($ses['obliga4']==1&&strlen($ses['respuesta4'])<1)||
            ($ses['obliga5']==1&&strlen($ses['respuesta5'])<1)||
            ($ses['obliga6']==1&&strlen($ses['respuesta6'])<1)||
            ($ses['obliga7']==1&&strlen($ses['respuesta7'])<1)||
            ($ses['obliga8']==1&&strlen($ses['respuesta8'])<1)||
            ($ses['obliga9']==1&&strlen($ses['respuesta9'])<1)||
            ($ses['obliga10']==1&&strlen($ses['respuesta10'])<1)
            )
            {
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Por favor responda a todas las preguntas obligatorias.',  'title' => 'ATENCIÓN']);
            return;
        }
        //Utils::vacialog();
        //Log: :info($this->multi);

        $limbo=[];
        
        $idcita=$this->multi['id'];
        $localizador=$this->multi['localizador'];
        $fechareserva=$this->multi['inicio'];
        $nombresesion=$this->servicio['nombre'];
        $nombrepack=$this->pack['nombre'];
        $idcliente=$this->idclientepago;
        $idsesion=$this->servicio['id'];
        $idpack=$this->pack['id'];
        $servicios='["'.$idsesion.'-'.$idpack.'"]';
        $tipopago=$this->multi['tipodepago'];
        $importe=$this->pack['precioreserva'];
        $minutos=$this->pack['minutos'];
        $preg1=$this->servicio['pregunta1'];
        $resp1=$this->servicio['respuesta1'];
        $preg2=$this->servicio['pregunta2'];
        $resp2=$this->servicio['respuesta2'];
        $preg3=$this->servicio['pregunta3'];
        $resp3=$this->servicio['respuesta3'];
        $preg4=$this->servicio['pregunta4'];
        $resp4=$this->servicio['respuesta4'];
        $preg5=$this->servicio['pregunta5'];
        $resp5=$this->servicio['respuesta5'];
        $preg6=$this->servicio['pregunta6'];
        $resp6=$this->servicio['respuesta6'];
        $preg7=$this->servicio['pregunta7'];
        $resp7=$this->servicio['respuesta7'];
        $preg8=$this->servicio['pregunta8'];
        $resp8=$this->servicio['respuesta8'];
        $preg9=$this->servicio['pregunta9'];
        $resp9=$this->servicio['respuesta9'];
        $preg10=$this->servicio['pregunta10'];
        $resp10=$this->servicio['respuesta10'];

        $asunto=$this->servicio['emailconfirmaasunto'];
        $cuerpo=$this->servicio['emailconfirmacuerpo'];
        if($importe==0)
            $cuerpo=$this->servicio['emailconfirmacuerpo0'];

        $reservado=true;
        $confirmado=true;
        $pagado=true;

        $descripcion="";
        switch($tipopago){
            case 1:
            case 2:
            case 6:
                // formas de pago manuales
                if($tipopago==1)
                    $descripcion="Efectivo";
                if($tipopago==2)
                    $descripcion="Transferencia";
                if($tipopago==6)
                    $descripcion="Bizum";
                //$importe=0;
                $confirmado=false;
                $pagado=false;
                break;
            case 3:
                // redsys
                $descripcion="Tarjeta de crédito";
                break;
            case 4:
                // paypal
                $descripcion="Paypal";
                if($this->ppalprc>0)
                    $importe=round($importe*(1+($this->ppalprc/100)),2);
                break;
            case 5:
                // stripe
                $descripcion="Stripe";
                break;
        }

        $limbo['idcita']=$idcita;
        $limbo['localizador']=$localizador;
        $limbo['fechareserva']=$fechareserva;
        $limbo['nombresesion']=$nombresesion;
        $limbo['nombrepack']=$nombrepack;
        $limbo['idcliente']=$idcliente;
        $limbo['idsesion']=$idsesion;
        $limbo['idpack']=$idpack;
        $limbo['servicios']=$servicios;
        $limbo['tipopago']=$tipopago;
        $limbo['importe']=$importe;
        $limbo['minutos']=$minutos;
        $limbo['asunto']=$asunto;
        $limbo['cuerpo']=$cuerpo;
        $limbo['preg1']=$preg1;
        $limbo['resp1']=$resp1;
        $limbo['preg2']=$preg2;
        $limbo['resp2']=$resp2;
        $limbo['preg3']=$preg3;
        $limbo['resp3']=$resp3;
        $limbo['preg4']=$preg4;
        $limbo['resp4']=$resp4;
        $limbo['preg5']=$preg5;
        $limbo['resp5']=$resp5;
        $limbo['reservado']=$reservado;
        $limbo['confirmado']=$confirmado;
        $limbo['pagado']=$pagado;
        $limbo['descripcion']=$descripcion;
        $limbo['importe']=$importe;
        $cliente=Cliente::where('user_id',$this->userid)->find($idcliente);
        $xuser = User::find($this->userid);
        $xuser2 = User2::find($this->userid);
        $asunto="Confirmación de reserva en ".$xuser2->nombre;
        $vista="email.reserva";
        $limbo['asunto']=$asunto;
        $limbo['vista']=$vista;
        // email personalizado
        $texto="";
        $asu=$this->servicio['emailconfirmaasunto'];
        $cue=$this->servicio['emailconfirmacuerpo'];
        if($importe==0)
            $cuerpo=$this->servicio['emailconfirmacuerpo0'];
        if(strlen($asu)>0 && strlen($cue)>0){
            $vista="email.reservapersonalizado";
            $asunto=$asu;
            $texto=$cue;
            $limbo['asunto']=$asunto;
            $limbo['vista']=$vista;
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
            $s='<span style="    ">nombresesion</span>';
            $texto=str_replace($s,$nombresesion,$texto);
            $s='<span style="    ">nombrepack</span>';
            $texto=str_replace($s,$nombrepack,$texto);
            $s='<span style="    ">localizador</span>';
            $texto=str_replace($s,$localizador,$texto);
            $s='<span style="    ">fechasesion</span>';
            $texto=str_replace($s,Utils::datetime($fechareserva),$texto);
            //$texto=str_replace($s,$fechareserva,$texto);
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
            $s='<strong style="    ">nombresesion</strong>';
            $texto=str_replace($s,'<strong>'.$nombresesion.'</strong>',$texto);
            $s='<strong style="    ">nombrepack</strong>';
            $texto=str_replace($s,'<strong>'.$nombrepack.'</strong>',$texto);
            $s='<strong style="    ">localizador</strong>';
            $texto=str_replace($s,'<strong>'.$localizador.'</strong>',$texto);
            $s='<strong style="    ">fechasesion</strong>';
            $texto=str_replace($s,'<strong>'.Utils::datetime($fechareserva).'</strong>',$texto);
            //$texto=str_replace($s,'<strong>'.$fechareserva.'</strong>',$texto);
        }
        $limbo['texto']=$texto;
        //

        if($tipopago==3){
            Citastemporal::where('cita_id',$idcita)->delete();
            Citastemporal::insert([
                'cita_id'=>$idcita,
                'type'=>'redsys_lb',
                'data'=>json_encode($limbo),
            ]);
            $this->dispatch('submitredsys', ['id' => 'redsys']);
        }
        if($tipopago==5){
            Citastemporal::where('cita_id',$idcita)->delete();
            Citastemporal::insert([
                'cita_id'=>$idcita,
                'type'=>'stripe_lb',
                'data'=>json_encode($limbo),
            ]);
            Stripe::setApiKey($this->stripesecreta);
            $ruta=route('reservas',[$this->userid,$this->calendarid,$this->md5,$idcita.'-'.md5($idcita."reservando")]);
            $ruta2=route('reservas',[$this->userid,$this->calendarid,$this->md5,$idcita.'-'.'errorpagoSesion']);
            $session=\Stripe\Checkout\Session::create([
                'success_url' => $ruta,
                'cancel_url' => $ruta2,
                'line_items' => [
                  [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $importe*100,
                        'product_data' => [
                            'name' => 'Reserva de sesión en '.$xuser2->nombre,
                        ],
                    ],
                    'quantity' => 1,
                      ],
                ],
                'mode' => 'payment',
              ]);
            return redirect()->away($session->url);
        }
        if($tipopago==4){
            Citastemporal::where('cita_id',$idcita)->delete();
            Citastemporal::insert([
                'cita_id'=>$idcita,
                'type'=>'paypal_lb',
                'data'=>json_encode($limbo),
            ]);
            //omnipay
            $ruta=route('reservas',[$this->userid,$this->calendarid,$this->md5,$idcita.'-'.md5($idcita."reservando")]);
            $ruta2=route('reservas',[$this->userid,$this->calendarid,$this->md5,$idcita.'-'.'errorpagoSesion']);
            $gateway = Omnipay::create('PayPal_Rest');
            $gateway->setClientId($this->paypalclientid);
            $gateway->setSecret($this->paypalsecret);
            $gateway->setTestMode(!$this->paypalProduccion); //set it to 'false' when go live
            $response = $gateway->purchase(array(
                'amount' => $importe,
                'currency' => "EUR",
                'returnUrl' => $ruta,
                'cancelUrl' => $ruta2,
            ))->send();
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
        if($tipopago==1||$tipopago==2||$tipopago==6){
            //pago manual
            Calendario::where('basecalendario_id', $this->calendarid)->where('id',$idcita)->update([
                'cliente_id'=>$idcliente,
                'reservado'=>$reservado,
                'confirmado'=>$confirmado,
                'pagado'=>$pagado,
                'importepagado'=>$importe,
                'tipodepago'=>$tipopago,
                'minutos'=>$minutos,
                'reserved_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'servicios'=>$servicios,
                'pregunta1'=>$preg1,
                'pregunta2'=>$preg2,
                'pregunta3'=>$preg3,
                'pregunta4'=>$preg4,
                'pregunta5'=>$preg5,
                'pregunta6'=>$preg6,
                'pregunta7'=>$preg7,
                'pregunta8'=>$preg8,
                'pregunta9'=>$preg9,
                'pregunta10'=>$preg10,
                'respuesta1'=>$resp1,
                'respuesta2'=>$resp2,
                'respuesta3'=>$resp3,
                'respuesta4'=>$resp4,
                'respuesta5'=>$resp5,
                'respuesta6'=>$resp6,
                'respuesta7'=>$resp7,
                'respuesta8'=>$resp8,
                'respuesta9'=>$resp9,
                'respuesta10'=>$resp10,
            ]);
            $datos=[
                'ruta'=>'',
                'logo'=>$xuser2->logo,
                'empresa'=>$xuser2->nombre,
                'fechareserva'=>$fechareserva,
                'pago'=>$descripcion,
                'importe'=>$importe,
                'personalizado'=>$texto,
            ];
            $ok=Utils::sendmail($cliente->id,$this->userid,$vista,$xuser2->nombre,$xuser->email,$asunto,$datos);
            $this->dispatch('removeevent', ['id' => $idcita]);
            //$this->cargardatos();
            $this->resetsesion();
            $this->dispatch('mensajelargo',['type' => 'success',  'message' => 'Cita confirmada, revise su correo electrónico.',  'title' => 'ATENCIÓN']);
            //$this->dispatch('redrawallevents', ['eventos' => $this->eventosjson]);
            $this->dispatch('closemodalconfirmarcitacliente', []);
        }
    }

    public function fpago(){
    }

    public function lock(){
        DB::connection()->getPdo()->exec("lock tables calendario write");
    }

    public function unlock(){
        DB::connection()->getPdo()->exec("unlock tables");
    }

    public function procesarpago($recibido){
        if(strlen($recibido)==0){
            return;
        }
        $x=explode("-",$recibido);
        $idcita=$x[0];
        $reser=$x[1];
        if (str_contains($recibido, '-errorpagoSesion')) {
            Citastemporal::where('cita_id',$idcita)->delete();
            $this->dispatch('mensajelargo',['type' => 'error',  'message' => 'Se ha cancelado la reserva.',  'title' => 'ATENCIÓN']);
            return;
        }
        $formado=$idcita."-".md5($idcita."reservando");

        if (Request::isMethod('post')){
            //$x=Request::all();
            //if(count($x)>0){
            //    $recibido=$formado; // no hago mas comprobaciones no debe hacer falta
            //    //Log: :info($recibido);
            //}
        }

        if ($formado!=$recibido) {
            Citastemporal::where('cita_id',$idcita)->delete();
            $this->dispatch('mensajelargo',['type' => 'error',  'message' => 'Se ha cancelado la reserva. Error en datos recibidos.',  'title' => 'ATENCIÓN']);
            return;
        }
        // si llega aqui debe estar todo ok
        $xuser = User::find($this->userid);
        $xuser2 = User2::find($this->userid);
        $x=Citastemporal::where('cita_id',$idcita)->get();
        if(count($x)==0)
            return;
        $xa=json_decode($x[0]['data'],true);
        //Log: :info($x);
        if($x[0]['type']=="paypal_lb"){
            $xp=Request::all();
            $paymentId=$xp['paymentId'];
            $PayerID=$xp['PayerID'];
            $token=$xp['token'];
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
                    //$this->dispatch('mensaje',['type' => 'success',  'message' =>"Pago procesado correctamente." ,  'title' => 'ATENCIÓN']);
                    //$this->dispatch('mensaje',['type' => 'success',  'message' =>"Payment of {$arr['transactions'][0]['amount']['total']} was accepted from {$arr['payer']['payer_info']['email']}. Transaction Id: {$arr['id']}." ,  'title' => 'ATENCIÓN']);
                }
                else{
                    //$this->dispatch('mensaje',['type' => 'error',  'message' =>$response->getMessage(),  'title' => 'ATENCIÓN']);
                    //return $response->getMessage();
                }
            }
            else{
                //$this->dispatch('mensaje',['type' => 'error',  'message' =>'El pago no se ha procesado, error indeterminado',  'title' => 'ATENCIÓN']);
            }
            if(strlen($paymentidreceived)==0){
                //$this->error='errorpago';
                //Log: :info($response);
            }else{
                Calendario::where('basecalendario_id', $this->calendarid)->where('id',$idcita)->update([
                    'cliente_id'=>$xa['idcliente'],
                    'reservado'=>$xa['reservado'],
                    'confirmado'=>$xa['confirmado'],
                    'pagado'=>$xa['pagado'],
                    'importepagado'=>$xa['importe'],
                    'tipodepago'=>$xa['tipopago'],
                    'minutos'=>$xa['minutos'],
                    'reserved_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                    'servicios'=>$xa['servicios'],
                    'pregunta1'=>$xa['preg1'],
                    'pregunta2'=>$xa['preg2'],
                    'pregunta3'=>$xa['preg3'],
                    'pregunta4'=>$xa['preg4'],
                    'pregunta5'=>$xa['preg5'],
                    'pregunta6'=>$xa['preg6'],
                    'pregunta7'=>$xa['preg7'],
                    'pregunta8'=>$xa['preg8'],
                    'pregunta9'=>$xa['preg9'],
                    'pregunta10'=>$xa['preg10'],
                    'respuesta1'=>$xa['resp1'],
                    'respuesta2'=>$xa['resp2'],
                    'respuesta3'=>$xa['resp3'],
                    'respuesta4'=>$xa['resp4'],
                    'respuesta5'=>$xa['resp5'],
                    'respuesta6'=>$xa['resp6'],
                    'respuesta7'=>$xa['resp7'],
                    'respuesta8'=>$xa['resp8'],
                    'respuesta9'=>$xa['resp9'],
                    'respuesta10'=>$xa['resp10'],
                ]);
                $datos=[
                    'ruta'=>'',
                    'logo'=>$xuser2->logo,
                    'empresa'=>$xuser2->nombre,
                    'fechareserva'=>$xa['fechareserva'],
                    'pago'=>$xa['descripcion'],
                    'importe'=>$xa['importe'],
                    'personalizado'=>$xa['texto'],
                ];
                $ok=Utils::sendmail($xa['idcliente'],$this->userid,$xa['vista'],$xuser2->nombre,$xuser->email,$xa['asunto'],$datos);
                Citastemporal::where('cita_id',$idcita)->delete();
                $this->dispatch('mensajelargo',['type' => 'success',  'message' => 'Cita confirmada, revise su correo electrónico.',  'title' => 'ATENCIÓN']);
            }
        }
        if($x[0]['type']=="stripe_lb"){
            Calendario::where('basecalendario_id', $this->calendarid)->where('id',$idcita)->update([
                'cliente_id'=>$xa['idcliente'],
                'reservado'=>$xa['reservado'],
                'confirmado'=>$xa['confirmado'],
                'pagado'=>$xa['pagado'],
                'importepagado'=>$xa['importe'],
                'tipodepago'=>$xa['tipopago'],
                'minutos'=>$xa['minutos'],
                'reserved_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'servicios'=>$xa['servicios'],
                'pregunta1'=>$xa['preg1'],
                'pregunta2'=>$xa['preg2'],
                'pregunta3'=>$xa['preg3'],
                'pregunta4'=>$xa['preg4'],
                'pregunta5'=>$xa['preg5'],
                'pregunta6'=>$xa['preg6'],
                'pregunta7'=>$xa['preg7'],
                'pregunta8'=>$xa['preg8'],
                'pregunta9'=>$xa['preg9'],
                'pregunta10'=>$xa['preg10'],
                'respuesta1'=>$xa['resp1'],
                'respuesta2'=>$xa['resp2'],
                'respuesta3'=>$xa['resp3'],
                'respuesta4'=>$xa['resp4'],
                'respuesta5'=>$xa['resp5'],
                'respuesta6'=>$xa['resp6'],
                'respuesta7'=>$xa['resp7'],
                'respuesta8'=>$xa['resp8'],
                'respuesta9'=>$xa['resp9'],
                'respuesta10'=>$xa['resp10'],
            ]);
            $datos=[
                'ruta'=>'',
                'logo'=>$xuser2->logo,
                'empresa'=>$xuser2->nombre,
                'fechareserva'=>$xa['fechareserva'],
                'pago'=>$xa['descripcion'],
                'importe'=>$xa['importe'],
                'personalizado'=>$xa['texto'],
            ];
            $ok=Utils::sendmail($xa['idcliente'],$this->userid,$xa['vista'],$xuser2->nombre,$xuser->email,$xa['asunto'],$datos);
            Citastemporal::where('cita_id',$idcita)->delete();
            $this->dispatch('mensajelargo',['type' => 'success',  'message' => 'Cita confirmada, revise su correo electrónico.',  'title' => 'ATENCIÓN']);
        }
        if($x[0]['type']=="redsys_lb"){
            Calendario::where('basecalendario_id', $this->calendarid)->where('id',$idcita)->where('cliente_id',0)->update([
                'cliente_id'=>$xa['idcliente'],
                'reservado'=>$xa['reservado'],
                'confirmado'=>$xa['confirmado'],
                'pagado'=>$xa['pagado'],
                'importepagado'=>$xa['importe'],
                'tipodepago'=>$xa['tipopago'],
                'minutos'=>$xa['minutos'],
                'reserved_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'servicios'=>$xa['servicios'],
                'pregunta1'=>$xa['preg1'],
                'pregunta2'=>$xa['preg2'],
                'pregunta3'=>$xa['preg3'],
                'pregunta4'=>$xa['preg4'],
                'pregunta5'=>$xa['preg5'],
                'pregunta6'=>$xa['preg6'],
                'pregunta7'=>$xa['preg7'],
                'pregunta8'=>$xa['preg8'],
                'pregunta9'=>$xa['preg9'],
                'pregunta10'=>$xa['preg10'],
                'respuesta1'=>$xa['resp1'],
                'respuesta2'=>$xa['resp2'],
                'respuesta3'=>$xa['resp3'],
                'respuesta4'=>$xa['resp4'],
                'respuesta5'=>$xa['resp5'],
                'respuesta6'=>$xa['resp6'],
                'respuesta7'=>$xa['resp7'],
                'respuesta8'=>$xa['resp8'],
                'respuesta9'=>$xa['resp9'],
                'respuesta10'=>$xa['resp10'],
            ]);
            $datos=[
                'ruta'=>'',
                'logo'=>$xuser2->logo,
                'empresa'=>$xuser2->nombre,
                'fechareserva'=>$xa['fechareserva'],
                'pago'=>$xa['descripcion'],
                'importe'=>$xa['importe'],
                'personalizado'=>$xa['texto'],
            ];
            
            if (Request::isMethod('post')){
                $ok=Utils::sendmail($xa['idcliente'],$this->userid,$xa['vista'],$xuser2->nombre,$xuser->email,$xa['asunto'],$datos);
            }
            if (Request::isMethod('get')){
                Citastemporal::where('cita_id',$idcita)->delete();
            }
            $this->dispatch('mensajelargo',['type' => 'success',  'message' => 'Cita confirmada, revise su correo electrónico.',  'title' => 'ATENCIÓN']);
        }
    }



























    




    public function xxaaxxaacargardatos()
    {
        $sel = DB::table("calendario")
            ->select('title', 'start', 'minutos', 'calendario.id',
                DB::raw("'gray' as className"), DB::raw('DATE_ADD(START,interval minutos MINUTE) as end'),'updated_at')
            ->where('basecalendario_id', $this->calendarid)
            ->where('cliente_id', 0)
            ->where('reservado', false)
            ->where('confirmado', false)
            ->where('start','>=',Carbon::now())
            ->orderBy('id', 'asc');
        $x = $sel->get()->toArray();
        foreach ($x as $key => $obe) {
            $x[$key] = (array)$x[$key];
        }
        $this->eventos = (array)$x;
        $this->eventosjson = json_encode($this->eventos);
        //$xa=DB::select("select now() as x");
        //Log: :info($xa[0]->x);
        //Log: :info(Carbon::now());
        //Log: :info(Carbon::now()->addMinutes(60));
        //Log: :info($xa[0]->x==Carbon::now());
    }

    public function xxaaxxaacargalistaservicioscita($selecteds,$idvis,$datte,$datetimeinicio){
        //$selecteds servicios de la cita seleccionada
        //$idvis id de la cita seleccionada
        //$datte fecha
        // citas disponibles en este datetime?
        $citassimultaneas=Calendario::select('id')
            ->where('basecalendario_id', $this->calendarid)
            ->where('reservado',0)
            ->where('nodisponible',false)
            ->whereRaw('(date_add(prereserved_at, interval 10 minute)<now() or prereserved_at is null)')
            ->where('start',$datetimeinicio)
            ->get();
        $citassimultaneas=count($citassimultaneas);
        //Log: :info($citassimultaneas);
        $servic=[];
        foreach($selecteds as $sels){
            $x=explode("-",$sels);
            $serv=$x[0];
            $pack=$x[1];
            $servic[]=$serv;
        }
        $servicios = Sesiones::
            where('user_id',$this->userid)
            ->whereIn('id',$servic)
            ->orderBy('id','asc')
            ->get()->toArray();
        foreach($servicios as $key=>$serv){
            $sid=$serv['id'];
            $pa=[];
            foreach($selecteds as $sels){
                $x=explode("-",$sels);
                $serv=$x[0];
                $pack=$x[1];
                if($serv+0==$sid)
                    $pa[]=$pack;
            }
            $packss = Packs::
                where('user_id',$this->userid)
                ->where('minutos',">=",0)
                ->whereIn('id',$pa)
                ->orderBy('id','asc')
                ->get()->toArray();
            foreach($packss as $k=>$p){
                $minutos=$packss[$k]['minutos'];
                $inicio=$datetimeinicio;
                $final=Carbon::parse($inicio)->addMinutes($minutos-1);
                $disponible=true;
                // disponibilidad
                // $citassimultaneas son las que empiezan a la misma hora
                // citas reservadas que empiezan antes
                $cogidasantes=Calendario::select('id')
                ->where('basecalendario_id', $this->calendarid)
                ->whereRaw("((reservado=1 or date_add(prereserved_at, interval 10 minute)>now()) and date_add(start,interval $minutos-1 minute) between '$inicio' and '$final')")
                ->where('start','<>',$datetimeinicio)
                ->get();
                $cogidasantes=count($cogidasantes);
                // citas reservadas que empiezan antes
                $cogidasdespues=Calendario::select('id')
                ->where('basecalendario_id', $this->calendarid)
                ->whereRaw("((reservado=1 or date_add(prereserved_at, interval 10 minute)>now()) and start between '$inicio' and '$final')")
                ->where('start','<>',$datetimeinicio)
                ->get();
                $cogidasdespues=count($cogidasdespues);
                $disponiblesdespues=Calendario::select('id')
                ->where('basecalendario_id', $this->calendarid)
                ->whereRaw("((reservado=0 or date_add(prereserved_at, interval 10 minute)<now()) and start between '$inicio' and '$final')")
                ->where('start','<>',$datetimeinicio)
                ->get();
                $disponiblesdespues=count($disponiblesdespues);
                //Log: :info($citassimultaneas."simultaneas");
                //Log: :info($cogidasantes."cogidas antes");
                //Log: :info($cogidasdespues."cogidas despues");
                //Log: :info($disponiblesdespues." dispo despues");
                if($cogidasantes>=$citassimultaneas){
                    $disponible=false;
                }
                if($cogidasdespues-$disponiblesdespues>=$citassimultaneas){
                    $disponible=false;
                }
                //                
                $packss[$k]['disponible']=$disponible;
                $packss[$k]['seleccionadoparapago']=false;
            }
            $sid=$servicios[$key]['packs']=$packss;
        }
        $this->servicios=$servicios;
        //Log: :info($selecteds);
        //Log: :info($servicios);
    }

    public function xxaaxxaasolounpack($id1,$id2){
        //Log: :info($this->servicios);
        foreach($this->servicios as $key=>$servicio){
            $serid=$servicio['id'];
            foreach($servicio['packs'] as $key2=>$packs){
                $pacid=$packs['id'];
                $this->servicios[$key]['packs'][$key2]['seleccionadoparapago']=false;
                if($id1==$serid&&$id2==$pacid)
                    $this->servicios[$key]['packs'][$key2]['seleccionadoparapago']=true;
                //$packs['seleccionadoparapago']=false;
            }
        }
        $this->comenzar_reserva(false); // regraba prereserved_at para no perder el hilo en cada movimiento
    }

    public function xxaaxxaaresumenevento($idid){
        // pantalla que muestra el inicio de hacer reserva con las opciones disponibles en la misma
        $sel=DB::table("calendario")
            ->select('title','start','minutos','calendario.id','cuerpo','cliente_id',
            DB::raw('DATE_ADD(START,interval minutos MINUTE) as end'),'calendario.updated_at','servicios')
            ->where('calendario.id',$idid)
            ->get()->toArray();
        //$i=date(('d/m/Y h'),strtotime($sel[0]->start));
        //Log: :info($sel[0]->start);
        $fechacita=Utils::left($sel[0]->start,10);
        $i=Utils::left(Carbon::parse(Utils::left($sel[0]->start,10))->format('d/m/Y')." ".substr($sel[0]->start."",-8),16);
        $f=Utils::left(Carbon::parse(Utils::left($sel[0]->end,10))->format('d/m/Y')." ".substr($sel[0]->end."",-8),16);
        $this->resetsesion(); // asigna public $multi
        $this->multi['id']=$idid;
        $this->multi['nuevo']=false;
        $this->multi['title']=$sel[0]->title;
        $this->multi['descripcion']=$sel[0]->cuerpo;
        $this->multi['inicio']=$i;
        $this->multi['id']=$idid;
        $this->multi['cliente_id']=0;
        $this->multi['nombrecliente']="";
        $this->multi['reservado']=false;
        $this->multi['confirmado']=false;
        $this->multi['pagado']=false;
        $this->multi['importepagado']=0;
        $this->multi['pregunta1']="";
        $this->multi['respuesta1']="";
        $this->multi['pregunta2']="";
        $this->multi['respuesta2']="";
        $this->multi['pregunta3']="";
        $this->multi['respuesta3']="";
        $this->multi['pregunta4']="";
        $this->multi['respuesta4']="";
        $this->multi['pregunta5']="";
        $this->multi['respuesta5']="";
        $this->multi['pregunta6']="";
        $this->multi['respuesta6']="";
        $this->multi['pregunta7']="";
        $this->multi['respuesta7']="";
        $this->multi['pregunta8']="";
        $this->multi['respuesta8']="";
        $this->multi['pregunta9']="";
        $this->multi['respuesta9']="";
        $this->multi['pregunta10']="";
        $this->multi['respuesta10']="";
        foreach($this->eventos as $key=>$event){
            if($event['id']==$idid)
            $this->multi['updated_at']=$event['updated_at'];
        }
        $selecteds=[];
        if(strlen($sel[0]->servicios)>0)
            $selecteds=json_decode($sel[0]->servicios,true);
        $this->cargalistaservicioscita($selecteds,$idid,$fechacita,$sel[0]->start);
        $this->dispatch('showmodalinfocitacliente', []);
    }

    public function xxaaxxaacomenzar_reserva($dispatch=true)
    {
        $this->pagpago=1;
        $this->permitirpago=false;
        $idcal=$this->multi['id'];


        // aseguramos disponibilidad
        DB::connection()->getPdo()->exec("lock tables calendario write");
        $existe = Calendario::
            where('id', $idcal)
            ->where('cliente_id', 0)
            ->whereRaw("(reservado=0 or date_add(prereserved_at, interval 10 minute)<now())")
            ->count();
        if ($existe == 0) {
            // han cogido la cita
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Lo siento, acaban de reservar esta cita, seleccione otra.',  'title' => 'ATENCIÓN']);
            DB::connection()->getPdo()->exec("unlock tables");
            $this->dispatch('closemodalinfocitacliente', []);
            return;
        }
        Calendario::where('basecalendario_id', $this->calendarid)->where('id',$idcal)->update([
            'ipcontrato'=>$this->browserid,
            'prereserved_at'=>Carbon::now(),
        ]);
        DB::connection()->getPdo()->exec("unlock tables");

        if($dispatch){
            $this->dispatch('closemodalinfocitacliente', []);
            $this->dispatch('showmodalconfirmarcitacliente', []);
        }
    }






    



}
