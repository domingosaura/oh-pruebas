<?php

namespace App\Http\Livewire\Calendarios;

use App\Models\Basecalendario;
use App\Models\Calendario;
use App\Models\Cliente;
use App\Models\User2;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Http\Utils;
use Storage;
use Image;
use File;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
use DateTime;

class Index extends Component
{

    use WithPagination;
    use AuthorizesRequests;
    //use SoftDeletes;

    public $tipo = "";
    public $userid = 0;
    public $search = '';
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $perPage = 25;
    public $soloactivos = 1;
    public $clientes;
    public $emailsesion=""; // para enlace mail solicitar sesion
    public $telefsesion=""; // para enlace mail solicitar sesion
    public $notifsesion=""; // para enlace mail solicitar sesion
    public $idclisesion=0; // para enlace mail solicitar sesion
    public $calendarid=0; // para enlace mail solicitar sesion
    public $errormail="";
    public $rutaaccesocliente="";

    protected $queryString = ['sortField', 'sortDirection','soloactivos'];
    protected $paginationTheme = 'bootstrap';

    public function mount(){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $discobox = Storage::disk('sftp');
        $this->userid=Auth::id();

        $clientes=DB::table("clientes")
        ->select(DB::raw("concat(nombre,' ',apellidos,' ',telefono,' ',email,' ',nif) as label"),'id as value')
        ->where('user_id',$this->userid)
        ->orderBy('nombre','asc')
        ->get();
        $clientes=Utils::objectToArray($clientes);
        $this->clientes=json_encode($clientes);

        // binarios antiguos
        if(1==2){
            $x=Basecalendario::where('user_id',$this->userid)->get();
            foreach($x as $y){
                $bindata=base64_decode($y['binario']);
                $file1 = '/galerias/assets/cal'.$y['id']."-".md5('cal'.$y['id']).".avif";
                $file2 = '/galerias/assets/cal'.$y['id']."-".md5('cal'.$y['id']).".jpg";
                //Log::info($file1);
                //Log::info($disks3->exists($file1));
                $discobox->put($file1, $bindata);
                $discobox->put($file2, $bindata);
                Basecalendario::where('id',$y['id'])->update(['binario'=>'']);
            }
        }
        //

    }
    public function sortBy($field){
        if($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function nuevoregistro(){
        $nid=Basecalendario::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>'',
            //'nombre'=>'introduzca un título',
            'descripcion'=>'',
            'activo'=>true,
            'permitereserva'=>true,
            'redsys'=>true,
            'paypal'=>true,
            'stripe'=>true,
            'bizum'=>true,
            'efectivo'=>true,
            'transferencia'=>true,
            'binario'=>'',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        return redirect(route('edit-calendario',$nid));
    }

    public function destroy($id){
        Basecalendario::find($id)->delete();
        Calendario::where('basecalendario_id',$id)->delete();
        $file1 = '/galerias/assets/cal'.$id."-".md5('cal'.$id).".avif";
        $file2 = '/galerias/assets/cal'.$id."-".md5('cal'.$id).".jpg";
        $discobox = Storage::disk('sftp');
        $discobox->delete($file1);
        $discobox->delete($file2);
        $file1loc = storage_path('app/public/tmpgallery').'/cal'.$id."-".md5('cal'.$id).".avif";
        $file2loc = storage_path('app/public/tmpgallery').'/cal'.$id."-".md5('cal'.$id).".jpg";
        File::delete($file1loc);
        File::delete($file2loc);
        return redirect(route('calendarios-management'))->with('status','calendario eliminado correctamente');
    }

    public function vseccion($xx){
        $this->soloactivos=$xx; // 1 activo 2 inactivo 3 todo
    }

    public function setidcliente($idid){
        // tiene que estar
    }
    public function setcalendar($idid){
        $this->calendarid=$idid;
        $this->rutaaccesocliente=url('/').'/reservas/'.$this->userid.'/'.$this->calendarid.'/uid'.Utils::left(md5($this->calendarid.'calendar'),4);
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

    public function recarga(){
        //$this->render();
        //log: :info($this->soloactivos);
    }
    
    public function asignarimagenes($xx){
        return;
        $discobox = Storage::disk('sftp');
        foreach($xx as $key=>$y){
            $file1='/oh/img/gallery-generic.jpg';
            $file2='/oh/img/gallery-generic.jpg';
            $xx[$key]->file1=$file1;
            $xx[$key]->file2=$file2;
            $file1=Storage::url('tmpgallery/oh/img/gallery-generic.jpg');
            $file2=Storage::url('tmpgallery/oh/img/gallery-generic.jpg');
            $file1 = '/galerias/assets/cal'.$y['id']."-".md5('cal'.$y['id']).".avif";
            $file2 = '/galerias/assets/cal'.$y['id']."-".md5('cal'.$y['id']).".jpg";
        }
    }
    
    public function render()
    {
        //$this->authorize('manage-items', User::class);
        // sin paginate
        $this->perPage=1000000;
        switch($this->soloactivos){
            case 1:
                $modi="=";
                $valor=1;
                break;
            case 2:
                $modi="=";
                $valor=0;
                break;
            case 3:
                $modi=">=";
                $valor=0;
                break;
        }
        if(strlen($this->search)==0){
            $x=Basecalendario::orderBy($this->sortField, $this->sortDirection)
                ->where('activo',$modi,$valor)
                ->where('user_id',$this->userid)
                ->paginate($this->perPage);
            $this->asignarimagenes($x);
            return view('livewire.calendarios.index', [
                'datos' => $x
            ]);
        }
        $sear=$this->search;
        $x=Basecalendario::
        where('user_id',$this->userid)
        ->where('activo',$modi,$valor)
        ->where(function ($query) use ($sear) {
            $query->where('nombre','like','%'.$sear.'%');
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);
        $this->asignarimagenes($x);
        return view('livewire.calendarios.index', [
            'datos' => $x
        ]);
    }
}
