<?php

namespace App\Http\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\User2;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Utils;
use Illuminate\Support\Facades\Mail;
use Log;

class Edit extends Component
{

    public Cliente $ficha;
    public $singular='';
    public $plural='';
    public $userid;
    public $logo;
    public $empresa;
    public $errormail="";
    use AuthorizesRequests;
    
    protected function rules(){

        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]

        return [
            'ficha.nombre' => 'required|max:100',
            'ficha.apellidos' => 'required|max:100',
            'ficha.email' => 'required|email|max:200|unique:clientes,email,'.$this->ficha->id.',id,user_id,'.$this->userid,
            'ficha.activo' => '',
            'ficha.permiteimagenes' => '',
            'ficha.permitecomunicaciones' => '',
            //'ficha.nif' => 'required|max:15|unique:clientes,nif,'.$this->ficha->id.',id,user_id,'.$this->userid,
            'ficha.nif' => '',
            'ficha.nombrepareja' => '',
            'ficha.notasinternas' => '',
            'ficha.apellidospareja' => '',
            'ficha.nifpareja' => '',
            'ficha.hijo1' => '',
            'ficha.edad1' => '',
            'ficha.hijo2' => '',
            'ficha.edad2' => '',
            'ficha.hijo3' => '',
            'ficha.edad3' => '',
            'ficha.hijo4' => '',
            'ficha.edad4' => '',
            'ficha.hijo5' => '',
            'ficha.edad5' => '',
            'ficha.hijo6' => '',
            'ficha.edad6' => '',
            'ficha.domicilio' => '',
            'ficha.cpostal' => '',
            'ficha.poblacion' => '',
            'ficha.provincia' => '',
            'ficha.telefono' => '',
        ];
    }

    public function mount($id){
        $this->userid=Auth::id();
        $this->ficha = Cliente::where('user_id',$this->userid)->find($id);
        $this->ficha->activo=$this->ficha->activo==1?true:false;
        $this->ficha->permiteimagenes=$this->ficha->permiteimagenes==1?true:false;
        $this->ficha->permitecomunicaciones=$this->ficha->permitecomunicaciones==1?true:false;
        $this->singular="cliente";
        $this->plural="Clientes";
        $xuser = User::find($this->userid);
        $xuser2 = User2::find($this->userid);
        $this->logo=$xuser2->logo;
        $this->empresa=$xuser2->nombre;
    }

    public function update(){
        
        $this->validate();

        $this->ficha->edad1=strlen($this->ficha->edad1)==10?$this->ficha->edad1:null;
        $this->ficha->edad2=strlen($this->ficha->edad2)==10?$this->ficha->edad2:null;
        $this->ficha->edad3=strlen($this->ficha->edad3)==10?$this->ficha->edad3:null;
        $this->ficha->edad4=strlen($this->ficha->edad4)==10?$this->ficha->edad4:null;
        $this->ficha->edad5=strlen($this->ficha->edad5)==10?$this->ficha->edad5:null;
        $this->ficha->edad6=strlen($this->ficha->edad6)==10?$this->ficha->edad6:null;


        $this->ficha->update();

        return redirect(route('cliente-management'))->with('status',$this->singular.' actualizado.');
    }

    public function sendclientwhatsapp(){
        $datee = Utils::left(Carbon::now(),10); // enlace solo para hoy y mañana
        $datee1 = Utils::left(Carbon::now()->add(7, 'day'),10); // enlace solo para hoy y mañana
        $ruta=route('rellenarporcliente',[$this->userid,$this->ficha->id,md5($this->ficha->id.$datee."dattacli")]);

        $ruta=str_replace('nonohttps://','',$ruta); // disable preview image
        //$ruta=str_replace('https://www.','www.',$ruta); // disable preview image
        //$ruta=str_replace('https://','www.',$ruta); // disable preview image

        
        $clientetelefono=$this->ficha->telefono;
        if(!$clientetelefono){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Exxxl cliente no tiene teléfono',  'title' => 'ATENCIÓN']);
            //return;
        }
        if(strlen($clientetelefono)==9)
            $clientetelefono="34".$clientetelefono;
        //$enlace="https://wa.me/$clientetelefono?text=".urlencode($this->empresa.' - completar datos de cliente - '.$ruta);
        $enlace="https://api.whatsapp.com/send/?phone=$clientetelefono&text=".urlencode($this->empresa.' - completar datos de cliente - '.$ruta);
        //$this->dispatch('mensaje',['type' => 'success',  'message' => $enlace,  'title' => 'ATENCIÓN']);
        $this->dispatch('openwhatsapp', ['id' => $enlace]);
    }

    public function sendclient(){
        $datee = Utils::left(Carbon::now(),10); // enlace solo para hoy y mañana
        $datee1 = Utils::left(Carbon::now()->add(7, 'day'),10); // enlace solo para hoy y mañana
        $ruta=route('rellenarporcliente',[$this->userid,$this->ficha->id,md5($this->ficha->id.$datee."dattacli")]);
        $clienteemail=$this->ficha->email;
        $clientenombre=$this->ficha->nombre." ".$this->ficha->apellidos;
        if(!$clienteemail){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'El cliente no tiene dirección de correo',  'title' => 'ATENCIÓN']);
            return;
        }
        $direcciones = [];
        $direcciones[]=['address'=>$clienteemail,'name'=>$clientenombre];
        $asunto="Confirmación de datos de cliente en ".$this->empresa;
        $vista = "email.solicitardatoscliente";

        $ok = true;
        $datos=[
            'ruta'=>$ruta,
            'logo'=>$this->logo,
            'empresa'=>$this->empresa,
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
        if($ok){
            $this->dispatch('mensaje',['type' => 'success',  'message' => 'enlace enviado',  'title' => 'ATENCIÓN']);
        }
        if(!$ok){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al enviar mail',  'title' => 'ATENCIÓN']);
        }
    }



    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.cliente.edit');
    }
}
