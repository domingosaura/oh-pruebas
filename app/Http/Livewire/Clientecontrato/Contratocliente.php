<?php

namespace App\Http\Livewire\Clientecontrato;

use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\User;
use App\Models\User2;
use App\Models\Binarioscontrato;
use Livewire\Component;
use File;
use App\Http\Utils;
use DB;
use URL;
use Log;
//use Livewire\WithFileUploads;
//use Image;
use Storage;
use Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class Contratocliente extends Component
{

    public Contrato $ficha;
    public Cliente $fichacli;
    public $userid=0;
    public $idcontrato=0;
    public $seccion=1;
    public $md5="";
    public $logo="";
    public $empresa="";
    public $emailcliente="";
    public $error="";
    public $ficha1;
    public $ficha2;
    public $contrato="";
    public $fallofirma="";
    public $vista="livewire.clientecontrato.contratocliente";
    public $check1=false;
    public $check2=false;
    public $check3=false;
    public $check4=false;
    public $check5=false;
    public $check6=false;
    public $check7=false;
    public $check8=false;
    public $check9=false;
    public $check10=false;
    public $check11=false;
    public $check12=false;
    public $check13=false;
    public $check14=false;
    public $check15=false;
    public $checkop1=false;
    public $checkop2=false;
    public $checkop3=false;
    public $checkop4=false;
    public $checkop5=false;
    public $checkop6=false;
    public $checkop7=false;
    public $checkop8=false;
    public $checkop9=false;
    public $checkop10=false;
    public $checkop11=false;
    public $checkop12=false;
    public $checkop13=false;
    public $checkop14=false;
    public $checkop15=false;
    public $countcheck=0;
    public $countcheckop=0;
    public $firma="";
    public $esclientenuevo=false;
    public $errormail="";

    protected $listeners = [
        'savesign' => 'savesign',
        'firmar' => 'firmar',
    ];

    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'fichacli.nombre' => 'required|max:100',
            'fichacli.apellidos' => 'required|max:100',
            'fichacli.email' => 'required|email|max:200|unique:clientes,email,0,id,user_id,'.$this->userid,
            'fichacli.nif' => 'required',
            'fichacli.nombrepareja' => '',
            'fichacli.apellidospareja' => '',
            'fichacli.nifpareja' => '',
            'fichacli.hijo1' => '',
            'fichacli.edad1' => '',
            'fichacli.hijo2' => '',
            'fichacli.edad2' => '',
            'fichacli.hijo3' => '',
            'fichacli.edad3' => '',
            'fichacli.hijo4' => '',
            'fichacli.edad4' => '',
            'fichacli.hijo5' => '',
            'fichacli.edad5' => '',
            'fichacli.hijo6' => '',
            'fichacli.edad6' => '',
            'fichacli.domicilio' => '',
            'fichacli.cpostal' => '',
            'fichacli.poblacion' => '',
            'fichacli.provincia' => '',
            'fichacli.telefono' => 'required',
        ];
    }

    public function mount($idcontrato,$md5,$error=""){
        $this->error=$error;
        $this->idcontrato=$idcontrato;
        $this->md5=$md5;
        $conmd5=md5($this->idcontrato."ckecka");
        if($md5!=$conmd5){
            // fallamos
            //log: :info("falla md5 ".$conmd5);
            $this->vista="livewire.authentication.error.error404";
            return;
        }
        $regs = Contrato::where('id',$idcontrato)->count();
        if($regs==0){
            // fallamos
            Log::info("no encuentra");
            $this->vista="livewire.authentication.error.error404";
            return;
        }
        $this->ficha = Contrato::find($idcontrato);
        $this->ficha->firmado=$this->ficha->firmado==1?true:false;
        if($this->ficha->firmado){
            $this->vista="livewire.authentication.error.error404firmado";
            return;
        }
        $this->contrato=$this->ficha->texto;
        $this->userid=$this->ficha->user_id;
        $cliente = Cliente::find($this->ficha->cliente_id);

        if(!$cliente){
            $cliente=new Cliente;
            $this->esclientenuevo=true;
        }
        $this->emailcliente=$cliente->email;
        $this->fichacli=$cliente;
        if($error=="xxaa"){
            $this->error='';
        }

        //$x = User2::select('logo','nombre','iban')->find($this->ficha->user_id);
        $this->ficha1 = User::find($this->ficha->user_id);
        $this->ficha2 = User2::find($this->ficha->user_id);
        $this->logo=$this->ficha2->logo;
        $this->empresa=$this->ficha2->nombre;
        $this->reemplazos();
    }

    public function updatecli(){
        //log: :info($this->fichacli);
        //return;
        //log: :info($this->validate());
        //log: :info("pasa");
        $this->fichacli->edad1=strlen($this->fichacli->edad1)==10?$this->fichacli->edad1:null;
        $this->fichacli->edad2=strlen($this->fichacli->edad2)==10?$this->fichacli->edad2:null;
        $this->fichacli->edad3=strlen($this->fichacli->edad3)==10?$this->fichacli->edad3:null;
        $this->fichacli->edad4=strlen($this->fichacli->edad4)==10?$this->fichacli->edad4:null;
        $this->fichacli->edad5=strlen($this->fichacli->edad5)==10?$this->fichacli->edad5:null;
        $this->fichacli->edad6=strlen($this->fichacli->edad6)==10?$this->fichacli->edad6:null;



        if($this->esclientenuevo){
            $this->fichacli->nombrepareja=$this->fichacli->nombrepareja==null?"":$this->fichacli->nombrepareja;
            $this->fichacli->apellidospareja=$this->fichacli->apellidospareja==null?"":$this->fichacli->apellidospareja;
            $this->fichacli->nifpareja=$this->fichacli->nifpareja==null?"":$this->fichacli->nifpareja;
            $this->fichacli->hijo1=$this->fichacli->hijo1==null?"":$this->fichacli->hijo1;
            //$this->fichacli->edad1=$this->fichacli->edad1==null?"":$this->fichacli->edad1;
            $this->fichacli->hijo2=$this->fichacli->hijo2==null?"":$this->fichacli->hijo2;
            //$this->fichacli->edad2=$this->fichacli->edad2==null?"":$this->fichacli->edad2;
            $this->fichacli->hijo3=$this->fichacli->hijo3==null?"":$this->fichacli->hijo3;
            //$this->fichacli->edad3=$this->fichacli->edad3==null?"":$this->fichacli->edad3;
            $this->fichacli->hijo4=$this->fichacli->hijo4==null?"":$this->fichacli->hijo4;
            //$this->fichacli->edad4=$this->fichacli->edad4==null?"":$this->fichacli->edad4;
            $this->fichacli->hijo5=$this->fichacli->hijo5==null?"":$this->fichacli->hijo5;
            //$this->fichacli->edad5=$this->fichacli->edad5==null?"":$this->fichacli->edad5;
            $this->fichacli->hijo6=$this->fichacli->hijo6==null?"":$this->fichacli->hijo6;
            //$this->fichacli->edad6=$this->fichacli->edad6==null?"":$this->fichacli->edad6;
            $this->fichacli->domicilio=$this->fichacli->domicilio==null?"":$this->fichacli->domicilio;
            $this->fichacli->cpostal=$this->fichacli->cpostal==null?"":$this->fichacli->cpostal;
            $this->fichacli->poblacion=$this->fichacli->poblacion==null?"":$this->fichacli->poblacion;
            $this->fichacli->provincia=$this->fichacli->provincia==null?"":$this->fichacli->provincia;
            $this->fichacli->user_id=$this->userid;
            
            
            $this->validate();
            $x=Cliente::insertGetId($this->fichacli->toArray());
            $cliente = Cliente::find($x);
            $this->fichacli=$cliente;
            $this->fichacli->created_at=Carbon::now();
            $this->fichacli->updated_at=Carbon::now();
            $this->emailcliente=$this->fichacli->email;
            $this->esclientenuevo=false;
            Contrato::where('id',$this->idcontrato)->update(['cliente_id'=>$x]);
            $this->ficha->cliente_id=$x;
        }

        $this->fichacli->update();
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'se han actualizado sus datos de cliente',  'title' => 'ATENCIÓN']);
        $this->reemplazos();
    }

    public function firmar($firma){

        $regs = Contrato::where('id',$this->idcontrato)->where('firmado',0)->count();
        if($regs==0)
            return;


        if(strlen($firma)==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Firma vacía',  'title' => 'ATENCIÓN']);
            return;
        }
        
        
        if(strlen($this->fichacli->email)==0||strlen($this->fichacli->nombre)==0||strlen($this->fichacli->apellidos)==0||strlen($this->fichacli->nif)==0||strlen($this->fichacli->telefono)==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Rellene los datos de su ficha de cliente',  'title' => 'ATENCIÓN']);
            return;
        }


        $image=str_replace("data:image/png;base64,","",$firma);
        $this->ficha->firma=$image;
        //$this->ficha->update();

        // checks obligatorios
        $this->fallofirma="";
        $ok=true;
        //$this->check1=true;
        if($this->check1==false && $this->countcheck>=1)
            $ok=false;
        if($this->check2==false && $this->countcheck>=2)
            $ok=false;
        if($this->check3==false && $this->countcheck>=3)
            $ok=false;
        if($this->check4==false && $this->countcheck>=4)
            $ok=false;
        if($this->check5==false && $this->countcheck>=5)
            $ok=false;
        if($this->check6==false && $this->countcheck>=6)
            $ok=false;
        if($this->check7==false && $this->countcheck>=7)
            $ok=false;
        if($this->check8==false && $this->countcheck>=8)
            $ok=false;
        if($this->check9==false && $this->countcheck>=9)
            $ok=false;
        if($this->check10==false && $this->countcheck>=10)
            $ok=false;
        if($this->check11==false && $this->countcheck>=11)
            $ok=false;
        if($this->check12==false && $this->countcheck>=12)
            $ok=false;
        if($this->check13==false && $this->countcheck>=13)
            $ok=false;
        if($this->check14==false && $this->countcheck>=14)
            $ok=false;
        if($this->check15==false && $this->countcheck>=15)
            $ok=false;
        if($ok==false){
            $this->fallofirma="Por favor, rellene todas las casillas de marca obligatorias";
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'tiene que rellenar todos las casillas de marca obligatorias',  'title' => 'ATENCIÓN']);
            //log: :info("fallo firma");
            return;
        }
        
        //Utils::vacialog();
        //log: :info("firmamos");
        
        $contrato=$this->contrato;
        $contrato=str_replace(" type='checkbox' class='check-fijo'"," type='checkbox' checked class='check-fijo'",$contrato);
        if($this->checkop1==true && $this->countcheckop>=1)
            $contrato=str_replace(" wire:model='checkop1' type='checkbox' class='check-opcional'"," wire:model='checkop1' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop2==true && $this->countcheckop>=2)
            $contrato=str_replace(" wire:model='checkop2' type='checkbox' class='check-opcional'"," wire:model='checkop2' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop3==true && $this->countcheckop>=3)
            $contrato=str_replace(" wire:model='checkop3' type='checkbox' class='check-opcional'"," wire:model='checkop3' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop4==true && $this->countcheckop>=4)
            $contrato=str_replace(" wire:model='checkop4' type='checkbox' class='check-opcional'"," wire:model='checkop4' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop5==true && $this->countcheckop>=5)
            $contrato=str_replace(" wire:model='checkop5' type='checkbox' class='check-opcional'"," wire:model='checkop5' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop6==true && $this->countcheckop>=6)
            $contrato=str_replace(" wire:model='checkop6' type='checkbox' class='check-opcional'"," wire:model='checkop6' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop7==true && $this->countcheckop>=7)
            $contrato=str_replace(" wire:model='checkop7' type='checkbox' class='check-opcional'"," wire:model='checkop7' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop8==true && $this->countcheckop>=8)
            $contrato=str_replace(" wire:model='checkop8' type='checkbox' class='check-opcional'"," wire:model='checkop8' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop9==true && $this->countcheckop>=9)
            $contrato=str_replace(" wire:model='checkop9' type='checkbox' class='check-opcional'"," wire:model='checkop9' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop10==true && $this->countcheckop>=10)
            $contrato=str_replace(" wire:model='checkop10' type='checkbox' class='check-opcional'"," wire:model='checkop10' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop11==true && $this->countcheckop>=11)
            $contrato=str_replace(" wire:model='checkop11' type='checkbox' class='check-opcional'"," wire:model='checkop11' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop12==true && $this->countcheckop>=12)
            $contrato=str_replace(" wire:model='checkop12' type='checkbox' class='check-opcional'"," wire:model='checkop12' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop13==true && $this->countcheckop>=13)
            $contrato=str_replace(" wire:model='checkop13' type='checkbox' class='check-opcional'"," wire:model='checkop13' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop14==true && $this->countcheckop>=14)
            $contrato=str_replace(" wire:model='checkop14' type='checkbox' class='check-opcional'"," wire:model='checkop14' checked type='checkbox' class='check-opcional'",$contrato);
        if($this->checkop15==true && $this->countcheckop>=15)
            $contrato=str_replace(" wire:model='checkop15' type='checkbox' class='check-opcional'"," wire:model='checkop15' checked type='checkbox' class='check-opcional'",$contrato);
        //Utils::vacialog();
        //log: :info($contrato);

        $x = User2::select('logo','nombre','iban','firma')->find($this->ficha->user_id);
        $logo=$x->logo;
        $empresa=$x->nombre;
        $firmaempresa=$x->firma;
        $x=Cliente::where('user_id',$this->ficha->user_id)->where('id',$this->ficha->cliente_id)->limit(1)->get();
        $clienteemail=$x[0]->email;
        $clientenombre=$x[0]->nombre." ".$x[0]->apellidos;

        $pdf = Pdf::loadView('email.mailcontratoclientepdf', [
            'contrato' => $contrato,
            'firmacli'=>$this->ficha->firma,
            'firmaemp'=>$firmaempresa,
            'nombrecli'=>$clientenombre,
            'nombreemp'=>$empresa,
        ]);
        //return $pdf->download();
        //return $pdf->stream(); 
        $pdf->render();
        $output = $pdf->output();
        //file_put_contents($filePath, $output);
        //Utils::vacialog();
        //log: :info($output);

        //return response()->streamDownload(function () use ($pdf) {
        //    echo $pdf->stream();
        //}, 'name.pdf');

        $storage_path = storage_path('app/contratos')."/";
        $filee=$storage_path.'contrato'.$this->idcontrato.'.pdf';
        file_put_contents($filee,$output);
        
        $direcciones = [];
        $direcciones[]=['address'=>$clienteemail,'name'=>$clientenombre];
        $direcciones[]=['address'=>$this->ficha1->email,'name'=>$this->ficha2->nombre." ".$this->ficha2->apellidos];
        $asunto="Contrato ".$empresa;
        $vista = "email.mailcontratocliente";

        $ok = true;
        $datos=[
            'logo'=>$logo,
            'empresa'=>$empresa,
        ];
        $reply=Utils::cargarconfiguracionemailempresa($this->ficha->user_id);
        $this->errormail="";
        try {
            $body = view($vista,['datos' => $datos])->render();
            $bodyfull=Utils::emailtocid($body);
            Mail::html($bodyfull['body'], function ($message) use ($direcciones,$asunto,$reply,$filee,$bodyfull) {
                $message->to($direcciones)->subject($asunto)->replyTo([$reply])->attach($filee);
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
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'error al enviar mail',  'title' => 'ATENCIÓN']);
            $this->fallofirma="Error al enviar mail con el contrato adjunto, reintente.";
            return;
        }

        unlink($filee);
        
        $cip=Request::getClientIp();
        $this->ficha->firmado=true;
        $this->ficha->ipfirma=$cip;
        $this->ficha->dtfirma=Carbon::now();
        $this->ficha->update();

        Binarioscontrato::where('contrato_id',$this->idcontrato)->delete();
        Binarioscontrato::insert([
            'contrato_id'=>$this->idcontrato,
            'binario'=>$output,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        $this->fallofirma="Contrato firmado. Recibirá una copia por correo electrónico.";
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Contrato firmado. Recibirá una copia por correo electrónico.',  'title' => 'ATENCIÓN']);
    }

    public function reemplazos()
    {
        $this->contrato=$this->ficha->texto;
        $texto=$this->ficha->texto;

        $this->countcheck=substr_count($texto, '>check<');
        $this->countcheckop=substr_count($texto, '>check-opcional<');
    
        // esto se hará al presentar el contrato al cliente
        $texto=str_replace("border-radius: 5px;","",$texto);
        $texto=str_replace("text-align: center;","",$texto);
        $texto=str_replace("padding: 4px 8px;","",$texto);
        $texto=str_replace("color: white;","",$texto);
        $texto=str_replace("background-color: orange;","",$texto);
        //
        $s='<span style="    ">nombreempresa</span>';
        $texto=str_replace($s,$this->ficha2->nombre,$texto);
        $s='<span style="    ">nombreempresa2</span>';
        $texto=str_replace($s,$this->ficha2->nombre2,$texto);
        $s='<span style="    ">nifempresa</span>';
        $texto=str_replace($s,$this->ficha2->nif,$texto);
        $s='<span style="    ">domicilioempresa</span>';
        $texto=str_replace($s,$this->ficha2->domicilio,$texto);
        $s='<span style="    ">cpempresa</span>';
        $texto=str_replace($s,$this->ficha2->codigopostal,$texto);
        $s='<span style="    ">poblacionempresa</span>';
        $texto=str_replace($s,$this->ficha2->poblacion,$texto);
        $s='<span style="    ">provinciaempresa</span>';
        $texto=str_replace($s,$this->ficha2->provincia,$texto);
        $s='<span style="    ">telefonoempresa</span>';
        $texto=str_replace($s,$this->ficha2->telefono,$texto);
        $s='<span style="    ">emailempresa</span>';
        $texto=str_replace($s,$this->ficha1->email,$texto);
        $s='<span style="    ">nombrecliente</span>';
        $texto=str_replace($s,$this->fichacli->nombre." ".$this->fichacli->apellidos,$texto);
        $s='<span style="    ">nifcliente</span>';
        $texto=str_replace($s,$this->fichacli->nif,$texto);
        $s='<span style="    ">domiciliocliente</span>';
        $texto=str_replace($s,$this->fichacli->domicilio,$texto);
        $s='<span style="    ">cpcliente</span>';
        $texto=str_replace($s,$this->fichacli->cpostal,$texto);
        $s='<span style="    ">poblacioncliente</span>';
        $texto=str_replace($s,$this->fichacli->poblacion,$texto);
        $s='<span style="    ">provinciacliente</span>';
        $texto=str_replace($s,$this->fichacli->provincia,$texto);
        $s='<span style="    ">telefonocliente</span>';
        $texto=str_replace($s,$this->fichacli->telefono,$texto);
        $s='<span style="    ">emailcliente</span>';
        $texto=str_replace($s,$this->fichacli->email,$texto);
        $s='<span style="    ">nombrepareja</span>';
        $texto=str_replace($s,$this->fichacli->nombrepareja." ".$this->fichacli->apellidospareja,$texto);
        $s='<span style="    ">nifpareja</span>';
        $texto=str_replace($s,$this->fichacli->nifpareja,$texto);
        $s='<span style="    ">hijo1</span>';
        $texto=str_replace($s,$this->fichacli->hijo1,$texto);
        $s='<span style="    ">hijo2</span>';
        $texto=str_replace($s,$this->fichacli->hijo2,$texto);
        $s='<span style="    ">hijo3</span>';
        $texto=str_replace($s,$this->fichacli->hijo3,$texto);
        $s='<span style="    ">hijo4</span>';
        $texto=str_replace($s,$this->fichacli->hijo4,$texto);
        $s='<span style="    ">hijo5</span>';
        $texto=str_replace($s,$this->fichacli->hijo5,$texto);
        $s='<span style="    ">hijo6</span>';
        $texto=str_replace($s,$this->fichacli->hijo6,$texto);
        $s='<span style="    ">fechacontrato</span>';
        $texto=str_replace($s,date('d/m/Y'),$texto);
        //
        $s='<strong style="    ">nombreempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->nombre.'</strong>',$texto);
        $s='<strong style="    ">nombreempresa2</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->nombre2.'</strong>',$texto);
        $s='<strong style="    ">nifempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->nif.'</strong>',$texto);
        $s='<strong style="    ">domicilioempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->domicilio.'</strong>',$texto);
        $s='<strong style="    ">cpempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->codigopostal.'</strong>',$texto);
        $s='<strong style="    ">poblacionempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->poblacion.'</strong>',$texto);
        $s='<strong style="    ">provinciaempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->provincia.'</strong>',$texto);
        $s='<strong style="    ">telefonoempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha2->telefono.'</strong>',$texto);
        $s='<strong style="    ">emailempresa</strong>';
        $texto=str_replace($s,'<strong>'.$this->ficha1->email.'</strong>',$texto);
        $s='<strong style="    ">nombrecliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->nombre." ".$this->fichacli->apellidos.'</strong>',$texto);
        $s='<strong style="    ">nifcliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->nif.'</strong>',$texto);
        $s='<strong style="    ">domiciliocliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->domicilio.'</strong>',$texto);
        $s='<strong style="    ">cpcliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->cpostal.'</strong>',$texto);
        $s='<strong style="    ">poblacioncliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->poblacion.'</strong>',$texto);
        $s='<strong style="    ">provinciacliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->provincia.'</strong>',$texto);
        $s='<strong style="    ">telefonocliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->telefono.'</strong>',$texto);
        $s='<strong style="    ">emailcliente</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->email.'</strong>',$texto);
        $s='<strong style="    ">nombrepareja</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->nombrepareja." ".$this->fichacli->apellidospareja.'</strong>',$texto);
        $s='<strong style="    ">nifpareja</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->nifpareja.'</strong>',$texto);
        $s='<strong style="    ">hijo1</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->hijo1.'</strong>',$texto);
        $s='<strong style="    ">hijo2</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->hijo2.'</strong>',$texto);
        $s='<strong style="    ">hijo3</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->hijo3.'</strong>',$texto);
        $s='<strong style="    ">hijo4</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->hijo4.'</strong>',$texto);
        $s='<strong style="    ">hijo5</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->hijo5.'</strong>',$texto);
        $s='<strong style="    ">hijo6</strong>';
        $texto=str_replace($s,'<strong>'.$this->fichacli->hijo6.'</strong>',$texto);
        $s='<strong style="    ">fechacontrato</strong>';
        $texto=str_replace($s,date('d/m/Y'),$texto);

        $s='<span style="    ">check</span>';
        for($alfa=1;$alfa<=15;$alfa++){
            $texto=Utils::str_replace_first($s,"<input wire:model='check".$alfa."' type='checkbox' class='check-fijo' style='width: 20px;height: 20px;'>&nbsp;",$texto);
        }
        
        
        $s='<span style="    ">check-opcional</span>';
        for($alfa=1;$alfa<=15;$alfa++){
            $texto=Utils::str_replace_first($s,"<input wire:model='checkop".$alfa."' type='checkbox' class='check-opcional' style='width: 20px;height: 20px;'>&nbsp;",$texto);
        }        

        $this->contrato=$texto;
        //$this->dispatch('refreshquill', ['ob' => $this->ficha->texto]);
    }

    public function vseccion($num)
    {
        $this->seccion=$num;
        $this->dispatch('$refresh');
    }

    public function alertpago(){
        $this->dispatch('mensaje',['type' => 'error',  'message' => 'esta galería ya se ha pagado y no se puede modificar',  'title' => 'ATENCIÓN']);
        //$this->dispatch('alerta',['type' => 'success',  'message' => 'esta galería no se puede modificar',  'title' => 'ATENCIÓN']);
    }

    public function render()
    {
        return view($this->vista);
    }
}
