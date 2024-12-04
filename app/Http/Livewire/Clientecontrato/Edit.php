<?php

namespace App\Http\Livewire\Clientecontrato;

use App\Models\User;
use App\Models\User2;
use App\Models\Contrato;
use App\Models\Pcontrato;
use App\Models\Cliente;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use File;
use App\Http\Utils;
use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\Binarioscontrato;
use Response;

class Edit extends Component
{

    public Contrato $ficha;
    public $idmov = 0;
    public $userid;
    public $clientes;
    public $plantillas;
    public $firmado=false;
    public $enviado=false;
    public $conmd5="";
    public $contrato;
    public $newnombre="";
    public $newapellidos="";
    public $newdni="";
    public $newemail="";
    public $newtelefono="";
    public $newtext="";
    public $ruta="";
    public $errormail="";
    use AuthorizesRequests;
    
    protected function rules(){

        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]

        return [
            'ficha.nombre' => 'required|max:250',
            'ficha.texto' => '',
            'ficha.cliente_id' => '',
            'ficha.galeria_id' => '',
        ];
    }

    public function mount($id){
        $this->userid=Auth::id();
        $this->idmov=$id;
        $this->conmd5=md5($this->idmov."ckecka");
        $this->ficha = Contrato::where('user_id',$this->userid)->find($id);
        //log: :info($this->ficha);
        $this->firmado=$this->ficha->firmado;
        $this->enviado=$this->ficha->enviado;

        $this->ruta=route('contratocliente',[$this->idmov,$this->conmd5]);

        $clientes=DB::table("clientes")
        ->select(DB::raw("concat(nombre,' ',apellidos,' ',telefono,' ',email,' ',nif) as label"),'id as value')
        ->where('user_id',$this->userid)
        ->orderBy('nombre','asc')
        ->get();
        $clientes=Utils::objectToArray($clientes);
        $this->clientes=json_encode($clientes);
        //$this->dispatch('vselectsetvalue', ['idcli' => $this->ficha->cliente_id]);
        //
        $this->plantillas = Pcontrato::select('id','nombre','texto')->where('user_id',$this->userid)->orderBy('nombre','asc')->get()->toArray();
        $this->contrato="";
        if($this->firmado){
            //$x=Binarioscontrato::where('contrato_id',$this->idmov)->select('binario')->get();
            //$this->contrato="data:application/pdf;base64,".base64_encode($x[0]->binario);
        }
        if($this->enviado){
        }
        //log: :info($this->ficha);
    }
    
    public function seepdf(){
        $x=Binarioscontrato::where('contrato_id',$this->idmov)->select('binario')->get();
        $contrato=$x[0]->binario;
        $storage_path = storage_path('app/contratos')."/";
        $filee=$storage_path.'contrato'.$this->idmov.'.pdf';
        file_put_contents($filee,$contrato);
        return response()->file($filee, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="contrato.pdf"'
        ]);
        unlink($filee);
    }

    public function variable($tip){
        $s=Utils::variablecontrato($tip);
        $this->dispatch('addtoquill', ['ob' => $s]);
    }

    public function sendclientwhatsapp(){
        if($this->ficha->cliente_id<=0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => '¡es necesario seleccionar un cliente!',  'title' => 'ATENCIÓN']);
            return;
        }
        $ruta=route('contratocliente',[$this->idmov,$this->conmd5]);

        $ruta=str_replace('nonohttps://','',$ruta); // disable preview image
        //$ruta=str_replace('https://www.','www.',$ruta); // disable preview image
        //$ruta=str_replace('https://','www.',$ruta); // disable preview image

        
        $x = User2::select('logo','nombre','iban')->find($this->userid);
        $empresa=$x->nombre;
        $x=Cliente::where('user_id',$this->userid)->where('id',$this->ficha->cliente_id)->limit(1)->get();
        $clientetelefono=$x[0]->telefono;
        if(!$clientetelefono){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Exxxxl cliente no tiene teléfono',  'title' => 'ATENCIÓN']);
            //return;
        }
        if(strlen($clientetelefono)==9)
            $clientetelefono="34".$clientetelefono;
        $this->ficha->enviado=true;
        $this->enviado=true;
        $this->ficha->dtenvio=Carbon::now();
        $this->ficha->update();
        //$enlace="https://wa.me/$clientetelefono?text=".urlencode($empresa.' - Firma de contrato - '.$ruta);
        $enlace="https://api.whatsapp.com/send/?phone=$clientetelefono&text=".urlencode($empresa.' - Firma de contrato - '.$ruta);
        $this->dispatch('openwhatsapp', ['id' => $enlace]);
    }

    public function sendclient(){
        if($this->ficha->cliente_id<=0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => '¡es necesario seleccionar un cliente!',  'title' => 'ATENCIÓN']);
            return;
        }
        $this->ficha->enviado=true;
        $this->enviado=true;
        $this->ficha->dtenvio=Carbon::now();
        $this->ficha->update();
        $ruta=route('contratocliente',[$this->idmov,$this->conmd5]);
        $x = User2::select('logo','nombre','iban')->find($this->userid);
        $logo=$x->logo;
        $empresa=$x->nombre;
        $x=Cliente::where('user_id',$this->userid)->where('id',$this->ficha->cliente_id)->limit(1)->get();
        $clienteemail=$x[0]->email;
        $clientenombre=$x[0]->nombre." ".$x[0]->apellidos;
        $direcciones = [];
        $direcciones[]=['address'=>$clienteemail,'name'=>$clientenombre];
        $asunto="Contrato de cliente en ".$empresa;
        $vista = "email.sendtoclientcontrato";
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
        if($ok){
            $this->dispatch('mensaje',['type' => 'success',  'message' => 'enlace enviado',  'title' => 'ATENCIÓN']);
        }
        if(!$ok){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al enviar mail',  'title' => 'ATENCIÓN']);
        }
    }
    
    public function update(){
        $this->validate();
        $this->ficha->update();
        $this->dispatch('mensajecorto',['type' => 'success',  'message' => 'datos actualizados',  'title' => 'ATENCIÓN']);
    }

    public function updateout(){
        $this->validate();
        $this->ficha->update();
        return redirect(route('clientecontrato-management'))->with('status','contrato actualizado.');
    }

    public function setidcliente($idid)
    {
        $this->ficha->cliente_id=$idid;
        $this->ficha->update();
    }
    public function selectplantilla($keyy)
    {
        $ficha1 = User::where('id',$this->userid)->find($this->userid);
        $ficha2 = User2::where('id',$this->userid)->find($this->userid);
        $ficha3 = Cliente::find($this->ficha->cliente_id);
        //
        $texto=$this->plantillas[$keyy]['texto'];

        $this->ficha->texto=$texto;
        $this->dispatch('refreshquill', ['ob' => $this->ficha->texto]);
        return;

        // esto se hará al presentar el contrato al cliente
        $texto=str_replace("border-radius: 5px;","",$texto);
        $texto=str_replace("text-align: center;","",$texto);
        $texto=str_replace("padding: 4px 8px;","",$texto);
        $texto=str_replace("color: white;","",$texto);
        $texto=str_replace("background-color: orange;","",$texto);
        //
        $s='<span style="    ">nombreempresa</span>';
        $texto=str_replace($s,$ficha2->nombre,$texto);
        $s='<span style="    ">nombreempresa2</span>';
        $texto=str_replace($s,$ficha2->nombre2,$texto);
        $s='<span style="    ">nifempresa</span>';
        $texto=str_replace($s,$ficha2->nif,$texto);
        $s='<span style="    ">domicilioempresa</span>';
        $texto=str_replace($s,$ficha2->domicilio,$texto);
        $s='<span style="    ">cpempresa</span>';
        $texto=str_replace($s,$ficha2->codigopostal,$texto);
        $s='<span style="    ">poblacionempresa</span>';
        $texto=str_replace($s,$ficha2->poblacion,$texto);
        $s='<span style="    ">provinciaempresa</span>';
        $texto=str_replace($s,$ficha2->provincia,$texto);
        $s='<span style="    ">telefonoempresa</span>';
        $texto=str_replace($s,$ficha2->telefono,$texto);
        $s='<span style="    ">emailempresa</span>';
        $texto=str_replace($s,$ficha1->email,$texto);
        $s='<span style="    ">nombrecliente</span>';
        $texto=str_replace($s,$ficha3->nombre." ".$ficha3->apellidos,$texto);
        $s='<span style="    ">nifcliente</span>';
        $texto=str_replace($s,$ficha3->nif,$texto);
        $s='<span style="    ">domiciliocliente</span>';
        $texto=str_replace($s,$ficha3->domicilio,$texto);
        $s='<span style="    ">cpcliente</span>';
        $texto=str_replace($s,$ficha3->cpostal,$texto);
        $s='<span style="    ">poblacioncliente</span>';
        $texto=str_replace($s,$ficha3->poblacion,$texto);
        $s='<span style="    ">provinciacliente</span>';
        $texto=str_replace($s,$ficha3->provincia,$texto);
        $s='<span style="    ">telefonocliente</span>';
        $texto=str_replace($s,$ficha3->telefono,$texto);
        $s='<span style="    ">emailcliente</span>';
        $texto=str_replace($s,$ficha3->email,$texto);
        $s='<span style="    ">nombrepareja</span>';
        $texto=str_replace($s,$ficha3->nombrepareja." ".$ficha3->apellidospareja,$texto);
        $s='<span style="    ">nifpareja</span>';
        $texto=str_replace($s,$ficha3->nifpareja,$texto);
        $s='<span style="    ">hijo1</span>';
        $texto=str_replace($s,$ficha3->hijo1,$texto);
        $s='<span style="    ">hijo2</span>';
        $texto=str_replace($s,$ficha3->hijo2,$texto);
        $s='<span style="    ">hijo3</span>';
        $texto=str_replace($s,$ficha3->hijo3,$texto);
        $s='<span style="    ">hijo4</span>';
        $texto=str_replace($s,$ficha3->hijo4,$texto);
        $s='<span style="    ">hijo5</span>';
        $texto=str_replace($s,$ficha3->hijo5,$texto);
        $s='<span style="    ">hijo6</span>';
        $texto=str_replace($s,$ficha3->hijo6,$texto);
        $s='<span style="    ">check</span>';
        $texto=str_replace($s,"check",$texto);
        $s='<span style="    ">check-opcional</span>';
        $texto=str_replace($s,"check-opcional",$texto);
        $s='<span style="    ">fechacontrato</span>';
        $texto=str_replace($s,date('d/m/Y'),$texto);
        //
        $s='<strong style="    ">nombreempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->nombre.'</strong>',$texto);
        $s='<strong style="    ">nombreempresa2</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->nombre2.'</strong>',$texto);
        $s='<strong style="    ">nifempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->nif.'</strong>',$texto);
        $s='<strong style="    ">domicilioempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->domicilio.'</strong>',$texto);
        $s='<strong style="    ">cpempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->codigopostal.'</strong>',$texto);
        $s='<strong style="    ">poblacionempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->poblacion.'</strong>',$texto);
        $s='<strong style="    ">provinciaempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->provincia.'</strong>',$texto);
        $s='<strong style="    ">telefonoempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha2->telefono.'</strong>',$texto);
        $s='<strong style="    ">emailempresa</strong>';
        $texto=str_replace($s,'<strong>'.$ficha1->email.'</strong>',$texto);
        $s='<strong style="    ">nombrecliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->nombre." ".$ficha3->apellidos.'</strong>',$texto);
        $s='<strong style="    ">nifcliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->nif.'</strong>',$texto);
        $s='<strong style="    ">domiciliocliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->domicilio.'</strong>',$texto);
        $s='<strong style="    ">cpcliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->cpostal.'</strong>',$texto);
        $s='<strong style="    ">poblacioncliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->poblacion.'</strong>',$texto);
        $s='<strong style="    ">provinciacliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->provincia.'</strong>',$texto);
        $s='<strong style="    ">telefonocliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->telefono.'</strong>',$texto);
        $s='<strong style="    ">emailcliente</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->email.'</strong>',$texto);
        $s='<strong style="    ">nombrepareja</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->nombrepareja." ".$ficha3->apellidospareja.'</strong>',$texto);
        $s='<strong style="    ">nifpareja</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->nifpareja.'</strong>',$texto);
        $s='<strong style="    ">hijo1</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->hijo1.'</strong>',$texto);
        $s='<strong style="    ">hijo2</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->hijo2.'</strong>',$texto);
        $s='<strong style="    ">hijo3</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->hijo3.'</strong>',$texto);
        $s='<strong style="    ">hijo4</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->hijo4.'</strong>',$texto);
        $s='<strong style="    ">hijo5</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->hijo5.'</strong>',$texto);
        $s='<strong style="    ">hijo6</strong>';
        $texto=str_replace($s,'<strong>'.$ficha3->hijo6.'</strong>',$texto);
        $s='<strong style="    ">check</strong>';
        $texto=str_replace($s,"check",$texto);
        $s='<strong style="    ">check-opcional</strong>';
        $texto=str_replace($s,"check-opcional",$texto);
        $s='<strong style="    ">fechacontrato</strong>';
        $texto=str_replace($s,'<strong>'.date('d/m/Y').'</strong>',$texto);

        $s='<span style="    ">cuadrofirma</span>';
        $texto=str_replace($s,'<span style="border-radius:5px;text-align:center;padding:10px;color:green;background-color:lightgray;">espacio para la firma</span>',$texto);
        $this->ficha->texto=$texto;
        $this->dispatch('refreshquill', ['ob' => $this->ficha->texto]);
    }


    public function cancelarnuevocliente(){
        $this->newnombre="";
        $this->newapellidos="";
        $this->newdni="";
        $this->newemail="";
        $this->newtelefono="";
        $this->newtext="";
    }
    public function crearnuevocliente(){
        $nombre=$this->newnombre;
        $apellidos=$this->newapellidos;
        $dni=$this->newdni;
        $mail=$this->newemail;
        $tele=$this->newtelefono;
        if(strlen($mail)==0||strlen($nombre)==0){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Rellene al menos nombre y email',  'title' => 'ATENCIÓN']);
            $this->newtext="Rellene al menos nombre y email";
            return;
        }
        $totalclientes=Cliente::where('user_id',$this->userid)->where('email',$mail)->count();
        if($totalclientes>0){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'La dirección de email ya está en uso',  'title' => 'ATENCIÓN']);
            $this->newtext="La dirección de email ya está en uso";
            return;
        }
        $idid=Cliente::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>$nombre,
            'apellidos'=>$apellidos,
            'nif'=>$dni,
            'email'=>$mail,
            'telefono'=>$tele,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        $this->ficha->cliente_id=$idid;

        $clientes=DB::table("clientes")
        ->select(DB::raw("concat(nombre,' ',apellidos,' ',telefono,' ',email,' ',nif) as label"),'id as value')
        ->where('user_id',$this->userid)
        ->orderBy('nombre','asc')
        ->get();
        $clientes=Utils::objectToArray($clientes);
        $this->clientes=json_encode($clientes);

        $this->newnombre="";
        $this->newapellidos="";
        $this->newdni="";
        $this->newemail="";
        $this->newtelefono="";
        $this->newtext="";
        $this->dispatch('asignarcliente', ['id' => $idid]);
        $this->dispatch('closemodal', ['id' => '']);
    
    }


    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.clientecontrato.edit');
    }


}
