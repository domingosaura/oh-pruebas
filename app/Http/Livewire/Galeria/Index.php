<?php

namespace App\Http\Livewire\Galeria;

use App\Models\Galeria;
use App\Models\Binarios;
use App\Models\Binarios2;
use App\Models\Binarios3;
use App\Models\Binarios4;
use App\Models\Binarios5;
use App\Models\User;
use App\Models\User2;
use App\Models\Cliente;
use App\Models\Productosgaleria;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Utils;
use Carbon\Carbon;
use Storage;
use Response;
use File;
use STS\ZipStream\Facades\Zip;
use ZipArchive;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
use Session;

class Index extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    public $marcador = 1;
    public $userid = 0;
    public $soloactivos = 1;
    public $state = 1;
    public $search = '';
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
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

    public function downloadgallery($idd){
        //log: :info(")ini");
        //Utils::vacialog();
        $x=Galeria::select('nombre')->where('user_id',$this->userid)->find($idd);
        if(!$x){
            return;
        }
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $storage_path = storage_path('app/livewire-tmp')."/";
        $nombrezip=str_replace(" ","_",$x->nombre.".zip");
        $zip = new ZipArchive;
        $zipFileName = $nombrezip;

        if ($zip->open($storage_path.$nombrezip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        }else{
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al crear el paquete comprimido',  'title' => 'ATENCIÓN']);
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            return;
        }
        $x=Binarios::select('id','nombre')->where('galeria_id',$idd)->get();
        foreach($x as $y){
            //log: :info($y->nombre);
            //log: :info($y->id);
            //$nombrefoto="oh myphoto_".$idd."_".$y->nombre;
            $nombrefoto=$y->nombre;
            //log: :info($nombrefoto);return;
            $disks3 = Storage::disk('sftp');
            $filePaths3 = '/galerias/'.$this->userid."/";
            $bindata=$disks3->get($filePaths3.$y->id.".jpg");
            $filee=$storage_path.$nombrefoto;
            //log: :info($nombrefoto);
            //log: :info($bindata);
            //File::put($filee,$bindata);
            //$zip->addFile($filee, $nombrefoto);
            $zip->addFromString($nombrefoto, $bindata);
            //File::delete($filee);
        }
        $zip->close();
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        //File::move($storage_path.$nombrezip,$storage_path.$nombrezip1);
        //log: :info($storage_path.$nombrezip);
        return response()->file($storage_path.$nombrezip, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$nombrezip
        ])->deleteFileAfterSend(true);
    }

    public function destroy($idd){
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";
        $x=Binarios::where('galeria_id',$idd)->select('id')->get();
        foreach($x as $y){
            $disks3->delete($filePaths3.$y->id.".jpg");
        }
        Binarios::where('id',$idd)->delete();
        Binarios::where('galeria_id',$idd)->delete();
        Productosgaleria::where('galeria_id',$idd)->delete();
        Galeria::find($idd)->delete();
        //return redirect(route('galeria-management'))->with('status','galería eliminada correctamente');
    }

    public function totrash($idd){
        Galeria::find($idd)->update([
            'eliminada'=>true,
            'fechaeliminada'=>Carbon::now(),
        ]);
        $this->render();
        $this->dispatch('mensaje',['type' => 'error',  'message' => 'Puede recuperar la galería en un máximo de 7 días',  'title' => 'ATENCIÓN']);
    }

    public function nuevoregistro(){


        if(!Session::has('suscrito')){
            $suscrito=Utils::suscripcionactiva(); // 0 no tiene licencia 1 periodo de prueba 2 tiene una suscripcion
            Session::put('suscrito',$suscrito);
        }
        if(Session('suscrito')==0){
            return redirect()->route('micuenta', ['pos' => 0]);
        }
        if($this->userid==6){
            //return redirect()->route('micuenta', ['pos' => 0]);
        }


        $nid=Galeria::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>'',
            'nombreinterno'=>'',
            'anotaciones'=>'',
            'anotaciones2'=>'',
            'numfotos'=>0,
            'maxfotos'=>0,
            'preciogaleria'=>0,
            'preciogaleriacompleta'=>0,
            'preciofoto'=>0,
            'created_at'=>Carbon::now(),
            'diascaducidad'=>7,
            'diascaducidaddescarga'=>30,
            'tipodepago'=>1,
            'permitirdescarga'=>2,
            'updated_at'=>Carbon::now(),
            'caducidad'=>DB::raw("date_add(curdate(),interval 7 day)"),
        ]);
        return redirect(route('edit-galeria',$nid));
    }
    
    public function recarga(){
    }

    public function vseccion($xx){
        $this->soloactivos=$xx;
        $this->recarga();
    }

    public function vseccion2($xx){
        // 1 todas 2 seleccionadas 3 pte seleccion 4 pagadas 5 pte pago
        $this->state=$xx;
        $this->recarga();
    }

    public function archivar($id){
        Galeria::where('id',$id)->update([
            'archivada'=>true
        ]);
        $this->recarga();
    }

    public function desarchivar($id){
        Galeria::where('id',$id)->update([
            'archivada'=>false
        ]);
        $this->recarga();
    }

    public function recover($id){
        Galeria::where('id',$id)->update([
            'eliminada'=>false,
            'fechaeliminada'=>null,
        ]);
        $this->recarga();
    }

    public function sendclientwhatsapp($id,$cliid){
        $ruta=route('galeriacliente',[$id,md5($id."ckeck")]);

        $ruta=str_replace('nonohttps://','',$ruta); // disable preview image
        //$ruta=str_replace('https://www.','www.',$ruta); // disable preview image
        //$ruta=str_replace('https://','www.',$ruta); // disable preview image
        //$ruta=' . '.$ruta.' . '; // disable preview image

        $ficha = Galeria::find($id);
        $xuser2 = User2::find($this->userid);
        $empresa=$xuser2->nombre;
        $x=Cliente::where('user_id',$this->userid)->where('id',$cliid)->limit(1)->get();
        $clientetelefono=$x[0]->telefono;
        if(!$clientetelefono){
            //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Exxxl cliente no tiene teléfono',  'title' => 'ATENCIÓN']);
            //return;
        }

        if(strlen($clientetelefono)==9)
            $clientetelefono="34".$clientetelefono;


        //$enlacea="https://wa.me/$clientetelefono?text=".($empresa.' - '.$ficha->nombre.' - '.$ruta);
        //log: :info($enlacea);
        //$enlace="https://wa.me/$clientetelefono?text=".urlencode($empresa.' - '.$ficha->nombre.' - '.$ruta);
        $enlace="https://api.whatsapp.com/send/?phone=$clientetelefono&text=".urlencode($empresa.' - '.$ficha->nombre.' - '.$ruta);

        //$this->dispatch('mensaje',['type' => 'success',  'message' => $enlace,  'title' => 'ATENCIÓN']);

        $xs=Galeria::select('dtenvio','dtenvio2','dtenvio3','dtenvio4','dtenvio5','dtenvio6','dtenvio7','dtenvio8','dtenvio9','dtenvio10')->where('id',$id)->get();
        $campo="dtenvio";
        for ($i=1; $i <=10 ; $i++) {
            $xa="dtenvio";
            if($i>1)
            $xa="dtenvio".$i;
        if($xs[0]->$xa==null){
            $campo=$xa;
            break;
            }
        }
        Galeria::where('id',$id)->update([
            'enviado'=>true,
            $campo=>Carbon::now(),
        ]);



        $this->dispatch('openwhatsapp', ['id' => $enlace]);

    }

    public function sendclient($id,$cliid){
        $ruta=route('galeriacliente',[$id,md5($id."ckeck")]);
        $xuser = User::find($this->userid);
        $xuser2 = User2::find($this->userid);
        $logo=$xuser2->logo;
        $empresa=$xuser2->nombre;
        $x=Cliente::where('user_id',$this->userid)->where('id',$cliid)->limit(1)->get();
        $clienteemail=$x[0]->email;
        $clientenombre=$x[0]->nombre." ".$x[0]->apellidos;

        $direcciones = [];
        //$direcciones[]=$clienteemail;
        $direcciones[]=['address'=>$clienteemail,'name'=>$clientenombre];

        $ficha = Galeria::find($id);
        $clave=$ficha->clavecliente;
        $asunto="Aquí tienes tu galería en ".$empresa;
        $vista = "email.sendtoclient";

        // email personalizado
        $texto="";
        $asu=$ficha->emailenvioasunto;
        $cue=$ficha->emailenviocuerpo;
        if(strlen($asu)>0 && strlen($cue)>0){
            $vista="email.sendtoclientpersonalizado";
            $asunto=$asu;
            $texto=$cue;
            //
            $texto=str_replace("border-radius: 5px;","",$texto);
            $texto=str_replace("text-align: center;","",$texto);
            $texto=str_replace("padding: 4px 8px;","",$texto);
            $texto=str_replace("color: white;","",$texto);
            $texto=str_replace("background-color: orange;","",$texto);
            //
            if (str_contains($texto, '>enlace<')) {
                $s='<span style="    ">enlace</span>';
                $texto=str_replace($s,"<a href='$ruta'>$ruta</a>",$texto);
                $ruta="";
            }
            if (str_contains($texto, '>clave<')) {
                $s='<span style="    ">clave</span>';
                $texto=str_replace($s,$ficha->clavecliente,$texto);
                $clave="";
            }
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
            $texto=str_replace($s,$x[0]->nombre." ".$x[0]->apellidos,$texto);
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
            $texto=str_replace($s,'<strong>'.$x[0]->nombre." ".$x[0]->apellidos.'</strong>',$texto);

        }
        //

        $ok = true;
        $datos=[
            'ruta'=>$ruta,
            'logo'=>$logo,
            'empresa'=>$empresa,
            'caduco'=>$ficha->caducidad,
            'clave'=>$clave,
            'numfotos'=>$ficha->numfotos,
            'personalizado'=>$texto,
        ];
        //Log::info($datos);
        //Log::info("sendtocliente");
        //Log::info($this->userid);
        $reply=Utils::cargarconfiguracionemailempresa($this->userid);
        $this->errormail="";
        try {
            $body = view($vista,['datos' => $datos])->render();
            $bodyfull=Utils::emailtocid($body);
            //Log::info($direcciones);
            //Log::info($asunto);
            //Log::info($reply);
            //Log::info($bodyfull);
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

            $xs=Galeria::select('dtenvio','dtenvio2','dtenvio3','dtenvio4','dtenvio5','dtenvio6','dtenvio7','dtenvio8','dtenvio9','dtenvio10')->where('id',$id)->get();
            $campo="dtenvio";
            for ($i=1; $i <=10 ; $i++) {
                $xa="dtenvio";
                if($i>1)
                $xa="dtenvio".$i;
            if($xs[0]->$xa==null){
                $campo=$xa;
                break;
                }
            }
            Galeria::where('id',$id)->update([
                'enviado'=>true,
                $campo=>Carbon::now(),
            ]);

            $this->dispatch('mensaje',['type' => 'success',  'message' => 'enlace enviado',  'title' => 'ATENCIÓN']);
        }
        if(!$ok){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al enviar mail',  'title' => 'ATENCIÓN']);
        }
    }

    public function ajustabinarios($res){
        // si no tenemos soporte avif convierte a jpg cuidado que el modelo no se grabe sobreescribirá el binario avif bueno!
        if(Session('soporteavif')){
            foreach($res as $key=>$bin){
                if(strlen($bin->binariomin)==0)
                    continue;
                $res[$key]->binariomin="data:image/jpeg;base64,".$bin->binariomin;
            }            
            return $res;
        }
        // aqui si no soporte avif
        foreach($res as $key=>$bin){
            if(strlen($bin->binariomin)==0)
                continue;
            $rut = storage_path('app/public/tmpgallery')."/".Utils::randomString(10).".jpg";
            $bindata=base64_decode($bin->binariomin);
            File::put($rut,$bindata);
            $su=Spatie::load($rut)->base64();
            File::delete($rut);
            $res[$key]->binariomin=$su;
        }
        return $res;
    }

    public function render()
    {

        $acti=$this->soloactivos; // 1 activos 2 archivados 3 trashed
        // sin paginate
        $this->perPage=1000000;

        switch($acti){
            case 3: // papelera
                $campo1="eliminada";
                $pasador="=";
                $valor=1;
                $campo2="eliminada";
                $pasador2="=";
                $valor2=1;
                break;
            case 1: // activos
                $campo1="archivada";
                $pasador="=";
                $valor=0;
                $campo2="eliminada";
                $pasador2="=";
                $valor2=0;
                break;
            case 2: // archivados
                $campo1="archivada";
                $pasador="=";
                $valor=1;
                $campo2="eliminada";
                $pasador2="=";
                $valor2=0;
                break;
        }


        // 1 todas 2 seleccionadas 3 pte seleccion 4 pagadas 5 pte pago
        // $this->state=$xx;
        // pagado pagadomanual seleccionconfirmada
        $campo3="galerias.user_id";
        $pasador3="=";
        $valor3=$this->userid;
        if($acti==1){
            switch($this->state){
                case 2:
                    // seleccionadas
                    $campo3="galerias.seleccionconfirmada";
                    $pasador3="=";
                    $valor3=1;
                    break;
                case 3:
                    // pte seleccion
                    $campo3="galerias.seleccionconfirmada";
                    $pasador3="=";
                    $valor3=0;
                    break;
                case 4:
                    // pagadas
                    $campo3="galerias.pagado";
                    $pasador3="=";
                    $valor3=1;
                    break;
                case 5:
                    // pte pago
                    $campo3="galerias.pagado";
                    $pasador3="=";
                    $valor3=0;
                    break;
            }
        }


        $this->sortField="created_at";
        $this->sortDirection="desc";

        //$this->authorize('manage-items', User::class);
        if(strlen($this->search)==0){

            $x=Galeria::
            select('galerias.id','galerias.cliente_id','galerias.nombre','galerias.nombreinterno','galerias.created_at','galerias.archivada',
                'galerias.eliminada',
                'galerias.binariomin','galerias.pagado','galerias.descargada','galerias.seleccionconfirmada','galerias.enviado','galerias.dtenvio',
                'clientes.nombre as nomcli','clientes.apellidos as apecli',
                DB::raw('(select sum(originalsize)/1024/1024 from binarios where galeria_id=galerias.id) as sizeg'))
                ->leftJoin('clientes','clientes.id','=','galerias.cliente_id')
                ->orderBy($this->sortField, $this->sortDirection)
                ->where('galerias.user_id',$this->userid)
                ->where($campo1,$pasador,$valor)
                ->where($campo2,$pasador2,$valor2)
                ->where($campo3,$pasador3,$valor3)
                ->paginate($this->perPage);
            $x=$this->ajustabinarios($x);

            return view('livewire.galeria.index', [
                'fichas' => $x
            ]);
        }
        $sear=$this->search;
        
        $x=Galeria::
        select('galerias.id','galerias.cliente_id','galerias.nombre','galerias.nombreinterno','galerias.created_at','galerias.archivada',
        'galerias.eliminada',
        'galerias.binariomin','galerias.pagado','galerias.descargada','galerias.seleccionconfirmada','galerias.enviado','galerias.dtenvio',
        'clientes.nombre as nomcli','clientes.apellidos as apecli',
        DB::raw('(select sum(originalsize)/1024/1024 from binarios where galeria_id=galerias.id) as sizeg'))
        ->leftJoin('clientes','clientes.id','=','galerias.cliente_id')
        ->where('galerias.user_id',$this->userid)
        ->where($campo1,$pasador,$valor)
        ->where($campo2,$pasador2,$valor2)
        ->where($campo3,$pasador3,$valor3)
        ->where('archivada',$pasador,$valor)
        ->where(function ($query) use ($sear) {
        $query->where('galerias.nombre','like','%'.$sear.'%')
            ->orWhere('galerias.nombreinterno','like','%'.$sear.'%')
            ->orWhere('clientes.nombre','like','%'.$sear.'%')
            ->orWhere('clientes.apellidos','like','%'.$sear.'%'); // ->orWhere('titulo','like','%'.$sear.'%'); // ->orWhere('nif','like','%'.$sear.'%');
        })
        ->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
        $x=$this->ajustabinarios($x);
        return view('livewire.galeria.index', [
            'fichas' => $x
        ]);
    }
}
