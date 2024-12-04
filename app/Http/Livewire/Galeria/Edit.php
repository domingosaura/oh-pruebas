<?php

namespace App\Http\Livewire\Galeria;

use App\Models\Galeria;
use App\Models\Pgaleria;
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
use App\Models\Productos;
use App\Models\Pproductos;
use App\Models\Productosgaleria;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use File;
use App\Http\Utils;
use DB;
use Log;
use Livewire\WithFileUploads;
//use Intervention\Image\Laravel\Facades\Image;
//use Intervention\Image\Facades\Image as Image;
use Image;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
//use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use ZipArchive;

class Edit extends Component
{

    use WithFileUploads;
    public Galeria $ficha;
    public $tablabinarios;
    public $plantillas;
    public $progress=10;
    public $clientes;
    public $seccion = 1;
    public $idgaleria = 0;
    public $userid;
    public $clienteid;
    public $clientetelefono="";
    public $nombreempresa="";
    public $photoname;
    public $iban="configurada en [mi cuenta]";
    public $base64="";
    public $base64min="";
    public $files=[];
    public $images=[];
    public $galeria=[];
    public $galeriabis=[];
    public $galeriabiscount=0;
    public $formaspago=[];
    public $desgloseado=[];
    public $galmd5="";
    public $precio=0;
    public $seleccionadas = 0;
    public $totalfotos = 0;
    public $sizegallery = 0;
    public $procesable=false;
    public $imgloaded=false;
    public $newnombre="";
    public $newapellidos="";
    public $newdni="";
    public $newemail="";
    public $newtelefono="";
    public $newtext="";
    public $versele=1;
    public $vernosele=true;
    public $vernotas=false;
    public $lightroom="";
    public $photomechanic="";
    public $lightroom2="";
    public $photomechanic2="";
    public $productos=[];
    public $productoseleccion=[];
    public $productoseleccionid=-1;
    public $productosdisponibles=[];
    public $versoloproductos=1; // 1 todos 2 seleccionados 3 no seleccionados
    public $versolofotosproductos=1; // 1 todos 2 seleccionados 3 no seleccionados
    public $versolofotosproductossolonotas=false; // 1 todos 2 seleccionados 3 no seleccionados
    public $productosincluidos=0;
    public $productosadicionales=0;
    public $textobackdrop="";
    public $peticiondescarga=false;

    public $rutadescarga="";
    public $descargadisponible=false;

    public $pagado=false; // solo se incluye para que exista ya que mismo objeto se usa aqui y en galeriacliente
    public $pagadomanual=false; // solo se incluye para que exista ya que mismo objeto se usa aqui y en galeriacliente
    public $seleccionconfirmada=false; // solo se incluye para que exista ya que mismo objeto se usa aqui y en galeriacliente
    public $errormail="";
    public $notascliente="";
    public $notasclientekey=0;

    //public $nohay descarga;

    use AuthorizesRequests;
    
    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'ficha.nombre' => '',
            'ficha.nombreinterno' => 'required',
            'ficha.anotaciones' => '',
            'ficha.anotaciones2' => '',
            'ficha.entregado' => 'required|numeric',
            'ficha.numfotos' => 'required|numeric',
            'ficha.maxfotos' => 'required|numeric',
            'ficha.preciogaleria' => 'required|numeric',
            'ficha.preciogaleriacompleta' => 'required|numeric',
            'ficha.preciofoto' => 'required|numeric',
            'ficha.diascaducidad' => 'required|numeric',
            'ficha.caducidad' => '',
            'ficha.clavecliente' => 'max:20',
            'ficha.permitirdescarga' => '',
            'ficha.nohaydescarga' => '',
            'ficha.diascaducidaddescarga' => '',
            'ficha.marcaagua' => '',
            'ficha.nombresfotos' => '',
            'ficha.binario' => '',
            'ficha.binariomin' => '',
            'ficha.clicambiapago' => '',
            'ficha.archivada' => '',
            'ficha.pagado' => '',
            'ficha.pagadomanual' => '',
            'ficha.seleccionconfirmada' => '',
            'ficha.fechapago' => '',
            'ficha.fechafirma' => '',
            'ficha.descargada' => '',
            'ficha.fechadescarga' => '',
            'ficha.tipodepago' => 'required|numeric',
            'ficha.permitircomentarios' => '',
            'ficha.cliente_id' => '',
            'ficha.pago1activo' => '','ficha.pago2activo' => '','ficha.pago3activo' => '','ficha.pago4activo' => '','ficha.pago5activo' => '','ficha.pago6activo' => '',
            'ficha.pack1' => 'required|numeric','ficha.pack1precio' => 'required|numeric',
            'ficha.pack2' => 'required|numeric','ficha.pack2precio' => 'required|numeric',
            'ficha.pack3' => 'required|numeric','ficha.pack3precio' => 'required|numeric',
            'ficha.emailpagoasunto' => '','ficha.emailpagocuerpo' => '',
            'ficha.emailconfirmaasunto' => '','ficha.emailconfirmacuerpo' => '',
            'ficha.emailenvioasunto' => '','ficha.emailenviocuerpo' => '',
            'ficha.selopc1' => '','ficha.selopc2' => '','ficha.selopc3' => '','ficha.selopc4' => '','ficha.selopc5' => '',
            'ficha.selopc6' => '','ficha.selopc7' => '','ficha.selopc8' => '','ficha.selopc9' => '','ficha.selopc10' => '',
            'ficha.incluido1' => '','ficha.incluido2' => '','ficha.incluido3' => '','ficha.incluido4' => '','ficha.incluido5' => '',
            'ficha.incluido6' => '','ficha.incluido7' => '','ficha.incluido8' => '','ficha.incluido9' => '','ficha.incluido10' => '',
            'ficha.opcional1' => '','ficha.opcional2' => '','ficha.opcional3' => '','ficha.opcional4' => '','ficha.opcional5' => '',
            'ficha.opcional6' => '','ficha.opcional7' => '','ficha.opcional8' => '','ficha.opcional9' => '','ficha.opcional10' => '',
            'ficha.precioopc1' => 'required|numeric','ficha.precioopc2' => 'required|numeric','ficha.precioopc3' => 'required|numeric','ficha.precioopc4' => 'required|numeric','ficha.precioopc5' => 'required|numeric',
            'ficha.precioopc6' => 'required|numeric','ficha.precioopc7' => 'required|numeric','ficha.precioopc8' => 'required|numeric','ficha.precioopc9' => 'required|numeric','ficha.precioopc10' => 'required|numeric',
        ];
    }

    public function mount($id){
        // Clean the Livewire temp-upload folder
        //File::cleanDirectory(\storage_path('app/livewire-tmp'));
        $this->userid=Auth::id();
        //if($this->userid!=0)
        //    Avisos::where('user_id',$this->userid)->where('galeria_id',$id)->update(['pendiente'=>false]);
        // Clean the Livewire temp-upload folder
        $this->idgaleria=$id;

        $this->tablabinarios="Binarios";

        if(Galeria::where('id',$id)->count()==0)
            return redirect('/dashboard')->with('status', "Esta galería ya no existe.");
    
        $this->ficha=Galeria::where('user_id',$this->userid)->find($id);
        //Log::info($this->ficha);
        $this->clienteid=$this->ficha->cliente_id;
        $this->ficha->archivada=$this->ficha->archivada==1?true:false;
        //$this->ficha->permitirdescarga=$this->ficha->permitirdescarga==1?true:false;
        //$this->ficha->nohay descarga=$this->ficha->nohay descarga==1?true:false;
        //$this->nohay descarga=$this->ficha->nohay descarga;
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
        if($this->userid==6){
            $this->versoloproductos=1;
        }

        // pagado fechapago tipodepago 1 efectivo 2 transferencia 3 redsys 4 paypal 5 stripe 6 bizum manual
        $this->formaspago = Formaspago::find($this->ficha->user_id);
        $this->plantillas = Pgaleria::select('id','nombre','nombreinterno')->where('user_id',$this->userid)->orderBy('nombre','asc')->get()->toArray();
        $clientes=DB::table("clientes")
        ->select(DB::raw("concat(nombre,' ',apellidos,' ',telefono,' ',email,' ',nif) as label"),'id as value')
        ->where('user_id',$this->userid)
        ->orderBy('nombre','asc')
        ->get();
        $clientes=Utils::objectToArray($clientes);
        $this->clientes=json_encode($clientes);
        $this->galmd5=md5($this->idgaleria."ckeck");
        $this->productosdisponibles = Pproductos::where('user_id',$this->ficha->user_id)->orderBy('nombre','asc')->get()->toArray();
        $this->cargawhatsapp();
        $this->cargalistaproductos();
        //$this->calcularprecio();
        //log: :info($this->ficha);
        //Utils::vacialog();
        $files = Storage::disk("public")->allFiles("tmpgallery");
        foreach ($files as $file) {
            // eliminamos las imagenes de mas de 2 horas

            try {
                $time = Storage::disk('public')->lastModified($file);
            } catch (\Throwable $th) {
                // hay veces que no puede recuperar este dato, ni idea de la razon
                $time="ERROR";
            }
            if($time=="ERROR")
                continue;

            $fileModifiedDateTime = Carbon::parse($time); // vale, saca dos horas menos pero para esto da lo mismo
            //$fileModifiedDateTime = Carbon::parse($time)->addHour(2); // dos horas menos que la que da crabon now
            $file2hour=Carbon::parse($time)->addHour(3);
            //Log::info($file);
            //Log::info($fileModifiedDateTime);
            //Log::info($file2hour);
            //Log::info(Carbon::now());
            //Log::info(Carbon::now()->gt($file2hour));
            if (Carbon::now()->gt($file2hour)) {
                 Storage::disk("public")->delete($file);
                 //log: :info("borra ".$file);
             }
         }
        $files = Storage::disk("local")->allFiles("livewire-tmp");
        foreach ($files as $file) {
            // eliminamos las imagenes de mas de 2 horas
            $time = Storage::disk('local')->lastModified($file);
            $fileModifiedDateTime = Carbon::parse($time); // vale, saca dos horas menos pero para esto da lo mismo
            //$fileModifiedDateTime = Carbon::parse($time)->addHour(2); // dos horas menos que la que da crabon now
            //$file2hour=Carbon::parse($time)->addHour(4);
            $file2hour=Carbon::parse($time)->addHour(3);
            //log: :info($file2hour." ".Carbon::now());
            //log: :info(Carbon::now()->gt($file2hour));
            if (Carbon::now()->gt($file2hour)) {
                 Storage::disk("local")->delete($file);
                 //log: :info("borra ".$file);
             }
         }

         $storage_path = storage_path('app/public/tmpgallery')."/";
         $nombrezip="galeria_".$this->idgaleria."_".md5('cucu'.$this->idgaleria).".zip";
         if(File::exists($storage_path.$nombrezip)){
            File::delete($storage_path.$nombrezip);
         }
 
    }
    
    public function cargawhatsapp(){
        $this->clientetelefono="";
        $x=Cliente::select('telefono')->where('user_id',$this->userid)->where('id',$this->clienteid)->limit(1)->get();
        if(count($x)>0){
            $this->clientetelefono=$x[0]->telefono;
            if(strlen($this->clientetelefono)==9)
                $this->clientetelefono="34".$this->clientetelefono;
        }
        $xuser2 = User2::find($this->userid);
        $this->nombreempresa=$xuser2->nombre;


    }
    
    public function cargalistaproductos(){
        $this->productos=Productosgaleria::where('user_id',$this->ficha->user_id)->where('galeria_id',$this->idgaleria)->orderBy('position','asc')->orderBy('id','asc')->get()->toArray();
        $this->productos=Utils::objectToArray($this->productos);
        $this->productosadicionales=0;
        $this->productosincluidos=0;
        $ordenados=false;
        foreach($this->productos as $key=>$producto){
            //$this->productos[$key]['anotaciones']=Utils::anotacionesproductotoexternalphoto($this->productos[$key]['anotaciones'],$producto['id'],$this->ficha->user_id);
            $this->productos[$key]['anotaciones']="";
            if($this->productos[$key]['position']>0)
                $ordenados=true;
            $this->productos[$key]['incluido']=$producto['incluido']==1?true:false;
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
            if($this->productos[$key]['incluido']){
                $this->productosincluidos++;
            }else{
                $this->productosadicionales++;
            }
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
        }
        if(!$ordenados){
            $inc=0;
            $noinc=0;
            foreach($this->productos as $key=>$producto){
                $idd=$producto['id'];
                if($producto['incluido']){
                    $inc++;
                    $this->productos[$key]['position']=$inc;
                    Productosgaleria::where('id',$idd)->update(['position'=>$inc]);
                }
                if(!$producto['incluido']){
                    $noinc++;
                    $this->productos[$key]['position']=$noinc;
                    Productosgaleria::where('id',$idd)->update(['position'=>$noinc]);
                }
            }
        }
    }

    public function moveprod($idd,$incluido,$poss,$izder){
        if($izder==1){
            // a la izquierda
            $x=Productosgaleria::select('position')->where('galeria_id',$this->idgaleria)->where('incluido',$incluido)->where('position','<',$poss)->orderBy('position','desc')->limit(1)->get();
            if(count($x)==0){
                //log: :info("el primero");
                return;
            }
            $possn=($x[0]->position);
        }
        if($izder==2){
            // a la derecha
            $x=Productosgaleria::select('position')->where('galeria_id',$this->idgaleria)->where('incluido',$incluido)->where('position','>',$poss)->orderBy('position','asc')->limit(1)->get();
            if(count($x)==0){
                //log: :info("el ultimo");
                return;
            }
            $possn=($x[0]->position);
        }
        Productosgaleria::where('galeria_id',$this->idgaleria)->where('position',$poss)->update(['position'=>10000]);
        Productosgaleria::where('galeria_id',$this->idgaleria)->where('position',$possn)->update(['position'=>$poss]);
        Productosgaleria::where('galeria_id',$this->idgaleria)->where('position',10000)->update(['position'=>$possn]);
        $this->cargalistaproductos();
    }

    // producto sele imagenes
    public function cancelarseleccionfotoproducto(){
        $this->galeriabis=$this->galeria;
        $this->galeriabiscount=0;
        $this->productoseleccionid=-1;
    }
    public function cargarselecciondefotosproducto($key){

        $this->cargalistaproductos();

        $this->versolofotosproductos=1;
        if($this->ficha->pagado||$this->ficha->pagadomanual||$this->ficha->seleccionconfirmada){
            $this->versolofotosproductos=2;
        }

        $this->productoseleccionid=$key;
        $prod=$this->productos[$key];
        $selec=$prod['seleccionfotos'];
        $desdedonde=$prod['fotosdesde']; // 2 seleccionadas 3 no seleccionadas 4 solo con anotaciones
        $seleccion=[];
        if(strlen($selec)>0){
            $seleccion=json_decode($selec,true);
        }
        //$this->galeriabis=null;
        //log: :info($this->galeria);
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
            //log: :info($this->galeriabis[$key]['id']);
            if(count($seleccion)>0){
                $found_key = array_search($gal['id'], array_column($seleccion, 'id'));
                //log: :info($found_key);
                if($found_key===false){
                    continue;
                }
                $this->galeriabiscount+=$seleccion[$found_key]['cantidad'];
                $this->galeriabis[$key2]['selectedprod']=true;
                $this->galeriabis[$key2]['cantidad']=$seleccion[$found_key]['cantidad']; // la cantidad
                $this->galeriabis[$key2]['notas']=$seleccion[$found_key]['notas']; // la cantidad
            }
        }
        //$this->dispatch('focusonproductosel', ['key' => $key]);
        //log: :info($this->galeriabis);
        $this->metadataprg2();
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
        if($this->ficha->pagado||$this->ficha->pagadomanual){
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
        $this->metadataprg2();
    }

    public function guardarseleccionfotoproducto($key){
        if($this->ficha->pagado||$this->ficha->pagadomanual){
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



        if($this->galeriabiscount>=$this->productos[$key]['numfotos'] && $preguntasok){
            $this->productos[$key]['seleccionada']=true;
            Productosgaleria::where('id',$prodid)->update([
                'seleccionada'=>true,
            ]);
        }

        if($this->productos[$key]['cantidad']==0)
        $this->productos[$key]['cantidad']=1;

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
    // fin interior producto sele imagenes

    public function addproducto($key,$_0adicional1incluido){

        $poss=1;
        $xa=Productosgaleria::select('position')->where('galeria_id',$this->idgaleria)->where('incluido',$_0adicional1incluido)->orderBy('position','desc')->limit(1)->get();
        if(count($xa)>0)
            $poss=$xa[0]->position;
        $poss++;

        $prodid=$this->productosdisponibles[$key];
        $prodid['galeria_id']=$this->idgaleria;
        $prodid['position']=$poss;
        if($_0adicional1incluido==1){
            $prodid['incluido']=true;
            $prodid['seleccionada']=$prodid['numfotos']==0?true:false;
        }
        unset($prodid['id']);
        unset($prodid['created_at']);
        unset($prodid['updated_at']);
        Productosgaleria::insert($prodid);
        $this->cargalistaproductos();
    }

    public function deleteproducto($id){
        Productosgaleria::where('id',$id)->delete();
        $this->cargalistaproductos();
        $this->calcularprecio();
    }

    public function marcarproducto($key){
        if($this->ficha->pagado||$this->ficha->pagadomanual){
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

    public function cargaranotaciones($key){
        $this->notascliente=$this->galeria[$key]['anotaciones'];
        $this->notasclientekey=$key;
    }

    public function guardaranotaciones(){
        $this->galeria[$this->notasclientekey]['anotaciones']=$this->notascliente;
        $idd=$this->galeria[$this->notasclientekey]['id'];
        Binarios::where('id',$idd)->update(['anotaciones'=>$this->notascliente]);
        $this->notascliente="";
    }

    public function marcarproductocantidad($key,$masmenos){
        if($this->ficha->pagado||$this->ficha->pagadomanual){
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
        //$this->cargal istaproductos();
    }

    public function marcaropcionadicional($key,$opcion){
        if($this->ficha->pagado||$this->ficha->pagadomanual){
            return;
        }
        $fld="selopc".$opcion;
        $idprod=$this->productos[$key]['id'];
        $valor=$this->productos[$key][$fld];
        //log: :info($fld);
        //log: :info($idprod);
        //log: :info($valor);
        //log: :info($this->productos[$key]);
        Productosgaleria::where('id',$idprod)->update([
            $fld=>$valor
        ]);
        $this->calcularprecio();
        //$this->carga listaproductos();
    }

    public function grabarespuesta($key,$opcion){
        $fld="respuesta".$opcion;
        $idprod=$this->productos[$key]['id'];
        $valor=$this->productos[$key][$fld];
        Productosgaleria::where('id',$idprod)->update([
            $fld=>$valor
        ]);
    }

    public function validarceros(){
        $this->ficha->caducidad=strlen($this->ficha->caducidad)==10?$this->ficha->caducidad:Carbon::now()->addYear();

        if(strlen($this->ficha->nombreinterno)==0)
            $this->ficha->nombreinterno=$this->ficha->nombre;
        if(strlen($this->ficha->nombre)==0)
            $this->ficha->nombre=$this->ficha->nombreinterno;

        if(strlen($this->ficha->numfotos)==0)
            $this->ficha->numfotos=0;
        if(strlen($this->ficha->maxfotos)==0)
            $this->ficha->maxfotos=0;
        if(strlen($this->ficha->preciogaleria)==0)
            $this->ficha->preciogaleria=0;
        if(strlen($this->ficha->preciogaleriacompleta)==0)
            $this->ficha->preciogaleriacompleta=0;
        if(strlen($this->ficha->entregado)==0)
            $this->ficha->entregado=0;
        if(strlen($this->ficha->preciofoto)==0)
            $this->ficha->preciofoto=0;
        if(strlen($this->ficha->pack1)==0)
            $this->ficha->pack1=0;
        if(strlen($this->ficha->pack1precio)==0)
            $this->ficha->pack1precio=0;
        if(strlen($this->ficha->pack2)==0)
            $this->ficha->pack2=0;
        if(strlen($this->ficha->pack2precio)==0)
            $this->ficha->pack2precio=0;
        if(strlen($this->ficha->pack3)==0)
            $this->ficha->pack3=0;
        if(strlen($this->ficha->pack3precio)==0)
            $this->ficha->pack3precio=0;
    }    
    
    public function updatemarcaagua(){
        Galeria::where('user_id',$this->userid)->where('id',$this->idgaleria)->update(['marcaagua'=>$this->ficha->marcaagua]);
    }

    public function update($silent=false,$validar=true){
        //$this->dispatch('livewire-upload-progress',['progress' => 80,  'title' => 'ATENCIÓN']);
        if(($this->ficha->pagado==true||$this->ficha->pagadomanual==true) && $this->ficha->fechapago==null){
            $current_date_time = Carbon::now()->toDateTimeString(); // Produces something like "2019-03-11 12:25:00"
            $this->ficha->fechapago=$current_date_time;
        }
        //$this->ficha->nohay descarga=$this->nohay descarga;
        $this->validarceros();
        if($validar)
            $this->validate();
        if(!$silent)
            $this->dispatch('mensajecorto',['type' => 'success',  'message' => 'datos actualizados',  'title' => 'ATENCIÓN']);
        if(strlen($this->ficha->nombre)==0){
            $this->ficha->nombre=$this->ficha->nombreinterno;
        }
        $this->ficha->update();
    }

    public function updatetoclient(){
        $this->validarceros();
        $this->validate();
        $this->ficha->update();
        //return redirect(route('galeriacliente',[$this->idgaleria,$this->galmd5]));
    }

    public function updateout(){
        //$this->validate();

        //log: :info($this->ficha->fechapago);
        if(($this->ficha->pagado==true||$this->ficha->pagadomanual) && $this->ficha->fechapago==null){
            $current_date_time = Carbon::now()->toDateTimeString(); // Produces something like "2019-03-11 12:25:00"
            $this->ficha->fechapago=$current_date_time;
        }
        //$this->ficha->nohay descarga=$this->nohay descarga;
        $this->validarceros();
        $this->validate();
        if(strlen($this->ficha->nombre)==0){
            $this->ficha->nombre=$this->ficha->nombreinterno;
        }
        $this->ficha->update();
        //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Faltan datos',  'title' => 'ATENCIÓN']);
        //if($end==1){
        //    return redirect(route($this->ruta.'-management'));
        //}
        return redirect(route('galeria-management'))->with('status','galería actualizada.');
    }

    public function vseccion($num)
    {
        $this->seccion=$num;
        $this->update(true,false);

        if(($num==3||$num==4) && !$this->imgloaded){
            $this->loadimages($this->versele);
        }
        
        $this->calcularprecio();
        
        $this->dispatch('$refresh');
    }
    
    public function mostrarsoloproductos($num){
        $this->versoloproductos=$num; // 1 todos 2 seleccionados 3 no seleccionados
    }

    public function mostrarsolofotosproductos($num){
        $this->versolofotosproductossolonotas=false;
        if($num==4){
            $this->versolofotosproductossolonotas=true;
            $num=2;
        }
        $this->versolofotosproductos=$num; // 1 todos 2 seleccionados 3 no seleccionados 4 con anotaciones
    }

    public function move($idd,$poss,$izder){
        $regis=count($this->galeria);
        if($izder==1){
            // a la izquierda
            $x=Binarios::select('position')->where('galeria_id',$this->idgaleria)->where('position','<',$poss)->orderBy('position','desc')->limit(1)->get();
            if(count($x)==0){
                //log: :info("el primero");
                return;
            }
            $possn=($x[0]->position);
        }
        if($izder==2){
            // a la derecha
            $x=Binarios::select('position')->where('galeria_id',$this->idgaleria)->where('position','>',$poss)->orderBy('position','asc')->limit(1)->get();
            if(count($x)==0){
                //log: :info("el ultimo");
                return;
            }
            $possn=($x[0]->position);
        }


        //$this->tablabinarios::where('galeria_id',$this->idgaleria)->where('position',$poss)->update(['position'=>10000]);

        Binarios::where('galeria_id',$this->idgaleria)->where('position',$poss)->update(['position'=>10000]);
        Binarios::where('galeria_id',$this->idgaleria)->where('position',$possn)->update(['position'=>$poss]);
        Binarios::where('galeria_id',$this->idgaleria)->where('position',10000)->update(['position'=>$possn]);
        $this->loadimages($this->versele);
    }

    public function vaciarfotos(){
        if($this->versele!=1)
            $this->loadimages(1);
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";
        foreach($this->galeria as $key=>$gal){
            $idd=($gal['id']);
            $disks3->delete($filePaths3.$idd.".jpg");
            Binarios::where('id',$idd)->delete();
        }
        $this->loadimages($this->versele);
        $this->calcularprecio();
        $this->dispatch('postprocesadogalleryend',['type' => 'error',  'message' => '',  'title' => 'ATENCIÓN']);
    }

    public function vaciarfotosmarcadas(){
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";
        foreach($this->galeria as $key=>$gal){
            if($gal['selectedfordelete']){
                $idd=($gal['id']);
                $disks3->delete($filePaths3.$idd.".jpg");
                Binarios::where('id',$idd)->delete();
            }
        }
        $this->loadimages($this->versele);
        $this->calcularprecio();
        $this->dispatch('postprocesadogalleryend',['type' => 'error',  'message' => '',  'title' => 'ATENCIÓN']);
    }

    public function deleteimagegallery($idd){
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";
        $disks3->delete($filePaths3.$idd.".jpg");
        Binarios::where('id',$idd)->delete();
        $this->loadimages($this->versele);
        $this->calcularprecio();
    }

    public function descargarimagen($idd,$key){
        //$nombrefoto="oh myphoto_".$this->idgaleria."_".$this->galeria[$key]['nombre'];
        $nombrefoto=$this->galeria[$key]['nombre'];
        //log: :info($nombrefoto);return;
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";
        $bindata=$disks3->get($filePaths3.$idd.".jpg");
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $filee=$storage_path.$nombrefoto;
        File::put($filee,$bindata);
        return response()->file($filee, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename='.str_replace(" ","_",$nombrefoto)
        ]);
    }

    public function marcarimagen($keyy){
        // marca/desmarca la imagen que corresponda
        $idd=$this->galeria[$keyy]['id'];
        $vall=$this->galeria[$keyy]['selected'];
        Binarios::where('id',$idd)->update(['selected'=>$vall]);
        $this->calcularprecio();
        $this->metadataprg();
    }

    public function marcarimagennck($keyy){
        // marca/desmarca la imagen que corresponda
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
        $this->cancelarseleccionfotoproducto();
        $this->calcularprecio();
        $this->metadataprg();
    }

    public function fpago(){
        $this->calcularprecio();
    }

    public function loadimages($veo){
        $this->versele=$veo;
        switch($veo){
            case 1:
                $wr="selected in(1,0)";
                break;
            case 2:
                $wr="selected in(1)";
                break;
            case 3:
                $wr="selected in(0)";
                break;
            case 4:
                $wr="anotaciones!=''";
                break;
        }

        $storage_path = storage_path('app/public/tmpgallery')."/";
        $this->galeria=[];
        $x=Binarios::
            select('id','nombre','galeria_id','position','binario','anotaciones','selected',DB::raw('0 as selectedfordelete'))
            ->where('galeria_id',$this->idgaleria)
            ->whereRaw($wr)
            ->orderby('position','asc')
            ->orderby('id','asc')
            ->get()
            ->toArray();
        foreach($x as $key=>$y){
            $x[$key]['selected']=$x[$key]['selected']==1?true:false;
            $x[$key]['selectedfordelete']=$x[$key]['selectedfordelete']==1?true:false;
            //tmpgallery
            
            // falta securizar con md5
            $namesecure=$this->idgaleria."-".$x[$key]['id'].".jpg";
            $namesecure=$this->idgaleria."-".$x[$key]['id']."-".md5($x[$key]['id'].'gllery').".jpg";
            //log: :info($namesecure);

            $filee=$storage_path.$namesecure;
            if(File::exists($filee)){
                //log: :info("existe no graba");
                $x[$key]['binario']="";
                $x[$key]['file']=$namesecure;
            }else{
                $bindata=base64_decode($x[$key]['binario']);
                $x[$key]['binario']="";
                //$x[$key]['file']=$filee;
                $x[$key]['file']=$namesecure;
                File::put($filee,$bindata);
            }
        }
        $this->galeria=Utils::objectToArray($x);
        //log: :info($this->galeria);
        if(!$this->imgloaded){
            $x = User2::select('marcaagua')->where('id',$this->userid)->find($this->userid);
            $storage_path = storage_path('app/watermark')."/";
            $filee=$storage_path.$this->userid.".png";
            if(strlen($x->marcaagua)==0){
                //log: :info("sin marca agua");
                //log: :info(public_path('oh/oh_watermark.png'));
                File::copy(public_path('oh/oh_watermark.png'),$filee);
            }else{
                //log: :info("con marca");
                $data=base64_decode($x->marcaagua);
                File::put($filee,$data);
            }
        }
        $this->imgloaded=true;
        $this->metadataprg();
    }

    public function ordenar($asc1desc2){
        $x=Binarios::
            select('id','nombre','position')
            ->where('galeria_id',$this->idgaleria)
            ->orderby('nombre',$asc1desc2==1?"asc":"desc")
            ->get()
            ->toArray();
        usort($x, function($a, $b) {
            return strnatcasecmp($a['nombre'], $b['nombre']);
        });
        if($asc1desc2==2)
            $x=array_reverse($x,true);
        $iss=0;
        foreach($x as $key=>$y){
            $iss++;
            Binarios::where('id',$x[$key]['id'])->update(['position'=>$iss]);
        }
        $this->loadimages($this->versele);
    }

    public function metadataprg(){

        $this->lightroom="";
        $this->photomechanic="";
        $y=0;
        foreach($this->galeria as $x){
            if($x['selected']){
                $this->lightroom.=str_replace(".jpg","",$x['nombre']).' , ';
                $this->photomechanic.=str_replace(".jpg","",$x['nombre']).' + ';
                $y++;
            }
        }
        if($y>0){
            $this->lightroom=Utils::left($this->lightroom,strlen($this->lightroom)-3);
            $this->photomechanic=Utils::left($this->photomechanic,strlen($this->photomechanic)-3);
        }
    
    }

    public function metadataprg2(){
        //log: :info($this->galeriabis);
        $this->lightroom2="";
        $this->photomechanic2="";
        $y=0;
        foreach($this->galeriabis as $x){
            if($x['selectedprod']){
                $this->lightroom2.=str_replace(".jpg","",$x['nombre']).' , ';
                $this->photomechanic2.=str_replace(".jpg","",$x['nombre']).' + ';
                $y++;
            }
        }
        if($y>0){
            $this->lightroom2=Utils::left($this->lightroom2,strlen($this->lightroom2)-3);
            $this->photomechanic2=Utils::left($this->photomechanic2,strlen($this->photomechanic2)-3);
        }    
    }

    public function deleteimage(){
        $this->ficha->binario="";
        $this->ficha->binariomin="";
        $this->files=[];
        //$this->ficha->nohay descarga=$this->nohay descarga;
        $this->validate();
        $this->ficha->update();
    }

    public function messagefirst($xzx=""){
        $this->dispatch('alerta',['type' => 'success',  'message' => 'procesando',  'title' => 'ATENCIÓN']);
    }

    public function saveimage($xzx=""){

        switch($xzx){
            case "files":
                $this->saveimageencabezado();
                break;
            case "images":
                $this->savefotos();
                break;
        }
    }

    public function variable($tip,$ubica){
        $s='<span style="background-color: orange;color: white;padding: 4px 8px;text-align: center;border-radius: 5px;">NewByte</span> ';
        switch($tip){
            case 101:
            case 201:
                // check obligatorio
                $s=str_replace("NewByte","check",$s);
                break;
            case 102:
            case 202:
                // nombre empresa
                $s=str_replace("NewByte","nombreempresa",$s);
                break;
            case 103:
            case 203:
                // nombre propio empresa
                $s=str_replace("NewByte","nombreempresa2",$s);
                break;
            case 104:
            case 204:
                // domicilio empresa
                $s=str_replace("NewByte","domicilioempresa",$s);
                break;
            case 105:
            case 205:
                // cp empresa
                $s=str_replace("NewByte","cpempresa",$s);
                break;
            case 106:
            case 206:
                // poblacion empresa
                $s=str_replace("NewByte","poblacionempresa",$s);
                break;
            case 107:
            case 207:
                // provincia empresa
                $s=str_replace("NewByte","provinciaempresa",$s);
                break;
            case 108:
            case 208:
                // telefono empresa
                $s=str_replace("NewByte","telefonoempresa",$s);
                break;
            case 109:
            case 209:
                // email empresa
                $s=str_replace("NewByte","emailempresa",$s);
                break;
            case 110:
            case 210:
                // nombre cliente
                $s=str_replace("NewByte","nombrecliente",$s);
                break;
            case 111:
                // importe total del pago
                $s=str_replace("NewByte","importepago",$s);
                break;
            case 112:
                // importe total del pago
                $s=str_replace("NewByte","formapago",$s);
                break;
            case 113:
                // nombre de la galeria
                $s=str_replace("NewByte","nombregaleria",$s);
                break;
            case 211:
                // enlace a la galeria
                $s=str_replace("NewByte","enlace",$s);
                break;
            case 212:
                // caducidad
                $s=str_replace("NewByte","caducidad",$s);
                break;
            case 213:
                // clave
                $s=str_replace("NewByte","clave",$s);
                break;
            case 214:
                // fotos a elegir
                $s=str_replace("NewByte","fotosseleccion",$s);
                break;
        }
        if($ubica==3){
            $this->dispatch('addtoquill_fichaemailconfirmacuerpo', ['ob' => $s]);
        }
        if($ubica==2){
            $this->dispatch('addtoquill_fichaemailpagocuerpo', ['ob' => $s]);
        }
        if($ubica==1){
            $this->dispatch('addtoquill_fichaemailenviocuerpo', ['ob' => $s]);
        }
    }

    public function calcularprecio(){
        $result=Utils::calcularpreciogaleria($this->idgaleria,$this->ficha,$this->galeria,$this->formaspago,$this->productos);
        $this->precio=$result['precio'];
        $this->procesable=$result['procesable'];
        $this->seleccionadas=$result['seleccionadas'];
        $this->totalfotos=$result['totalfotos'];
        $this->sizegallery=$result['size'];
        $this->desgloseado=$result['desgloseado'];
    }

    public function selectplantilla($keyy)
    {
        $idplantilla=$this->plantillas[$keyy]['id'];
        //$ficha1 = User::where('id',$this->userid)->find($this->userid);
        //$ficha2 = User2::where('id',$this->userid)->find($this->userid);
        //$ficha3 = Cliente::find($this->ficha->cliente_id);
        $ficha4 = Pgaleria::find($idplantilla);
        //Log: :info($ficha4);
        
        $this->ficha->anotaciones=$ficha4->anotaciones;
        $this->ficha->anotaciones2=$ficha4->anotaciones2;
        $this->ficha->emailpagocuerpo=$ficha4->emailpagocuerpo;
        $this->ficha->emailenviocuerpo=$ficha4->emailenviocuerpo;
        $this->ficha->emailconfirmacuerpo=$ficha4->emailconfirmacuerpo;
        $this->ficha->emailpagoasunto=$ficha4->emailpagoasunto;
        $this->ficha->emailenvioasunto=$ficha4->emailenvioasunto;
        $this->ficha->emailconfirmaasunto=$ficha4->emailconfirmaasunto;
        $this->ficha->marcaagua=$ficha4->marcaagua==1?true:false;
        $this->ficha->nombresfotos=$ficha4->nombresfotos==1?true:false;
        $this->ficha->permitirdescarga=$ficha4->permitirdescarga;
        //$this->ficha->nohay descarga=$ficha4->nohay descarga==1?true:false;
        //$this->ficha->clic ambiapago=$ficha4->clic ambiapago==1?true:false;
        $this->ficha->nombre=$ficha4->nombre;
        $this->ficha->nombreinterno=$ficha4->nombreinterno;
        $this->ficha->binario=$ficha4->binario;
        $this->ficha->binariomin=$ficha4->binariomin;
        $this->ficha->numfotos=$ficha4->numfotos;
        $this->ficha->preciogaleria=$ficha4->preciogaleria;
        $this->ficha->preciogaleriacompleta=$ficha4->preciogaleriacompleta;
        $this->ficha->maxfotos=$ficha4->maxfotos;
        $this->ficha->preciofoto=$ficha4->preciofoto;
        $this->ficha->diascaducidaddescarga=$ficha4->diascaducidaddescarga;
        $this->ficha->permitircomentarios=$ficha4->permitircomentarios;
        $this->ficha->tipodepago=$ficha4->tipodepago;
        $this->ficha->pago1activo=$ficha4->pago1activo==1?true:false;
        $this->ficha->pago2activo=$ficha4->pago2activo==1?true:false;
        $this->ficha->pago3activo=$ficha4->pago3activo==1?true:false;
        $this->ficha->pago4activo=$ficha4->pago4activo==1?true:false;
        $this->ficha->pago5activo=$ficha4->pago5activo==1?true:false;
        $this->ficha->pago6activo=$ficha4->pago6activo==1?true:false;
        $this->ficha->incluido1=$ficha4->incluido1;
        $this->ficha->incluido2=$ficha4->incluido2;
        $this->ficha->incluido3=$ficha4->incluido3;
        $this->ficha->incluido4=$ficha4->incluido4;
        $this->ficha->incluido5=$ficha4->incluido5;
        $this->ficha->incluido6=$ficha4->incluido6;
        $this->ficha->incluido7=$ficha4->incluido7;
        $this->ficha->incluido8=$ficha4->incluido8;
        $this->ficha->incluido9=$ficha4->incluido9;
        $this->ficha->incluido10=$ficha4->incluido10;
        $this->ficha->opcional1=$ficha4->opcional1;
        $this->ficha->opcional2=$ficha4->opcional2;
        $this->ficha->opcional3=$ficha4->opcional3;
        $this->ficha->opcional4=$ficha4->opcional4;
        $this->ficha->opcional5=$ficha4->opcional5;
        $this->ficha->opcional6=$ficha4->opcional6;
        $this->ficha->opcional7=$ficha4->opcional7;
        $this->ficha->opcional8=$ficha4->opcional8;
        $this->ficha->opcional9=$ficha4->opcional9;
        $this->ficha->opcional10=$ficha4->opcional10;
        $this->ficha->precioopc1=$ficha4->precioopc1;
        $this->ficha->precioopc2=$ficha4->precioopc2;
        $this->ficha->precioopc3=$ficha4->precioopc3;
        $this->ficha->precioopc4=$ficha4->precioopc4;
        $this->ficha->precioopc5=$ficha4->precioopc5;
        $this->ficha->precioopc6=$ficha4->precioopc6;
        $this->ficha->precioopc7=$ficha4->precioopc7;
        $this->ficha->precioopc8=$ficha4->precioopc8;
        $this->ficha->precioopc9=$ficha4->precioopc9;
        $this->ficha->precioopc10=$ficha4->precioopc10;
        $this->ficha->pack1=$ficha4->pack1;
        $this->ficha->pack2=$ficha4->pack2;
        $this->ficha->pack3=$ficha4->pack3;
        $this->ficha->pack1precio=$ficha4->pack1precio;
        $this->ficha->pack2precio=$ficha4->pack2precio;
        $this->ficha->pack3precio=$ficha4->pack3precio;
        $x=DB::select("select date_add(curdate(),interval ".$ficha4->diascaducidad." day) as diaa")[0]->diaa;
        $this->ficha->caducidad=$x;
        $productos=Productos::where('galeria_id',$idplantilla)->get()->toArray();
        $productos=Utils::objectToArray($productos);
        foreach($productos as $key=>$prod){
            $productos[$key]['galeria_id']=$this->idgaleria;
            unset($productos[$key]['id']);
            unset($productos[$key]['created_at']);
            unset($productos[$key]['updated_at']);
        }
        Productosgaleria::where('user_id',$this->ficha->user_id)->where('galeria_id',$this->idgaleria)->delete();
        foreach($productos as $key=>$prod){
            Productosgaleria::insert($prod);
        }
        $this->cargalistaproductos();
        $this->dispatch('refreshquill_fichaanotaciones', ['ob' => $this->ficha->anotaciones]);
        $this->dispatch('refreshquill_fichaanotaciones2', ['ob' => $this->ficha->anotaciones2]);
        $this->dispatch('refreshquill_fichaemailpagocuerpo', ['ob' => $this->ficha->emailpagocuerpo]);
        $this->dispatch('refreshquill_fichaemailenviocuerpo', ['ob' => $this->ficha->emailenviocuerpo]);
        $this->dispatch('refreshquill_fichaemailconfirmacuerpo', ['ob' => $this->ficha->emailconfirmacuerpo]);
        $this->dispatch('$refresh');
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Plantilla importada',  'title' => 'ATENCIÓN']);
        $this->ficha->update();
    }

    public function setidcliente($idid)
    {
        $this->ficha->cliente_id=$idid;
        $this->clienteid=$idid;
        Galeria::where('user_id',$this->userid)->where('id',$this->idgaleria)->update(['cliente_id'=>$idid]);
        $this->cargawhatsapp();
        //$this->ficha->update();
    }

    public function sendclientwhatsapp(){
        $this->ficha->enviado=true;

        $fetched=false;
        for ($i=1; $i <=10 ; $i++) {
            $xa="dtenvio";
            if($i>1)
            $xa="dtenvio".$i;
        if($this->ficha->$xa==null){
                $this->ficha->$xa=Carbon::now();
                $fetched=true;
                break;
            }
        }
        if(!$fetched)
            $this->ficha->dtenvio10=Carbon::now();

        $this->validate();
        $this->ficha->update();
    }

    public function sendclient(){
        $this->validate();
        $this->ficha->update();
        $ruta=route('galeriacliente',[$this->idgaleria,$this->galmd5]);
        //$xuser2 = User2::select('logo','nombre','iban')->find($this->userid);
        $xuser = User::find($this->userid);
        $xuser2 = User2::find($this->userid);
        $logo=$xuser2->logo;
        $empresa=$xuser2->nombre;
        $x=Cliente::where('user_id',$this->userid)->where('id',$this->clienteid)->limit(1)->get();
        $clienteemail=$x[0]->email;
        $clientenombre=$x[0]->nombre." ".$x[0]->apellidos;
        $direcciones = [];
        $direcciones[]=['address'=>$clienteemail,'name'=>$clientenombre];
        $asunto="Aquí tienes tu galería en ".$empresa;
        $vista = "email.sendtoclient";

        // email personalizado
        $texto="";
        $clave=$this->ficha->clavecliente;
        $asu=$this->ficha->emailenvioasunto;
        $cue=$this->ficha->emailenviocuerpo;
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
                $texto=str_replace($s,$this->ficha->clavecliente,$texto);
                $clave="";
            }
    
            $s='<span style="    ">caducidad</span>';
            $texto=str_replace($s,Utils::fechaEsp($this->ficha->caducidad),$texto);
            $s='<span style="    ">fotosseleccion</span>';
            $texto=str_replace($s,$this->ficha->numfotos,$texto);
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
            $s='<strong style="    ">caducidad</strong>';
            $texto=str_replace($s,'<strong>'.Utils::fechaEsp($this->ficha->caducidad).'</strong>',$texto);
            $s='<strong style="    ">fotosseleccion</strong>';
            $texto=str_replace($s,'<strong>'.$this->ficha->numfotos.'</strong>',$texto);
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
            'caduco'=>$this->ficha->caducidad,
            'clave'=>$clave,
            'numfotos'=>$this->ficha->numfotos, 
            'personalizado'=>$texto,
        ];
        //Log::info($datos);
        $reply=Utils::cargarconfiguracionemailempresa($this->userid);
        //Log::info($this->userid);
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
            Log::info(Utils::extraererrormail($ex).' '.$clienteemail); // envía el error al registro de logs
            $this->errormail=Utils::extraererrormail($ex);
            $ok = false;
        }
        if($ok){
            $this->ficha->enviado=true;

            $fetched=false;
            for ($i=1; $i <=10 ; $i++) {
                $xa="dtenvio";
                if($i>1)
                $xa="dtenvio".$i;
            if($this->ficha->$xa==null){
                    $this->ficha->$xa=Carbon::now();
                    $fetched=true;
                    break;
                }
            }
            if(!$fetched)
                $this->ficha->dtenvio10=Carbon::now();
    
            $this->validate();
            $this->ficha->update();
            $this->dispatch('mensaje',['type' => 'success',  'message' => 'enlace enviado',  'title' => 'ATENCIÓN']);
        }
        if(!$ok){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al enviar mail',  'title' => 'ATENCIÓN']);
        }
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
        Galeria::where('user_id',$this->userid)->where('id',$this->idgaleria)->update(['cliente_id'=>$idid]);
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
        $this->cargawhatsapp();
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.galeria.edit');
    }

    public function savefotos(){
        if(empty($this->images)){
            return;
        }
        //Utils::vacialog();
        //log: :info("savefotos spatie");
        //$this->dispatch('scrolltop',['title' => 'ATENCIÓN']);
        $storage_path = storage_path('app/photos')."/";
        $storage_pathtmp = storage_path('app/public/tmpgallery')."/";
        $livewiretemp = storage_path('app/livewire-tmp')."/";
        $stw = storage_path('app/watermark')."/";
        $filew=$stw.$this->userid.".png";
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";

        $cantifotoactual=count($this->galeria);
        $cantiparasubir=count($this->images);
        $actual=0;
        $sustitucion=false;

        //Log: :info($this->images);

        foreach($this->images as $imagen){
            //$this->dispatch('livewire-upload-progress',['progress' => 80,  'title' => 'ATENCIÓN']);
            $actual++;
            $this->textobackdrop="procesando fotografía $actual de $cantiparasubir";
            $this->dispatch('postprocesadogalleryrefresh',['type' => 'error',  'message' => $this->textobackdrop,  'title' => 'ATENCIÓN']);
            $mime=$imagen->getMimeType();
            //Log: :info($imagen);
            $nombreoriginal=$imagen->getClientOriginalName();
            $nombretemporal=$imagen->getFileName();
            // mueve foto a dir photos con nombre temporal
            $rnd=str()->random();
            //$rnd=Utils::randomString(5);
            if(is_null($rnd))
                $rnd=Utils::randomString(5);
            $photoname=$this->userid."_".$rnd.".jpg";
            //$imagen->storeAs('photos',$photoname);
            //$filee=$storage_path.$photoname;
            //$fileemin=str_replace('.jpg','_min.jpg',$filee);
            //$fileemin=str_replace($storage_path,$storage_pathtmp,$filee);
            $filee=$livewiretemp.$nombretemporal; // no saltamos storeas
            //$fileemin=str_replace($livewiretemp,$storage_pathtmp,$filee);
            $fileemin=str_replace($livewiretemp,$storage_pathtmp,$filee.".avif");
            //$fileemin=str_replace('.jpg','.avif',$fileemin);
            // cargamos en $image
            $image = Spatie::load($filee);
            $width=$image->getWidth();
            $height=$image->getHeight();
            //1920 - 1080   widt - height
            //800  - x      800  - x 


            if($width>$height){
                //horizontal segun parece se ve peor
                //Log: :info("horizontal");
                $image->Fit(Fit::Contain,2048,   intval((2048*$height)/$width)  );
            }
            if($width<=$height){
                //vertical
                //Log: :info("vertical");
                $image->Fit(Fit::Contain,1024,   intval((1024*$height)/$width)  );
            }


            //$image->Fit(Fit: :Contain,1024, intval((1024*$height)/$width));
            //$image = Image ::make($filee);
            //$image->resize(800, null);
            if($this->ficha->marcaagua){
                $image->watermark($filew,
                AlignPosition::MiddleMiddle,
                //AlignPosition::MiddleMiddle,
                width:90,widthUnit:Unit::Percent,
                height:90,heightUnit:Unit::Percent,
                fit: Fit::Contain,
                alpha: 50
                );

                //$image->watermark($filew,
                //    paddingX: 10,
                //    paddingY: 10,
                //    paddingUnit: Unit::Percent
                //); // 10% padding around the watermark
            }
            $image->save($fileemin);
            $base64min=base64_encode(File::get($fileemin));

            $x=DB::select("select position from binarios where galeria_id=".$this->idgaleria." order by position desc limit 1");
            $poss=1;
            if($x){
                $poss=$x[0]->position+1;
            }
            $orisize=File::size($filee);



            // si existe la fulmino
            $x=Binarios::
            select('id','position')->where('galeria_id',$this->idgaleria)->where('nombre',$nombreoriginal)->get()->toArray();
            if($x){
                $iddelete=$x[0]['id'];
                $poss=$x[0]['position'];
                Binarios::where('id',$iddelete)->delete();
                $disks3->delete($filePaths3.$iddelete.".jpg");
                $sustitucion=true;
            }
            //

            $idimage=Binarios::insertGetId([
                'galeria_id'=>$this->idgaleria,
                'nombre'=>$nombreoriginal,
                'anotaciones'=>'',
                'binario'=>$base64min,
                'selected'=>false,
                'originalsize'=>$orisize,
                'position'=>$poss,
                'created_at'=>Carbon::now()->toDateTimeString(),
            ]);

            // upload a aws???!!!???
            if($this->userid!=-6){
                $disks3->put($filePaths3.$idimage.".jpg", file_get_contents($filee));
            }
            if(strlen($this->ficha->binario)==0){
                $fileemin1=str_replace('.jpg','_min1.avif',$filee);
                $filee1=str_replace('.jpg','.avif',$filee);
                $image = Spatie::load($filee);
                $width=$image->getWidth();
                $height=$image->getHeight();
                //1920 - 1080   widt - height
                //800  - x      800  - x
                //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
                $image->Fit(Fit::Crop,425,239)->save($fileemin1);
        
                $image = Spatie::load($filee);
                $width=$image->getWidth();
                $height=$image->getHeight();
                //1920 - 1080   widt - height
                //800  - x      800  - x
                //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
                $image->Fit(Fit::Crop,1920,1080)->save($filee1);
                $this->base64=base64_encode(File::get($filee1));
                $this->base64min=base64_encode(File::get($fileemin1));
                $this->ficha->binario=$this->base64;
                $this->ficha->binariomin=$this->base64min;
                File::delete($fileemin1);
                File::delete($filee1);
                //$this->ficha->nohay descarga=$this->nohay descarga;
                //$this->validate();
                $this->ficha->update();
            }

            File::delete($filee);
            //File::move($filee,$storage_path.'tos3_'.$idimage.".jpg");
            //
            $filee2=$storage_pathtmp.$this->idgaleria."-".$idimage.".jpg";
            //$bindata=base64_decode($base64min);
            //File::put($filee2,$bindata);
            File::move($fileemin,$filee2);

            $this->galeria[]=[
                'id'=>$idimage,
                'nombre'=>$nombreoriginal,
                'galeria_id'=>$this->idgaleria,
                'position'=>$poss,
                'binario'=>'',
                'anotaciones'=>'',
                'selected'=>false,
                'file'=>$this->idgaleria."-".$idimage.".jpg"
            ];
            //log: :info("end storage");
        }

        if($cantifotoactual==0){
            // no habia ninguna foto en la galeria
            $this->ordenar(1);
        }

        $this->files=[];
        $this->images=[];
        //log: :info("savefotos end");
        //$this->);
        //$this->dispatch('alerta',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        $this->dispatch('postprocesadogalleryend',['type' => 'error',  'message' => $this->textobackdrop,  'title' => 'ATENCIÓN']);
        $this->textobackdrop="";
        $this->dispatch('scrolltop',['title' => 'ATENCIÓN']);
        //$this->dispatch('postprocesado_end5',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        $this->calcularprecio();
        if($sustitucion){
            $this->loadimages($this->versele);
        }
    }

    public function endmultiupload(){
        $this->ficha = Galeria::where('user_id',$this->userid)->find($this->idgaleria);
        $this->loadimages($this->versele);
        $this->calcularprecio();
        $this->dispatch('scrolltop',['title' => 'ATENCIÓN']);
    }

    public function toencabezado($idd){
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";
        $contenido=$disks3->get($filePaths3.$idd.".jpg");
        //log: :info($contenido);
        $storage_path = storage_path('app/photos')."/";
        $this->photoname=$this->userid."_".Utils::randomString(5).".avif";
        $fileeoriginal=$storage_path.$idd.".avif";
        $filee=$storage_path.$this->photoname;
        //$fileemin=str_replace('.jpg','_min.jpg',$filee);
        $fileemin=str_replace('.avif','_min.avif',$filee);
        
        File::put($fileeoriginal,$contenido);
        
        $image = Spatie::load($fileeoriginal);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,425, (425*$height)/$width );
        $image->Fit(Fit::Crop,425, 239 );
        $image->save($fileemin);

        $image = Spatie::load($fileeoriginal);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
        $image->Fit(Fit::Crop,1920,1080);
        $image->save($filee);
        $this->ficha->binario=base64_encode(File::get($filee));
        $this->ficha->binariomin=base64_encode(File::get($fileemin));
        //$this->ficha->nohay descarga=$this->nohay descarga;
        $this->validate();
        $this->ficha->update();
        File::delete($fileeoriginal);
        $contenido="";
        //$this->seccion=6;
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        $this->dispatch('scrolltop',['title' => 'ATENCIÓN']);
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'se ha establecido esta imagen como portada de la galería',  'title' => 'ATENCIÓN']);
    }

    public function saveimageencabezado(){
        if(empty($this->files)){
            return;
        }
        //$photoname=$this->userid."_".Utils::randomString(5).".jpg";
        $photoname=$this->userid."_".Utils::randomString(5).".avif";
        $nombreoriginal=$this->files[0]->getClientOriginalName();
        $this->files[0]->storeAs('photos',$photoname);
        $storage_path = storage_path('app/photos')."/";
        $filee=$storage_path.$photoname;
        //$fileemin=str_replace('.jpg','_min.jpg',$filee);
        //$fileemin=str_replace('.jpg','_min.avif',$filee);
        $fileemin=str_replace('.avif','_min.avif',$filee);
        $image = Spatie::load($filee);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
        $image->Fit(Fit::Crop,425,239)->save($fileemin);

        $image = Spatie::load($filee);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
        $image->Fit(Fit::Crop,1920,1080)->save($filee);

        $this->base64=base64_encode(File::get($filee));
        $this->base64min=base64_encode(File::get($fileemin));
        $this->ficha->binario="";
        $this->ficha->binario=$this->base64;
        $this->ficha->binariomin=$this->base64min;
        File::delete($filee);
        File::delete($fileemin);
        $this->files=[];
        $this->images=[];
        //$this->ficha->nohay descarga=$this->nohay descarga;
        $this->validate();
        $this->ficha->update();
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
    }

    public function descargaclear(){
        $this->descargadisponible=false;
        $this->rutadescarga="";
        $this->peticiondescarga=false;
    }
    
    public function descargalista(){
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

    public function descargargaleria($_12){
        // 1 completa 2 solo seleccion 3 mi seleccion / anulada
        $x=Galeria::select('nombre')->where('user_id',$this->userid)->find($this->idgaleria);
        if(!$x){
            $this->dispatch('postprocesadogalleryend',['type' => 'error',  'message' => '',  'title' => 'ATENCIÓN']);
            return;
        }

        $x=Binarios::select('id','nombre')
            ->where('galeria_id',$this->idgaleria)
            ->where('selected','>=',$_12==1?0:1)
            ->get();
        if(count($x)==0){
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'nada seleccionado',  'title' => 'ATENCIÓN']);
            return;
        }
        $url=route('downloads.process2',[$this->idgaleria,md5('cucu'.$this->idgaleria),$_12]);
        //Log: :info($url);
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
        $this->peticiondescarga=true;
        return;

        $storage_path = storage_path('app/public/tmpgallery')."/";
        $storage_path = storage_path('app/livewire-tmp')."/";
        $nombrezip=str_replace(" ","_",$x->nombre.($_12==1?"_completa":"_seleccion").".zip");
        $zip = new ZipArchive;
        $zipFileName = $nombrezip;
        if ($zip->open($storage_path.$nombrezip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        }else{
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al crear el paquete comprimido',  'title' => 'ATENCIÓN']);
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            return;
        }
        switch($_12){
            case 1:
            case 2:
                $x=Binarios::select('id','nombre')
                    ->where('galeria_id',$this->idgaleria)
                    ->where('selected','>=',$_12==1?0:1)
                    ->get();
                break;
            case 3:
                $wherein="-1";
                foreach($this->galeria as $key=>$gal){
                    if($gal['selectedfordelete']){
                        $idd=($gal['id']);
                        $wherein.=",$idd";
                    }
                }
                $x=Binarios::select('id','nombre')
                    ->where('galeria_id',$this->idgaleria)
                    ->whereRaw("id in ($wherein)")
                    ->get();
                break;
        }
        if(count($x)==0){
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'nada seleccionado',  'title' => 'ATENCIÓN']);
            return;
        }
        foreach($x as $y){
            //log: :info($y->nombre);
            //log: :info($y->id);
            //$nombrefoto="oh myphoto_".$this->idgaleria."_".$y->nombre;
            $nombrefoto=$y->nombre;
            //log: :info($nombrefoto);return;
            $disks3 = Storage::disk('sftp');
            $filePaths3 = '/galerias/'.$this->userid."/";
            $bindata=$disks3->get($filePaths3.$y->id.".jpg");
            $filee=$storage_path.$nombrefoto;
            $zip->addFromString($nombrefoto, $bindata);
        }
        $zip->close();
        $this->dispatch('postprocesadogalleryend',['type' => 'error',  'message' => '',  'title' => 'ATENCIÓN']);
        //$this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        return response()->file($storage_path.$nombrezip, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$nombrezip
        ])->deleteFileAfterSend(true);

    }

    public function descargarproducto($key,$_12){
        //log: :info($key);
        $prod=$this->galeriabis;
        //log: :info($prod);
        //return;
        // 1 completa 2 solo seleccion
        $storage_path = storage_path('app/public/tmpgallery')."/";
        $storage_path = storage_path('app/livewire-tmp')."/";
        $nombrezip=str_replace(" ","_",$this->ficha->nombre.($_12==1?"_producto_completa":"_producto_seleccion").".zip");
        $canti=0;
        $cantisel=0;
        foreach($prod as $entra){
            $canti++;
            if($entra['selectedprod'])
                $cantisel++;
        }
        if($canti==0 && $_12==1){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'nada que descargar',  'title' => 'ATENCIÓN']);
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            return;
        }
        if($cantisel==0 && $_12==2){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'nada que descargar',  'title' => 'ATENCIÓN']);
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            return;
        }
        $zip = new ZipArchive;
        $zipFileName = $nombrezip;
        if ($zip->open($storage_path.$nombrezip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        }else{
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'error al crear el paquete comprimido',  'title' => 'ATENCIÓN']);
            $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
            return;
        }
        foreach($prod as $y){
            if($y['selectedprod']==false && $_12==2){
                continue;
            }
            //$nombrefoto="oh myphoto_".$key."_".$y['nombre'];
            $nombrefoto=$y['nombre'];
            $disks3 = Storage::disk('sftp');
            $filePaths3 = '/galerias/'.$this->userid."/";
            $bindata=$disks3->get($filePaths3.$y['id'].".jpg");
            $filee=$storage_path.$nombrefoto;
            $zip->addFromString($nombrefoto, $bindata);
        }
        $zip->close();
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        return response()->file($storage_path.$nombrezip, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$nombrezip
        ])->deleteFileAfterSend(true);
    }




}
