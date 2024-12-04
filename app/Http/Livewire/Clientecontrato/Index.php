<?php

namespace App\Http\Livewire\Clientecontrato;

use App\Models\Contrato;
use App\Models\Binarioscontrato;
use App\Models\Cliente;
use App\Models\User2;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Utils;
use Response;

class Index extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    public $tipo = 0;
    public $titulo = "";
    public $userid = 0;
    public $search = '';
    public $solopendiente = 1; // 1 todos 2 pte firma 3 firmado
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $errormail="";

    protected $queryString = ['sortField', 'sortDirection'];
    protected $paginationTheme = 'bootstrap';

    public function mount(){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $this->userid=Auth::id();
    }
    public function sortBy($field){
        if($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function seepdf($id){
        $x=Binarioscontrato::where('contrato_id',$id)->select('binario')->get();
        $contrato=$x[0]->binario;
        $storage_path = storage_path('app/contratos')."/";
        $filee=$storage_path.'contrato'.$id.'.pdf';
        file_put_contents($filee,$contrato);
        return response()->file($filee, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="contrato.pdf"'
        ]);
        unlink($filee);
    }

    public function seepdfinline($id){
        $x=Binarioscontrato::where('contrato_id',$id)->select('binario')->get();
        $contrato=$x[0]->binario;
        $storage_path = storage_path('app/contratos')."/";
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $filee=$storage_path.'contrato'.$id."_".md5($id.'ccli').'.pdf';
        $filee2='storage/tmpgallery/'.'contrato'.$id."_".md5($id.'ccli').'.pdf';
        file_put_contents($filee,$contrato);
        $this->dispatch('openpdf', ['id' => $filee2]);
    }

    public function sendclientwhatsapp($id){
        $contrato = Contrato::find($id);
        $ruta=route('contratocliente',[$id,md5($id."ckecka")]);

        $ruta=str_replace('nonohttps://','',$ruta); // disable preview image
        //$ruta=str_replace('https://www.','www.',$ruta); // disable preview image
        //$ruta=str_replace('https://','www.',$ruta); // disable preview image

        
        $x = User2::select('logo','nombre','iban')->find($this->userid);
        $empresa=$x->nombre;
        $x=Cliente::where('user_id',$this->userid)->where('id',$contrato->cliente_id)->limit(1)->get();
        $clientetelefono=$x[0]->telefono;
        if(!$clientetelefono){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Exxxxl cliente no tiene teléfono',  'title' => 'ATENCIÓN']);
            //return;
        }
        if(strlen($clientetelefono)==9)
            $clientetelefono="34".$clientetelefono;

        $contrato->enviado=true;
        $contrato->dtenvio=Carbon::now();
        $contrato->update();

        //$enlace="https://wa.me/$clientetelefono?text=".urlencode($empresa.' - Firma de contrato - '.$ruta);
        $enlace="https://api.whatsapp.com/send/?phone=$clientetelefono&text=".urlencode($empresa.' - Firma de contrato - '.$ruta);
        $this->dispatch('openwhatsapp', ['id' => $enlace]);
    }

    public function sendclient($id){
        //$this->ficha->dtenvio=Carbon::now();
        $contrato = Contrato::find($id);
        //Utils::vacialog();Log::info($x);return;
        //Utils::vacialog();
        $ruta=route('contratocliente',[$id,md5($id."ckecka")]);
        $x = User2::select('logo','nombre','iban')->find($this->userid);
        $logo=$x->logo;
        $empresa=$x->nombre;
        $x=Cliente::where('user_id',$this->userid)->where('id',$contrato->cliente_id)->limit(1)->get();
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
            $contrato->enviado=true;
            $contrato->dtenvio=Carbon::now();
            $contrato->update();
            $this->render();
        }
        if(!$ok){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al enviar mail',  'title' => 'ATENCIÓN']);
        }
    }

    public function recarga(){
        //$this->render();
        //log: :info($this->soloactivos);
    }

    public function destroy($id){
        Contrato::find($id)->delete();
        return redirect(route('clientecontrato-management'))->with('status','contrato eliminado correctamente');
    }

    public function nuevoregistro(){
        $nid=Contrato::insertGetId([
            'user_id'=>$this->userid,
            'galeria_id'=>null,
            'cliente_id'=>null,
            'nombre'=>'',
            'texto'=>'',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        return redirect(route('edit-clientecontrato',$nid));
    }
    
    public function sacanombre($x){
        foreach($x as $y){
            if($y->cliente_id>0){
                $s=Cliente::select('nombre','apellidos','telefono')->where('id',$y->cliente_id)->get()[0];
                $y->nomcliente=$s->nombre." ".$s->apellidos;
                $y->telefono=$s->telefono;
            }
        }
        return $x;
    }

    public function vseccion($xx){
        $this->solopendiente=$xx;
        $this->recarga();
    }

    public function render()
    {
        $acti=$this->solopendiente; // 1 todos 2 pte firma 3 firmado

        switch($acti){
            case 1:
                $pasador=">=";
                $valor=0;
                break;
            case 2:
                $pasador="=";
                $valor=0;
                break;
            case 3:
                $pasador="=";
                $valor=1;
                break;
        }

        //$this->authorize('manage-items', User::class);
        if(strlen($this->search)==0){
            $x = Contrato::
                orderBy($this->sortField, $this->sortDirection)
                ->where('user_id',$this->userid)
                ->where('firmado',$pasador,$valor)
                ->paginate($this->perPage);
                    
            $x=$this->sacanombre($x);

            return view('livewire.clientecontrato.index', [
                'fichas' => $x
            ]);
        }
        $sear=$this->search;
        $x= Contrato::
                where('user_id',$this->userid)
                ->where('firmado','<=',$acti)
                ->where(function ($query) use ($sear) {
                    $query->where('nombre','like','%'.$sear.'%'); // ->orWhere('apellidos','like','%'.$sear.'%')->orWhere('nif','like','%'.$sear.'%');
                })
                ->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
        $x=$this->sacanombre($x);
        return view('livewire.clientecontrato.index', [
            'fichas' => $x
        ]);
    }
}
