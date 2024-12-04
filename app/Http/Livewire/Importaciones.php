<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Basecalendario;
use App\Models\Contrato;
use App\Models\Galeria;
use Log;
use DB;
use File;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Storage;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Http\Utils;

class Importaciones extends Component
{
    use WithFileUploads;
    public $files=[];
    public $data=[];
    public $columns=[];
    public $colimport=[
        ["titulo"=>"no se importa","val"=>0],
        ["titulo"=>"nombre","val"=>1],
        ["titulo"=>"apellidos","val"=>2],
        ["titulo"=>"apellido 1","val"=>10],
        ["titulo"=>"apellido 2","val"=>11],
        ["titulo"=>"nif","val"=>3],
        ["titulo"=>"domicilio","val"=>4],
        ["titulo"=>"cpostal","val"=>5],
        ["titulo"=>"poblacion","val"=>6],
        ["titulo"=>"provincia","val"=>7],
        ["titulo"=>"telefono","val"=>8],
        ["titulo"=>"email","val"=>9],
        ["titulo"=>"nombre pareja","val"=>12],
        ["titulo"=>"apellidos pareja","val"=>13],
        ["titulo"=>"apellido 1 pareja","val"=>16],
        ["titulo"=>"apellido 2 pareja","val"=>17],
        ["titulo"=>"nif pareja","val"=>14],
        ["titulo"=>"notas internas","val"=>15],
        ["titulo"=>"familiares (separados por coma)","val"=>18],
    ];
    public $filename="";
    public $userid = 0;

    public function mount(){
        ini_set('memory_limit', '4G');
        $this->userid=Auth::id();
        $files = Storage::disk("")->allFiles("tmpimport");
        foreach ($files as $file) {
            // eliminamos las imagenes de mas de 3 horas
            $time = Storage::disk('')->lastModified($file);
            $fileModifiedDateTime = Carbon::parse($time); // vale, saca dos horas menos pero para esto da lo mismo
            Storage::disk("")->delete($file);
            if (Carbon::now()->gt($fileModifiedDateTime->addHour(3))) {
             }
         }
         $this->filename="";
         $storage_path = storage_path('app/tmpimport')."/";
         $filename=$this->userid."_import"."."."xls";
         $filee=$storage_path.$filename;
         if(file_exists($filee)){
             $this->filename=$filee;
         }
         $filename=$this->userid."_import"."."."xlsx";
         $filee=$storage_path.$filename;
         if(file_exists($filee)){
             $this->filename=$filee;
         }
         $filename=$this->userid."_import"."."."csv";
         $filee=$storage_path.$filename;
         if(file_exists($filee)){
             $this->filename=$filee;
         }
        $this->loadfile();
    }

    public function saveimage($x=""){
        if(empty($this->files)){
            return;
        }
        $storage_path = storage_path('app/tmpimport')."/";
        $extension=$this->files[0]->getClientOriginalExtension();
        $nombreoriginal=strtolower($this->files[0]->getClientOriginalName());
        
        if(File::exists($storage_path.$this->userid."_import".".csv"))
            File::delete($storage_path.$this->userid."_import".".csv");
        if(File::exists($storage_path.$this->userid."_import".".xls"))
            File::delete($storage_path.$this->userid."_import".".xls");
        if(File::exists($storage_path.$this->userid."_import".".xlsx"))
            File::delete($storage_path.$this->userid."_import".".xlsx");
        $this->filename=$this->userid."_import".".".$extension;
        $this->files[0]->storeAs('tmpimport',$this->filename);
        $filee=$storage_path.$this->filename;
        $this->files=[];
        $this->filename=$filee;
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        //$this->dispatch('scrolltop',['title' => 'ATENCIÓN']);
        $this->loadfile();
    }
    
    public function loadfile(){
        //Utils::vacialog();
        //if($this->userid!=15){
        //    $this->data=[];
        //    return redirect('/');
        //}
        if(empty($this->filename)){
            $this->data=[];
            return;
        }


        try {
            $this->data = Excel::toArray([],$this->filename);
        } catch (\Throwable $th) {
            $this->dispatch('mensaje',['type' => 'info',  'message' => '¡vaya! parece que este archivo no es válido',  'title' => 'ATENCIÓN']);
            $this->filename="";
            return;
        }


        $this->data=$this->data[0];
        $columns=$this->data[0];
        $this->columns=[];
        foreach($columns as $col){
            $this->columns[]=[
                "nombre"=>$col,"val"=>0
            ];
        }
        unset($this->data[0]);
    }

    public function importar(){
        // posibles valores 0 a 9 - col nif es la 3
        $email=false;
        $c0=0;
        $c1=0;
        $c2=0;
        $c3=0;
        $c4=0;
        $c5=0;
        $c6=0;
        $c7=0;
        $c8=0;
        $c9=0;
        $c10=0;
        $c11=0;
        $c12=0;
        $c13=0;
        $c14=0;
        $c15=0;
        $c16=0;
        $c17=0;
        $c18=0;
        //log: :info($this->columns);
        foreach($this->columns as $col){
            if($col['val']==9){
                $email=true;
            }
            $x=$col['val'];
            $u="c".$x;
            //log: :info($u);
            $$u++;
        }
        foreach($this->columns as $key=>$col){
            if($col['val']==0){
                unset($this->columns[$key]);
            }
        }
        if(!$email){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'columna email es imprescindible',  'title' => 'ATENCIÓN']);
            return;
        }
        if($c1>1||$c2>1||$c3>1||$c4>1||$c5>1||$c6>1||$c7>1||$c8>1||$c9>1||$c10>1||$c11>1||$c12>1||$c13>1||$c14>1||$c15>1||$c16>1||$c17>1||$c18>1){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'no puede haber columnas repetidas',  'title' => 'ATENCIÓN']);
            return;
        }
        //Utils::vacialog();
        //log: :info($this->columns);
        //log: :info($this->data);
        //return;
        foreach($this->data as $dat){
            //public static function soloNumLet($texo, $dejarespacios = true, $dejarpuntos = true, $dejarcomas = false)
            $nombre="";
            $apellidos="";
            $nif="";
            $domicilio="";
            $cpostal="";
            $poblacion="";
            $provincia="";
            $telefono="";
            $email="";
            $nompareja="";
            $apepareja="";
            $nifpareja="";
            $notas="";
            $familiares="";
            $unombre=false;
            $uapellidos=false;
            $unif=false;
            $udomicilio=false;
            $ucpostal=false;
            $upoblacion=false;
            $uprovincia=false;
            $utelefono=false;
            $unompareja=false;
            $uapepareja=false;
            $unifpareja=false;
            $unotas=false;
            $ufamiliares=false;
            $uemail=false;
            foreach($this->columns as $key=>$col){
                $poss=$col['val'];
                switch($poss){
                    case 12:
                        $nompareja=$dat[$key];
                        $nompareja=is_null($nompareja)?"":$nompareja;
                        $unompareja=true;
                        break;
                    case 18:
                        $familiares=$dat[$key];
                        $familiares=is_null($familiares)?"":$familiares;
                        $ufamiliares=true;
                        break;
                    case 13:
                        $apepareja=$dat[$key];
                        $apepareja=is_null($apepareja)?"":$apepareja;
                        $uapepareja=true;
                        break;
                    case 16:
                        $apepareja=$dat[$key];
                        $apepareja=is_null($apepareja)?"":$apepareja;
                        $uapepareja=true;
                        break;
                    case 17:
                        $apepareja=$apepareja." ".$dat[$key];
                        $apepareja=is_null($apepareja)?"":$apepareja;
                        break;
                    case 14:
                        $nifpareja=$dat[$key];
                        $nifpareja=is_null($nifpareja)?"":$nifpareja;
                        $unifpareja=true;
                        break;
                    case 15:
                        $notas=$dat[$key];
                        $notas=is_null($notas)?"":$notas;
                        $unotas=true;
                        break;
                    case 1:
                        $nombre=$dat[$key];
                        $nombre=is_null($nombre)?"":$nombre;
                        $unombre=true;
                        break;
                    case 2:
                        $apellidos=$dat[$key];
                        $apellidos=is_null($apellidos)?"":$apellidos;
                        $uapellidos=true;
                        break;
                    case 10:
                        $apellidos=$dat[$key];
                        $apellidos=is_null($apellidos)?"":$apellidos;
                        $uapellidos=true;
                        break;
                    case 11:
                        $apellidos=$apellidos." ".$dat[$key];
                        $apellidos=is_null($apellidos)?"":$apellidos;
                        break;
                    case 3:
                        $nif=Utils::soloNumLet($dat[$key],false,false,false);
                        $nif=is_null($nif)?"":$nif;
                        $unif=true;
                        break;
                    case 4:
                        $domicilio=$dat[$key];
                        $domicilio=is_null($domicilio)?"":$domicilio;
                        $udomicilio=true;
                        break;
                    case 5:
                        $cpostal=$dat[$key];
                        $cpostal=is_null($cpostal)?"":$cpostal;
                        $ucpostal=true;
                        break;
                    case 6:
                        $poblacion=$dat[$key];
                        $poblacion=is_null($poblacion)?"":$poblacion;
                        $upoblacion=true;
                        break;
                    case 7:
                        $provincia=$dat[$key];
                        $provincia=is_null($provincia)?"":$provincia;
                        $uprovincia=true;
                        break;
                    case 8:
                        $telefono=$dat[$key];
                        $telefono=is_null($telefono)?"":$telefono;
                        $utelefono=true;
                        break;
                    case 9:
                        $email=$dat[$key];
                        $email=is_null($email)?"":$email;
                        $uemail=true;
                        break;
                }
    
            }
            if(strlen($email)==0){
                //log: :info("noemail");
                continue;
            }

            $nombre=Utils::left($nombre,100);
            $apellidos=Utils::left($apellidos,100);
            $domicilio=Utils::left($domicilio,200);
            $cpostal=Utils::left($cpostal,8);
            $poblacion=Utils::left($poblacion,100);
            $provincia=Utils::left($provincia,100);
            $telefono=Utils::left($telefono,25);
            $email=Utils::left($email,200);
            $nif=Utils::left($nif,15);
            $nompareja=Utils::left($nompareja,100);
            $apepareja=Utils::left($apepareja,100);
            $nifpareja=Utils::left($nifpareja,15);
            //$notas=Utils::left($notas,100);

            //log: :info("siemail");
            //log: :info($nombre." ".$apellidos." ".$nif);
            $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->count();
            switch($cuenta){
                case 0:

                    try {
                        Cliente::insert([
                            'user_id'=>$this->userid,
                            'nombre'=>$nombre,
                            'apellidos'=>$apellidos,
                            'domicilio'=>$domicilio,
                            'cpostal'=>$cpostal,
                            'poblacion'=>$poblacion,
                            'provincia'=>$provincia,
                            'telefono'=>$telefono,
                            'email'=>$email,
                            'nif'=>$nif,
                            'nombrepareja'=>$nompareja,
                            'apellidospareja'=>$apepareja,
                            'nifpareja'=>$nifpareja,
                            'notasinternas'=>$notas,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    } catch (\Throwable $th) {}


                    if($ufamiliares){
                        $pieces = explode(",", $familiares);
                        $hasta=count($pieces)<6?count($pieces):6;
                        //log: :info(count($pieces));
                        for($alfa=0;$alfa<=$hasta-1;$alfa++){
                            $campo="hijo".($alfa+1);
                            try {
                                $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update([$campo=>$pieces[$alfa]]);
                            } catch (\Throwable $th) {}
                        }
                    }

                    break;
                default:
                $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['created_at'=>Carbon::now()]);
                if(($unombre)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['nombre'=>$nombre]);
                    } catch (\Throwable $th) {}
                }
                if(($uapellidos)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['apellidos'=>$apellidos]);
                    } catch (\Throwable $th) {}
                }
                if(($udomicilio)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['domicilio'=>$domicilio]);
                    } catch (\Throwable $th) {}
                }
                if(($ucpostal)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['cpostal'=>$cpostal]);
                    } catch (\Throwable $th) {}
                }
                if(($upoblacion)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['poblacion'=>$poblacion]);
                    } catch (\Throwable $th) {}
                }
                if(($uprovincia)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['provincia'=>$provincia]);
                    } catch (\Throwable $th) {}
                }
                if(($utelefono)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['telefono'=>$telefono]);
                    } catch (\Throwable $th) {}
                }
                if(($unif)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['nif'=>$nif]);
                    } catch (\Throwable $th) {}
                }
                if(($unompareja)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['nombrepareja'=>$nompareja]);
                    } catch (\Throwable $th) {}
                }
                if(($uapepareja)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['apellidospareja'=>$apepareja]);
                    } catch (\Throwable $th) {}
                }
                if(($unifpareja)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['nifpareja'=>$nifpareja]);
                    } catch (\Throwable $th) {}
                }
                if(($unotas)){
                    try {
                        $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update(['notasinternas'=>$notas]);
                    } catch (\Throwable $th) {}
                }
                if($ufamiliares){
                    $pieces = explode(",", $familiares);
                    $hasta=count($pieces)<6?count($pieces):6;
                    //log: :info(count($pieces));
                    for($alfa=0;$alfa<=$hasta-1;$alfa++){
                        $campo="hijo".($alfa+1);
                        try {
                            $cuenta=Cliente::where('user_id',$this->userid)->where('email',$email)->update([$campo=>$pieces[$alfa]]);
                        } catch (\Throwable $th) {}
                    }
                }
                break;
            }
        }
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        $this->dispatch('mensajelargo',['type' => 'success',  'message' => 'Archivo importado',  'title' => 'ATENCIÓN']);
    }

    public function revisar()
    {
        //Utils::vacialog();
        //log: :info($this->columns);
    }
    
    public function render()
    {
        return view('livewire.importaciones');
    }
}
