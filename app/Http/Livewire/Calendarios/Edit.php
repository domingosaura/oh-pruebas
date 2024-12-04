<?php

namespace App\Http\Livewire\Calendarios;

use App\Models\Basecalendario;
use App\Models\Calendario;
use App\Models\Sesiones;
use App\Models\Packs;
use App\Models\Cliente;
use App\Models\User;
use App\Models\User2;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Log;
use Livewire\WithFileUploads;
use App\Http\Utils;
use Image;
use File;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
use Illuminate\Support\Facades\Mail;
use DB;
use Carbon\Carbon;
use DateTime;

class Edit extends Component
{
    use WithFileUploads;
    public Basecalendario $ficha;
    public $userid;
    public $seccion=1;
    public $files=[];
    public $photoname;
    public $base64="";
    public $idfot="a";
    public $photo;
    public $calendarid;
    public $eventos;
    public $eventosjson;
    public $serviciosdisponibles=[]; // todas las sesiones que podemos meter en la cita
    public $servicios=[]; // los servicios que hemos añadido en este calendario para seleccionar
    public $servicios_packs=[];
    public $servicios_seleccionados=[];
    public $serviciosincluidos=0;
    public $seleccion=[];
    public $buscar=[];
    public $txbus="";
    public $next;
    public $idclisesion=0; // para enlace mail solicitar sesion
    public $clientes;
    public $emailsesion=""; // para enlace mail solicitar sesion
    public $telefsesion=""; // para enlace mail solicitar sesion
    public $notifsesion=""; // para enlace mail solicitar sesion
    public $rutaaccesocliente="";
    public $multi;
    public $horario;
    public $totalcitas=0;
    public $viscita=1;
    public $totalcitasreservadas=0;
    public $pteconfirmar=0;
    public $nodisponible=0;
    public $libres=0;
    public $serviciossesion=null;
    public $errormail="";
    use AuthorizesRequests;
    
    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'ficha.nombre' => 'required|max:100',
            'ficha.activo' => '',
            'ficha.permitereserva' => '',
            'ficha.descripcion' => '',
            'ficha.binario' => '',
            'ficha.redsys' => '',
            'ficha.paypal' => '',
            'ficha.stripe' => '',
            'ficha.efectivo' => '',
            'ficha.transferencia' => '',
            'ficha.bizum' => '',
            'ficha.mostrarreservadas' => '',
        ];
    }

    public function mount($id){
        $this->userid=Auth::id();
        $this->calendarid=$id;
        $this->ficha = Basecalendario::where('user_id',$this->userid)->find($id);
        $this->ficha->activo=($this->ficha->activo==1?true:false);
        $this->ficha->permitereserva=($this->ficha->permitereserva==1?true:false);
        $this->ficha->paypal=($this->ficha->paypal==1?true:false);
        $this->ficha->redsys=($this->ficha->redsys==1?true:false);
        $this->ficha->stripe=($this->ficha->stripe==1?true:false);
        $this->ficha->efectivo=($this->ficha->efectivo==1?true:false);
        $this->ficha->transferencia=($this->ficha->transferencia==1?true:false);
        $this->ficha->bizum=($this->ficha->bizum==1?true:false);
        $this->ficha->mostrarreservadas=($this->ficha->mostrarreservadas==1?true:false);
        $this->serviciosdisponibles = Sesiones::
            where('user_id',$this->ficha->user_id)->where('activa',true)
            ->orderBy('nombre','asc')->get()->toArray();
        //Log::info($this->serviciosdisponibles);
        $this->cancelarcalcularsesiones(); // asigna public $multi
        $this->cargarclientes();
        $this->cargardatos();
        $this->cargalistaservicios();
        if($this->ficha->nombre=="")
            $this->seccion=3;
        //$this->multi['inicio']=date('d/m/Y')." 09:00";
        //$this->multi['numerosesiones']=1;
        $this->rutaaccesocliente=url('/').'/reservas/'.$this->userid.'/'.$this->calendarid.'/uid'.Utils::left(md5($this->calendarid.'calendar'),4);
    }

    public function cargalistaservicios(){
        //return;
        $this->seleccion=[];
        if(strlen($this->ficha->servicios)>0){
            $this->seleccion=json_decode($this->ficha->servicios,true);
        }
        $this->serviciosincluidos=count($this->seleccion);
        $wherein="-1";
        if(count($this->seleccion)>0){
            foreach($this->seleccion as $key=>$sel){
                $wherein.=",".$sel['id'];
            }
        }
        $this->servicios = Sesiones::
            where('user_id',$this->ficha->user_id)
            ->whereRaw("id in (".$wherein.")")
            ->orderBy('id','asc')
            ->get()->toArray();
        $this->serviciosincluidos=count($this->servicios);

        $this->servicios_packs=[];
        //Log: :info($this->servicios);
        //Utils::vacialog();
        foreach($this->servicios as $servi){
            if(strlen($servi['packs'])==0){
                continue;
            }
            $pac=json_decode($servi['packs'],true);
            //Log: :info($pac);
            foreach($pac as $key=>$p){
                $v=Packs::where('id',$p['id'])->get()->toArray()[0];
                //Log: :info($v);
                $v['value']=$servi['id']."-".$v['id'];
                //$v['idsesion']=$servi['id'];
                //$v['idpack']=$p['id'];
                //$v['idservicio']=$servi['id'];
                $v['binario']="";
                $v['selected']=false;

                $kk=$servi['nombre']." / ".$v['nombre'];
                $kk.="->precio: ".$v['preciopack']." / reserva: ".$v['precioreserva']." / minutos: ".$v['minutos'];
                $kk.=$v['sinfecha']==1?" / sin fecha":"";

                $kk=$servi['nombre']." - ".$v['nombre'];
                $kk.="<br/>precio: ".$v['preciopack']." - reserva: ".$v['precioreserva']." - minutos: ".$v['minutos'];
                $kk.=$v['sinfecha']==1?"<br/>permite sin fecha":"";

                $v['label']=$kk;
                //$this->servicios_packs[]=(array)$v;
                $this->servicios_packs[]=$v;
            }
        }
        //$this->servicios_packs=json_encode($this->servicios_packs);
        //Log: :info($this->servicios);
        //Log: :info($this->servicios_packs);
    }

    public function addservicio($key){
        $idp=$this->serviciosdisponibles[$key]['id'];

        foreach($this->seleccion as $key=>$sel){
            if($sel['id']==$idp){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'Este servicio ya está en este calendario.',  'title' => 'ATENCIÓN']);
                return;
            }
        }
        $this->seleccion[]=['id'=>$idp];
        $this->ficha->servicios=json_encode($this->seleccion);
        $this->ficha->update();
        $this->cargalistaservicios();
        //$this->dispatch('focusonpack', ['key' => $key]);
        //fprod{{$tag['id']}}
    }

    public function mostrarsolo($x){
        $this->viscita=$x;
    }

    public function deleteservicio($id){
        foreach($this->seleccion as $key=>$sel){
            if($sel['id']==$id){
                unset($this->seleccion[$key]);
            }
        }
        $this->ficha->servicios=json_encode($this->seleccion);
        $this->ficha->update();
        $this->cargalistaservicios();
    }

    public function resumen(){
        $this->totalcitas=Calendario::where('basecalendario_id',$this->calendarid)
            //->where('start','>=',date('Y-m-d'))
            //->where('start','>=',Carbon::now())
            ->count();
        $this->totalcitasreservadas=Calendario::where('basecalendario_id',$this->calendarid)
            ->where('start','>=',date('Y-m-d'))
            ->where('start','>=',Carbon::now())
            ->where('reservado',true)
            ->count();

        $this->pteconfirmar=Calendario::where('basecalendario_id',$this->calendarid)
            ->where('start','>=',date('Y-m-d'))
            ->where('start','>=',Carbon::now())
            ->where('reservado',true)
            ->where('confirmado',false)
            ->count();
        $this->nodisponible=Calendario::where('basecalendario_id',$this->calendarid)
            ->where('start','>=',date('Y-m-d'))
            ->where('start','>=',Carbon::now())
            ->where('nodisponible',true)
            ->count();
        $this->libres=Calendario::where('basecalendario_id',$this->calendarid)
            ->where('start','>=',date('Y-m-d'))
            ->where('start','>=',Carbon::now())
            ->where('reservado',false)
            ->count();
    }

    public function cargarclientes(){
        //
        $clientes=DB::table("clientes")
        ->select(DB::raw("concat(nombre,' ',apellidos,' ',telefono,' ',email,' ',nif) as label"),'id as value')
        ->where('user_id',$this->userid)
        ->orderBy('nombre','asc')
        ->get();
        $clientes=Utils::objectToArray($clientes);
        $this->clientes=json_encode($clientes);
        //
    }
    public function cargardatos()
    {
        $sel=DB::table("calendario")
        ->leftJoin('clientes','clientes.id','=','calendario.cliente_id')
        ->select('title','title as title2','start','minutos','cliente_id','calendario.id','clientes.nombre as nombrecliente','localizador',
            DB::raw("'disponible' as calendarId"),
            DB::raw('DATE_ADD(START,interval minutos MINUTE) as end'),
            'calendario.updated_at','nodisponible','reservado','confirmado','pagado','importepagado','tipodepago','servicios','pregunta1','respuesta1','pregunta2','respuesta2','pregunta3','respuesta3','pregunta4','respuesta4','pregunta5','respuesta5')
        ->where('basecalendario_id',$this->calendarid)
        ->orderBy('start','asc');
        $x=$sel->get()->toArray();
        foreach ($x as $key => $obe) {
            $titulo=$obe->title;
            $obe->title=substr($obe->start,11,5)." ".$titulo;
            $obe->nodisponible=$obe->nodisponible==1?true:false;
            $obe->reservado=$obe->reservado==1?true:false;
            $obe->confirmado=$obe->confirmado==1?true:false;
            $obe->pagado=$obe->pagado==1?true:false;
            if($obe->reservado&&$obe->cliente_id>0){
                $obe->calendarId="ocupado";
                if(!$obe->confirmado)
                    $obe->calendarId="pteconfirmar";
                $obe->title=substr($obe->start,11,5)."-".substr($obe->end,11,5)." ".$titulo;
                $obe->title.="\n".$obe->nombrecliente;
                $obe->title.="\nLoc: ".$obe->localizador;
            }
            if($obe->nodisponible)
                $obe->calendarId="nodisponible";
            // para livewire no pueden ser objetos
            $x[$key]=(array)$x[$key];
            // no lo vamos a usar, cuando intenta eliminar avisa si no puede $x[$key]['movimiento']=false; // falta completar cuando se pueda
        }
        $this->eventos=(array)$x;
        //Log: :info($this->eventos);
        $this->eventosjson = json_encode($this->eventos);
        // next
        $sel=DB::table("calendario")
        ->leftJoin('clientes','clientes.id','=','calendario.cliente_id')
        ->select('title','start','minutos','cliente_id','calendario.id','clientes.nombre as nombrecliente',
            DB::raw('DATE_ADD(START,interval minutos MINUTE) as end'))
        ->where('basecalendario_id',$this->calendarid)
        ->where('reservado',true)
        ->whereRaw('date(start) between curdate() and date_add(curdate(),interval 7 day)')
        ->orderBy('start','asc');
        $x=$sel->get()->toArray();
        foreach ($x as $key => $obe) {
            if($obe->cliente_id>0){
                $obe->title.="->".$obe->nombrecliente;
            }
            $x[$key]=(array)$x[$key];
        }
        $this->next=(array)$x;
        $this->resumen();
        return $this->next;
        //$this->ds = json_encode($this->ds);
        //log: :info($this->next);
        //$this->dispatch('redrawallevents', ['eventos' => $this->eventosjson ]);
    }

    public function vseccion($xx){
        $this->seccion=$xx;
    }

    public function busqueda(){
        $this->buscar=[];
        $text=$this->txbus;
        if(strlen($this->txbus)==0){
            return;
        }
        $datos=DB::table("calendario")
            ->leftJoin('clientes','clientes.id','=','calendario.cliente_id')
            ->select('title','start','minutos','calendario.id','clientes.nombre as nombrecliente','email','telefono')
            ->where('basecalendario_id',$this->calendarid);
        $datos->where(function($query) use($text) {
            $query->orWhere('title','like','%'.$text.'%');
            $query->orWhere('localizador',$text);
            $query->orWhere('clientes.nombre','like','%'.$text.'%');
            $query->orWhere('clientes.email','like','%'.$text.'%');
            $query->orWhere('clientes.telefono','like','%'.$text.'%');
        });
        $datos->orderBy('start','asc');
        $x=$datos->get()->toArray();
        foreach ($x as $key => $obe) {
            // para livewire no pueden ser objetos
            $x[$key]=(array)$x[$key];
            // no lo vamos a usar, cuando intenta eliminar avisa si no puede $x[$key]['movimiento']=false; // falta completar cuando se pueda
        }
        $this->buscar=(array)$x;
    }

    public function update(){
        $this->validate();
        $this->ficha->update();
        $this->dispatch('mensajecorto',['type' => 'success',  'message' => 'datos actualizados',  'title' => 'ATENCIÓN']);
        //return redirect(route('calendarios-management'))->with('status','calendario actualizado.');
    }

    public function updateout(){
        $this->validate();
        $this->ficha->update();
        return redirect(route('calendarios-management'))->with('status','calendario actualizado.');
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.calendarios.edit');
    }

    public function saveimage($x=""){
        if(empty($this->files)){
            return;
        }
        $this->photoname=$this->userid."_".Utils::randomString(5).".jpg";
        //log: :info(count($this->files));
        $nombreoriginal=$this->files[0]->getClientOriginalName();
        //log: :info($nombreoriginal);
        $this->files[0]->storeAs('photos',$this->photoname);
        $storage_path = storage_path('app/photos')."/";
        $filee=$storage_path.$this->photoname;
        //$fileemin=str_replace('.jpg','_min.jpg',$filee);
        $fileemin=str_replace('.jpg','_min.avif',$filee);
        //log: :info($filee);
        if(file_exists($filee)){
            //log: :info("existe");
        }else{
            //log: :info("no existe");
        }
        $image = Spatie::load($filee);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,425, (425*$height)/$width );
        //$image->Fit(Fit::Crop,425, 242 );
        $image->Fit(Fit::Crop,537, 363 );
        $image->save($fileemin);
        //$image = Image::make($filee);
        //// Main Image Upload on Folder Code
        //$image->resize(425,242, function ($const) {
        //    //$const->aspectRatio();
        //})->save($fileemin);
        $this->base64=base64_encode(File::get($fileemin));
        $this->ficha->binario="";
        $this->ficha->binario=$this->base64;
        $this->ficha->update();
        //log: :info($this->base64);
        File::delete($filee);
        File::delete($fileemin);
        $this->files=[];
        $this->idfot.="a";
        //$this->dispatch('clear');
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
    }

    public function setidcliente($idid){
        // tiene que estar
    }

    public function setidclientemulti($idid){
        $this->multi['cliente_id']=$idid;
    }

    public function fillemailsession($idid){
        $this->emailsesion="";
        $this->telefsesion="";
        $this->idclisesion=$idid;
        if($idid<1)
            return;
        $x = Cliente::find($idid);
        $this->emailsesion=$x->email;
        $this->telefsesion=$x->telefono;
    }

    public function enviarenlacewhatsapp(){
        //$ruta=url('/').'/reservas/'.$this->userid.'/'.$this->idclisesion.'/'.$this->calendarid; // http://193.122.63.203:8080
        $ruta=url('/').'/reservas/'.$this->userid.'/'.$this->calendarid.'/uid'.Utils::left(md5($this->calendarid.'calendar'),4); // http://193.122.63.203:8080
        
        $ruta=str_replace('nonohttps://','',$ruta); // disable preview image
        //$ruta=str_replace('https://www.','www.',$ruta); // disable preview image
        //$ruta=str_replace('https://','www.',$ruta); // disable preview image


        $x = User2::select('logo','nombre','iban')->find($this->userid);
        $empresa=$x->nombre;
        $clientetelefono=$this->telefsesion;
        if(!$clientetelefono){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Exxxxl cliente no tiene teléfono',  'title' => 'ATENCIÓN']);
            //return;
        }
        if(strlen($clientetelefono)==9)
            $clientetelefono="34".$clientetelefono;
        //$enlace="https://wa.me/$clientetelefono?text=".urlencode($empresa.' - Reserva de sesión - '.$ruta);
        $enlace="https://api.whatsapp.com/send/?phone=$clientetelefono&text=".urlencode($empresa.' - Reserva de sesión - '.$ruta);
        $this->emailsesion="";
        $this->telefsesion="";
        $this->notifsesion="";
        $this->idclisesion=0;
        $this->dispatch('closemodalcita', ['id' => '']);
        $this->dispatch('openwhatsapp', ['id' => $enlace]);
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'enlace enviado',  'title' => 'ATENCIÓN']);
    }

    public function enviarenlace(){
        $x = User2::select('logo','nombre','iban')->find($this->userid);
        $logo=$x->logo;
        $empresa=$x->nombre;
        $this->notifsesion="";
        $direcciones = [];
        $direcciones[]=['address'=>$this->emailsesion,'name'=>$this->emailsesion];
        $vista = "email.pedircita";
        $asunto="Reserva de sesión en ".$empresa;
        //$mmdd5=md5($this->userid.'-'.$this->idclisesion.'-'.$this->calendarid.Carbon::now());
        //$ruta=url('/').'/reservas/'.$this->userid.'/'.$this->idclisesion.'/'.$this->calendarid; // http://193.122.63.203:8080
        $ruta=url('/').'/reservas/'.$this->userid.'/'.$this->calendarid.'/uid'.Utils::left(md5($this->calendarid.'calendar'),4); // http://193.122.63.203:8080
        //Citas temporal::insert(['nombre'=>$mmdd5]);
        $ok = true;
        $datos=[
            'ruta'=>$ruta,
            'logo'=>$logo,
            'empresa'=>$empresa,
        ];
        $reply=Utils::cargarconfiguracionemailempresa($this->userid);
        $this->errormail="";
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
            Log::info(Utils::extraererrormail($ex)); // envía el error al registro de logs
            $this->errormail=Utils::extraererrormail($ex);
            $ok = false;
        }
        if(!$ok){
            $this->notifsesion="se ha producido un error, reintente";
            return;
        }
        $this->emailsesion="";
        $this->telefsesion="";
        $this->notifsesion="";
        $this->idclisesion=0;
        $this->dispatch('closemodalcita', ['id' => '']);
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'enlace enviado',  'title' => 'ATENCIÓN']);
    }

    public function setdia($dia){
        $this->multi[$dia]=!$this->multi[$dia];
    }

    public function moveevent($evento){
        //Log::info($evento);return;
        //'id' => 12,
        //'start' => '2024-11-13 16:00',
        $idevento=($evento['id']);
        $fecnuev=($evento['start']);
        //$fecnuev=Utils::left(str_replace("T"," ",$fecnuev),19);

        /*
        foreach($this->eventos as $key=>$event){
            if($event['id']==$idevento)
                $lastmodloaded=$event['updated_at'];
        }
        $lastmodactual=Calendario::where('id',$idevento)->select('updated_at')->get()[0]['updated_at'];
        if($lastmodloaded!=$lastmodactual){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => "Lo siento esta cita ha cambiado mientras la movía, no se modifica, inténtelo de nuevo.",  'title' => 'ATENCIÓN']);
            return redirect(route('edit-calendario',$this->calendarid))->with('status','Lo siento esta cita ha cambiado mientras la movía, no se modifica, inténtelo de nuevo.');
        }
        */
        //Log: :info($lastmod);
        //Log: :info($viejo['extendedProps']['updated_at']);
        Calendario::where('id',$idevento)->update([
            'updated_at'=>Carbon::now(),
            'start'=>$fecnuev,
        ]);
        /*
        DB::transaction(function() use ($fecnuev,$idevento){
            DB::update("update calendario set start='$fecnuev',updated_at=now() where id=$idevento");
        });
        */
        $this->cargardatos();
        $this->busqueda();
        //$this->dispatch('redrawallevents', ['eventos' => $this->eventosjson ]);
    }

    public function nodispoevento(){
        $idid=($this->multi['id']);
        $this->multi['nodisponible']=true;
    }

    public function sidispoevento(){
        $idid=($this->multi['id']);
        $this->multi['nodisponible']=false;
    }

    public function eliminarevento($idid=0){
        if($idid==0)
            $idid=($this->multi['id']);
        DB::table("calendario")->where('id',$idid)->delete();
        $this->dispatch('removeevent', ['id' => $idid]);
        $this->cargardatos();
        $this->busqueda();
    }

    public function liberarevento($idid=0){
        $idid=($this->multi['id']);
        DB::table("calendario")->where('id',$idid)->update([
            'cliente_id'=>0,
            'servicios'=>DB::raw('servicios2'),
            'reservado'=>0,
            'nodisponible'=>0,
            'confirmado'=>0,
            'pagado'=>0,
            'importepagado'=>0,
            'minutos'=>60,
            'tipodepago'=>0,
            'reserved_at'=>null,
            'prereserved_at'=>null,
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
        ]);
        $this->cargardatos();
        $this->dispatch('redrawallevents', ['eventos' => $this->eventosjson]);
        $this->busqueda();
        $this->dispatch('mensajelargo',['type' => 'success',  'message' => 'Se ha liberado esta cita. Revise los servicios disponibles en la cita.',  'title' => 'ATENCIÓN']);
        $this->openevent(['id'=>$idid]);
    }

    public function calcularsesiones(){
        //Utils::vacialog();
        //Log::info($this->multi);
        $title=$this->multi['title'];
        $horasfijas=$this->multi['horasfijas'];
        $horasintervalos=$this->multi['horasintervalos'];
        //$title="cita calendario";
        $start=$this->multi['datestart'];
        $end=$this->multi['dateend'];
        $dl=$this->multi['l'];
        $dm=$this->multi['m'];
        $dx=$this->multi['x'];
        $dj=$this->multi['j'];
        $dv=$this->multi['v'];
        $ds=$this->multi['s'];
        $dd=$this->multi['d'];
        $simultaneas=$this->multi['numerosesiones'];
        $simultaneas=$simultaneas==0?1:$simultaneas;
        $minutos=0;
        foreach($this->servicios_seleccionados as $sels){
            $x=explode("-",$sels);
            $pack=$x[1];
            foreach($this->servicios_packs as $sp){
                if($sp['id']==$pack&&$sp['minutos']>$minutos)
                    $minutos=$sp['minutos'];
            }
        }

        if($horasfijas){
            asort($this->horario);
            foreach($this->horario as $key=>$hot){
                if($hot=="")
                    unset($this->horario[$key]);
            }
            if(count($this->horario)==0)
                $this->horario[0]="09:00";
            $ant="";
            foreach($this->horario as $key=>$hot){
                if($hot==$ant){
                    $ant=$hot;
                    unset($this->horario[$key]);
                }
                $ant=$hot;
            }
            asort($this->horario);
        }
        if($horasintervalos){
            $this->horario=[];

            $desde1=$this->multi['int1desde'];
            $hasta1=$this->multi['int1hasta'];
            $minutos1=$this->multi['int1minutos'];
            $desde2=$this->multi['int2desde'];
            $hasta2=$this->multi['int2hasta'];
            $minutos2=$this->multi['int2minutos'];

            if(strlen($desde1)==5&&strlen($hasta1)==5&&$minutos1>0){
                $t1 = strtotime($desde1);
                $t2 = strtotime($hasta1);
                $t1-=$minutos1*60;
                while(true){
                    $t1+=$minutos1*60;
                    if($t1>=$t2){
                        break;
                    }
                    $hora_formateada = date("H:i", $t1);
                    $this->horario[]=$hora_formateada;
                }
            }
            if(strlen($desde2)==5&&strlen($hasta2)==5&&$minutos2>0){
                $t1 = strtotime($desde2);
                $t2 = strtotime($hasta2);
                $t1-=$minutos2*60;
                while(true){
                    $t1+=$minutos2*60;
                    if($t1>=$t2){
                        break;
                    }
                    $hora_formateada = date("H:i", $t1);
                    $this->horario[]=$hora_formateada;
                }
            }
        }
        
        if(count($this->horario)==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Faltan datos, se cancela.',  'title' => 'ATENCIÓN']);
            return;
        }

        //Log::info($this->horario);
        if(strlen($title)==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Es necesario un título para continuar.',  'title' => 'ATENCIÓN']);
            return;
        }
        if(!$horasfijas && !$horasintervalos){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Seleccione las horas en las que crear las sesiones.',  'title' => 'ATENCIÓN']);
            return;
        }
        if(empty($this->servicios_seleccionados)){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'No ha seleccionado ningún servicio.',  'title' => 'ATENCIÓN']);
            return;
        }
        $datetime1 = new DateTime($start);
        $datetime2 = new DateTime($end);
        if($datetime2<$datetime1){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Error en el rango de fechas, revise y reintente.',  'title' => 'ATENCIÓN']);
            return;
        }
        
        $fecha=date('Y-m-d', strtotime($start.' -1 days'));
        $creados=0;
        while(true){
            $fecha=date('Y-m-d', strtotime($fecha.' +1 days'));
            $diasemana=date('w', strtotime($fecha)); // 1 lunes 0 domingo
            //Log::info($fecha." ".$diasemana);
            $procesar=true;
            if($diasemana==0&&!$dd)
                $procesar=false;
            if($diasemana==1&&!$dl)
                $procesar=false;
            if($diasemana==2&&!$dm)
                $procesar=false;
            if($diasemana==3&&!$dx)
                $procesar=false;
            if($diasemana==4&&!$dj)
                $procesar=false;
            if($diasemana==5&&!$dv)
                $procesar=false;
            if($diasemana==6&&!$ds)
                $procesar=false;

            //Log::info($fecha." ".$diasemana);
            if($procesar){
                //Log::info($fecha." ".$diasemana." si");
                foreach($this->horario as $hot){
                    if(strlen($hot)==0)
                        continue;
                    $dtime=$fecha." ".$hot;
                    for($alfa=1;$alfa<=$simultaneas;$alfa++){
                        $creados++;
                        $i=DB::table("calendario")->insertGetId([
                            'basecalendario_id'=>$this->calendarid,
                            'title'=>$title,
                            'cuerpo'=>'',
                            'start'=>$dtime,
                            'minutos'=>$minutos,
                            'servicios'=>json_encode($this->servicios_seleccionados),
                            'servicios2'=>json_encode($this->servicios_seleccionados),
                            'cliente_id'=>0,
                            'localizador'=>'',
                            'updated_at'=>Carbon::now(),
                            'created_at'=>Carbon::now(),
                        ]);
                        Calendario::where('id',$i)->update(['localizador'=>''.$this->calendarid.'X'.$i]);
    
                    }
                }
            }
            if($fecha==$end){
                break;
            }
        }
        if($creados==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Con los datos configurados no se ha creado ninguna sesión.',  'title' => 'ATENCIÓN']);
            return;
        }
        $this->cancelarcalcularsesiones(); // asigna public $multi
        $this->dispatch('closemodalmulti', ['id' => '']);
        $this->cargardatos();
        $this->dispatch('redrawallevents', ['eventos' => $this->eventosjson]);
        $this->dispatch('mensaje',['type' => 'success',  'message' => $creados.' sesiones creadas.',  'title' => 'ATENCIÓN']);
    }

    public function addhora(){
        $this->horario[]='09:00';
    }

    public function removehora($key){
        unset($this->horario[$key]);
    }

    public function fijaintervalo($clicked){
        if($clicked==1){
            $this->multi['horasintervalos']=false;
            $this->multi['horasfijas']=true;
        }
        if($clicked==2){
            $this->multi['horasintervalos']=true;
            $this->multi['horasfijas']=false;
        }
    }

    public function cancelarcalcularsesiones(){
        $this->servicios_seleccionados=[];
        $this->horario=['09:00'];
        $this->multi=[
            'id'=>0,
            'title'=>'',
            'localizador'=>'',
            'datestart'=>date('Y-m-d'),
            'dateend'=>date('Y-m-d'),
            //'inicio'=>date('d/m/Y')." 09:00",
            'minutos'=>60,
            'numerosesiones'=>1,
            'nodisponible'=>0,
            'intervalo'=>0, // la sesion se crea cada x minutos
            'sesionesintervalo'=>0, // cuantas sesiones
            //'repeticiones'=>1,
            'cliente_id'=>0,
            'reservado'=>false,
            'confirmado'=>false,
            'pagado'=>false,
            'importepagado'=>0,
            'tipodepago'=>0,
            'l'=>true,
            'm'=>true,
            'x'=>true,
            'j'=>true,
            'v'=>true,
            's'=>false,
            'd'=>false,
            'updated_at'=>'',
            'nuevo'=>true, // true es nuevo evento false estamos editando el evento
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
            'horasfijas'=>false,
            'horasintervalos'=>true,
            'int1desde'=>'',
            'int1hasta'=>'',
            'int1minutos'=>15,
            'int2desde'=>'',
            'int2hasta'=>'',
            'int2minutos'=>15,
        ];
    }

    public function newevent($objeto=null){
        $this->cancelarcalcularsesiones(); // asigna public $multi

        $this->multi['datestart']=date('Y-m-d', strtotime(date('Y-m-d').' + 1 days'));
        $this->multi['dateend']=date('Y-m-d', strtotime(date('Y-m-d').' + 1 days'));
        if($objeto!=null){
            $this->multi['datestart']=date('Y-m-d', strtotime($objeto));
            $this->multi['dateend']=date('Y-m-d', strtotime($objeto));
        }
        //Log::info($objeto);
        //$date=($objeto['dateStr']." ".date('h').":00");
        //$date=date(('d/m/Y'),strtotime($objeto['dateStr']))." ".date('h').":00";
        //$date=date(('d/m/Y'),strtotime(  Utils::left($objeto['dateStr'],10)  ))." 09:00";
        $this->multi['nuevo']=true;
        $this->multi['datestart']=$this->multi['datestart'];
        $this->multi['dateend']=$this->multi['datestart'];
        $this->multi['cliente_id']=0;
        foreach($this->servicios_packs as $key=>$xxx){
            $this->servicios_packs[$key]['selected']=false;
        }
        //Log: :info($this->servicios_packs);
        $servicios_packs=json_encode($this->servicios_packs);
        $this->dispatch('populatechoices', ['choices' => $servicios_packs]);

        $this->dispatch('vselectsetvalue', ['idcli' => 0]);
        $this->dispatch('showmodal', []);
    }

    public function openevent2($id){
        $this->openevent(['id'=>$id]);
    }

    public function openevent($evento){
        $idid=$evento['id'];
        //Log::info($idid);
        $sel=DB::table("calendario")
            ->leftJoin('clientes','clientes.id','=','calendario.cliente_id')
            ->select('title','localizador','start','minutos','calendario.id','cliente_id','clientes.nombre as nombrecliente',
            DB::raw('DATE_ADD(START,interval minutos MINUTE) as end'),'calendario.updated_at','reservado','nodisponible','confirmado','pagado','importepagado','tipodepago','nodisponible','servicios','pregunta1','respuesta1','pregunta2','respuesta2','pregunta3','respuesta3','pregunta4','respuesta4','pregunta5','respuesta5')
            ->where('calendario.id',$idid)
            ->get()->toArray();
        //$i=date(('d/m/Y h'),strtotime($sel[0]->start));
        //$i=Utils::left(Carbon::parse(Utils::left($sel[0]->start,10))->format('d/m/Y')." ".substr($sel[0]->start."",-8),16);
        //$f=Utils::left(Carbon::parse(Utils::left($sel[0]->end,10))->format('d/m/Y')." ".substr($sel[0]->end."",-8),16);
        $this->cancelarcalcularsesiones(); // asigna public $multi
        $this->horario=[substr($sel[0]->start."",-8)];
        $this->multi['id']=$idid;
        $this->multi['nuevo']=false;
        $this->multi['title']=$sel[0]->title;
        $this->multi['localizador']=$sel[0]->localizador;
        $this->multi['datestart']=Utils::left($sel[0]->start,10);
        $this->multi['id']=$idid;
        $this->multi['cliente_id']=$sel[0]->cliente_id;
        $this->multi['nombrecliente']=$sel[0]->nombrecliente;
        $this->multi['nodisponible']=$sel[0]->nodisponible==1?true:false;
        $this->multi['reservado']=$sel[0]->reservado==1?true:false;
        $this->multi['confirmado']=$sel[0]->confirmado==1?true:false;
        $this->multi['pagado']=$sel[0]->pagado==1?true:false;
        $this->multi['importepagado']=$sel[0]->importepagado;
        $this->multi['pregunta1']=$sel[0]->pregunta1;
        $this->multi['respuesta1']=$sel[0]->respuesta1;
        $this->multi['pregunta2']=$sel[0]->pregunta2;
        $this->multi['respuesta2']=$sel[0]->respuesta2;
        $this->multi['pregunta3']=$sel[0]->pregunta3;
        $this->multi['respuesta3']=$sel[0]->respuesta3;
        $this->multi['pregunta4']=$sel[0]->pregunta4;
        $this->multi['respuesta4']=$sel[0]->respuesta4;
        $this->multi['pregunta5']=$sel[0]->pregunta5;
        $this->multi['respuesta5']=$sel[0]->respuesta5;

        $tx="";
        $tx=$sel[0]->tipodepago==1?" (efectivo)":$tx;
        $tx=$sel[0]->tipodepago==2?" (transferencia)":$tx;
        $tx=$sel[0]->tipodepago==3?" (redsys)":$tx;
        $tx=$sel[0]->tipodepago==4?" (paypal)":$tx;
        $tx=$sel[0]->tipodepago==5?" (stripe)":$tx;
        $tx=$sel[0]->tipodepago==6?" (bizum)":$tx;
        $this->multi['txpago']=$tx;
        foreach($this->eventos as $key=>$event){
            if($event['id']==$idid)
            $this->multi['updated_at']=$event['updated_at'];
        }
    
        $selecteds=[];
        if(strlen($sel[0]->servicios)>0)
            $selecteds=json_decode($sel[0]->servicios,true);
        $this->servicios_seleccionados=$selecteds;
        //Log::info($selecteds);
        foreach($this->servicios_packs as $key=>$xxx){
            $this->servicios_packs[$key]['selected']=false;
            if (in_array($xxx['value'], $selecteds)) {
                $this->servicios_packs[$key]['selected']=true;
            }
        }
        //Log: :info($selecteds);
        //Log: :info($this->servicios_packs);
        $servicios_packs=json_encode($this->servicios_packs);
        $this->dispatch('vselectsetvalue2', ['idcli' => $sel[0]->cliente_id]);
        $this->dispatch('populatechoices', ['choices' => $servicios_packs]);

        $this->dispatch('vselectsetvalue', ['idcli' => $sel[0]->cliente_id]);
        $this->dispatch('showmodal', []);
    }

    public function actualizarevento(){
        if(empty($this->servicios_seleccionados)){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'No ha seleccionado ningún servicio.',  'title' => 'ATENCIÓN']);
            return;
        }
        $this->validate();
        $title=($this->multi['title']);
        $inicio=($this->multi['datestart']);
        $clienteid=($this->multi['cliente_id']);
        $idid=($this->multi['id']);
        $lastmodloaded=($this->multi['updated_at']);
        $this->dispatch('closemodalmulti', ['id' => '']);
        DB::connection()->getPdo()->exec("lock tables calendario write");
        $lastmodactual=Calendario::where('id',$idid)->select('updated_at')->get()[0]['updated_at'];
        if($lastmodloaded!=$lastmodactual && !$lastmodloaded==""){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => "Lo siento esta cita ha cambiado mientras la movía, no se modifica, inténtelo de nuevo.",  'title' => 'ATENCIÓN']);
            DB::connection()->getPdo()->exec("unlock tables");
            return redirect(route('edit-calendario',$this->calendarid))->with('status','Lo siento esta cita ha cambiado mientras la editaba, no se modifica, inténtelo de nuevo.');
        }
        $uat=Carbon::now();
        DB::table('calendario')->where('id',$idid)->update([
            'basecalendario_id'=>$this->calendarid,
            'title'=>$this->multi['title'],
            'cuerpo'=>'',
            'start'=>$this->multi['datestart']." ".$this->horario[0],
            'minutos'=>$this->multi['minutos'],
            'cliente_id'=>$this->multi['cliente_id'],
            'updated_at'=>$uat,
            'nodisponible'=>$this->multi['nodisponible'],
            'reservado'=>$this->multi['reservado'],
            'confirmado'=>$this->multi['confirmado'],
            'pagado'=>$this->multi['pagado'],
            'importepagado'=>$this->multi['importepagado'],
            'cliente_id'=>$this->multi['cliente_id'],
            'servicios'=>json_encode($this->servicios_seleccionados),
            'servicios2'=>json_encode($this->servicios_seleccionados),
        ]);
        DB::connection()->getPdo()->exec("unlock tables");
        //$this->dispatch('modevent', ['ob' => $this->multi]);
        $this->cancelarcalcularsesiones(); // asigna public $multi
        $this->cargardatos();
        $this->dispatch('redrawallevents', ['eventos' => $this->eventosjson]);
        $this->busqueda();
    }    

}
