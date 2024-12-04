<?php

namespace App\Http\Livewire\Plantillagaleria;

use App\Models\Pgaleria;
use App\Models\Formaspago;
use App\Models\Pproductos;
use App\Models\Productos;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use File;
use App\Http\Utils;
use DB;
use Log;
use Livewire\WithFileUploads;
use Image;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
use Carbon\Carbon;

class Edit extends Component
{

    use WithFileUploads;
    public Pgaleria $ficha;
    public $seccion = 1;
    public $idmov = 0;
    public $userid;
    public $photoname;
    public $base64="";
    public $base64min="";
    public $files=[];
    public $formaspago=[];
    public $productos=[];
    public $productosdisponibles=[];
    public $productoseleccionid=-1;
    public $productosincluidos=0;
    public $productosadicionales=0;
    public $plantillas;
    public $galeriabiscount=0;
    public $pagado=false; // solo se incluye para que exista ya que mismo objeto se usa aqui y en galeriacliente
    public $pagadomanual=false; // solo se incluye para que exista ya que mismo objeto se usa aqui y en galeriacliente
    public $seleccionconfirmada=false; // solo se incluye para que exista ya que mismo objeto se usa aqui y en galeriacliente

    use AuthorizesRequests;
    
    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'ficha.nombre' => '',
            'ficha.nombreinterno' => 'required',
            'ficha.anotaciones' => '',
            'ficha.anotaciones2' => '',
            'ficha.numfotos' => 'required|numeric',
            'ficha.maxfotos' => 'required|numeric',
            'ficha.preciogaleria' => 'required|numeric',
            'ficha.preciogaleriacompleta' => 'required|numeric',
            'ficha.preciofoto' => 'required|numeric',
            'ficha.diascaducidad' => 'required|numeric',
            'ficha.permitirdescarga' => '',
            'ficha.nohaydescarga' => '',
            'ficha.diascaducidaddescarga' => 'required|numeric',
            'ficha.marcaagua' => '',
            'ficha.nombresfotos' => '',
            'ficha.binario' => '',
            'ficha.tipodepago' => 'required|numeric',
            'ficha.binariomin' => '',
            'ficha.permitircomentarios' => '',
            'ficha.clicambiapago' => '',
            'ficha.pago1activo' => '','ficha.pago2activo' => '','ficha.pago3activo' => '','ficha.pago4activo' => '','ficha.pago5activo' => '','ficha.pago6activo' => '',
            'ficha.emailpagoasunto' => '','ficha.emailpagocuerpo' => '',
            'ficha.emailconfirmaasunto' => '','ficha.emailconfirmacuerpo' => '',
            'ficha.emailenvioasunto' => '','ficha.emailenviocuerpo' => '',
            'ficha.pack1' => 'required|numeric','ficha.pack1precio' => 'required|numeric',
            'ficha.pack2' => 'required|numeric','ficha.pack2precio' => 'required|numeric',
            'ficha.pack3' => 'required|numeric','ficha.pack3precio' => 'required|numeric',
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
        $this->userid=Auth::id();
        $this->idmov=$id;
        $this->ficha = Pgaleria::where('user_id',$this->userid)->find($id);
        $this->ficha->archivada=$this->ficha->archivada==1?true:false;
        //$this->ficha->permitirdescarga=$this->ficha->permitirdescarga==1?true:false;
        $this->ficha->nohaydescarga=$this->ficha->nohaydescarga==1?true:false;
        $this->ficha->marcaagua=$this->ficha->marcaagua==1?true:false;
        $this->ficha->nombresfotos=$this->ficha->nombresfotos==1?true:false;
        //$this->ficha->clic ambiapago=$this->ficha->clic ambiapago==1?true:false;
        $this->ficha->pago1activo=$this->ficha->pago1activo==1?true:false;
        $this->ficha->pago2activo=$this->ficha->pago2activo==1?true:false;
        $this->ficha->pago3activo=$this->ficha->pago3activo==1?true:false;
        $this->ficha->pago4activo=$this->ficha->pago4activo==1?true:false;
        $this->ficha->pago5activo=$this->ficha->pago5activo==1?true:false;
        $this->ficha->pago6activo=$this->ficha->pago6activo==1?true:false;
        $this->formaspago = Formaspago::find($this->ficha->user_id);
        $this->productosdisponibles = Pproductos::where('user_id',$this->ficha->user_id)->orderBy('nombre','asc')->get()->toArray();
        $this->cargalistaproductos();
        $this->plantillas = Pgaleria::
            select('id','nombre','nombreinterno')
            ->where('user_id',$this->userid)
            ->where('id',"<>",$id)
            ->orderBy('nombre','asc')
            ->get()->toArray();
        //log: :info($this->ficha);
        //log: :info($this->productos);
    }
    
    public function selectplantilla($keyy)
    {
        $idclon=$this->plantillas[$keyy]['id'];
        $pg=Pgaleria::where('id',$idclon)->get()->toArray()[0];
        $pg['created_at']=Carbon::now();
        $pg['updated_at']=Carbon::now();
        $pg['id']=$this->idmov;
        Pgaleria::where('id',$this->idmov)->delete();
        Pgaleria::insert($pg);
        $productos=Productos::where('galeria_id',$idclon)->get()->toArray();
        $productos=Utils::objectToArray($productos);
        foreach($productos as $key=>$prod){
            $productos[$key]['galeria_id']=$this->idmov;
            unset($productos[$key]['id']);
            unset($productos[$key]['created_at']);
            unset($productos[$key]['updated_at']);
        }
        foreach($productos as $key=>$prod){
            Productos::insert($prod);
        }
        return redirect(route('edit-plantillagaleria',$this->idmov));
        //$nombre=$this->plantillas[$keyy]['nombre'];
        //$texto=$this->plantillas[$keyy]['texto'];
        //$this->ficha->nombre=$nombre;
        //$this->ficha->texto=$texto;
        //$this->dispatch('refreshquill', ['ob' => $texto]);
    }

    public function cargalistaproductos(){
        $this->productos = Productos::where('user_id',$this->ficha->user_id)->where('galeria_id',$this->idmov)->orderBy('position','asc')->orderBy('id','asc')->get()->toArray();
        $this->productos=Utils::objectToArray($this->productos);
        $this->productosadicionales=0;
        $this->productosincluidos=0;
        $ordenados=false;
        foreach($this->productos as $key=>$producto){
            if($this->productos[$key]['position']>0)
                $ordenados=true;
            $this->productos[$key]['incluido']=$producto['incluido']==1?true:false;
            $this->productos[$key]['selopc1']=$producto['selopc1']==1?true:false;
            $this->productos[$key]['selopc2']=$producto['selopc2']==1?true:false;
            $this->productos[$key]['selopc3']=$producto['selopc3']==1?true:false;
            $this->productos[$key]['selopc4']=$producto['selopc4']==1?true:false;
            $this->productos[$key]['selopc5']=$producto['selopc5']==1?true:false;
            $this->productos[$key]['seleccionada']=$producto['seleccionada']==1?true:false;
            $this->productos[$key]['imagen']="/oh/img/gallery-generic.jpg";
            if($this->productos[$key]['incluido']){
                $this->productosincluidos++;
            }else{
                $this->productosadicionales++;
            }
            // fuera binarios cargan el modelo transfieren muchos datos
            $idsan=$this->productos[$key]['id'];
            $storage_path = storage_path('app/public/tmpgallery')."/";
            $filegal="galptprod".$idsan."-".md5($idsan).".jpg";
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
                //if($this->productos[$key]['position']>0)
                $idd=$producto['id'];
                if($producto['incluido']){
                    $inc++;
                    $this->productos[$key]['position']=$inc;
                    Productos::where('id',$idd)->update(['position'=>$inc]);
                }
                if(!$producto['incluido']){
                    $noinc++;
                    $this->productos[$key]['position']=$noinc;
                    Productos::where('id',$idd)->update(['position'=>$noinc]);
                }
            }
        }
    }

    public function moveprod($idd,$incluido,$poss,$izder){
        if($izder==1){
            // a la izquierda
            $x=Productos::select('position')->where('galeria_id',$this->idmov)->where('incluido',$incluido)->where('position','<',$poss)->orderBy('position','desc')->limit(1)->get();
            if(count($x)==0){
                //log: :info("el primero");
                return;
            }
            $possn=($x[0]->position);
        }
        if($izder==2){
            // a la derecha
            $x=Productos::select('position')->where('galeria_id',$this->idmov)->where('incluido',$incluido)->where('position','>',$poss)->orderBy('position','asc')->limit(1)->get();
            if(count($x)==0){
                //log: :info("el ultimo");
                return;
            }
            $possn=($x[0]->position);
        }
        Productos::where('galeria_id',$this->idmov)->where('position',$poss)->update(['position'=>10000]);
        Productos::where('galeria_id',$this->idmov)->where('position',$possn)->update(['position'=>$poss]);
        Productos::where('galeria_id',$this->idmov)->where('position',10000)->update(['position'=>$possn]);
        $this->cargalistaproductos();
    }

    public function marcarproducto($key){
        $valor=!$this->productos[$key]['seleccionada'];
        $this->productos[$key]['seleccionada']=$valor;
        Productos::where('id',$this->productos[$key]['id'])->update([
            'seleccionada'=>$valor
        ]);
        //$this->cargalistaproductos();
    }

    public function marcaropcionadicional($key,$opcion){
        $fld="selopc".$opcion;
        $idprod=$this->productos[$key]['id'];
        $valor=$this->productos[$key][$fld];
        //log: :info($fld);
        //log: :info($idprod);
        //log: :info($valor);
        //log: :info($this->productos[$key]);
        Productos::where('id',$idprod)->update([
            $fld=>$valor
        ]);
        //$this->cargalistaproductos();
    }

    public function addproducto($key,$_0adicional1incluido){

        $poss=1;
        $xa=Productos::select('position')->where('galeria_id',$this->idmov)->where('incluido',$_0adicional1incluido)->orderBy('position','desc')->limit(1)->get();
        if(count($xa)>0)
            $poss=$xa[0]->position;
        $poss++;

        $prodid=$this->productosdisponibles[$key];
        $prodid['galeria_id']=$this->idmov;
        $prodid['base_id']=$prodid['id'];
        $prodid['position']=$poss;
        if($_0adicional1incluido==1){
            $prodid['incluido']=true;
            //$prodid['seleccionada']=true;
        }
        unset($prodid['id']);
        unset($prodid['created_at']);
        unset($prodid['updated_at']);
        Productos::insert($prodid);
        $this->cargalistaproductos();
    }
    public function deleteproducto($id){
        Productos::where('id',$id)->delete();
        $this->cargalistaproductos();
    }

    public function update(){
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
        return redirect(route('plantillagaleria-management'))->with('status','plantilla actualizada.');
    }

    public function vseccion($num)
    {
        $this->seccion=$num;
    }
    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.plantillagaleria.edit');
    }

    public function comentarios(){
    }

    public function deleteimage(){
        $this->ficha->binario="";
        $this->ficha->binariomin="";
        $this->files=[];
    }

    public function saveimage($x=""){
        if(empty($this->files)){
            return;
        }
        $this->photoname=$this->userid."_".Utils::randomString(5).".jpg";
        $nombreoriginal=$this->files[0]->getClientOriginalName();
        $this->files[0]->storeAs('photos',$this->photoname);
        $storage_path = storage_path('app/photos')."/";
        $filee=$storage_path.$this->photoname;
        //$fileemin=str_replace('.jpg','_min.jpg',$filee);
        $fileemin=str_replace('.jpg','_min.avif',$filee);

        $image = Spatie::load($filee);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,425, (425*$height)/$width );
        $image->Fit(Fit::Crop,425, 239 );
        $image->save($fileemin);

        $image = Spatie::load($filee);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
        $image->Fit(Fit::Crop,1080,608);
        $image->save($filee);

        $this->base64=base64_encode(File::get($filee));
        $this->base64min=base64_encode(File::get($fileemin));
        $this->ficha->binario="";
        $this->ficha->binario=$this->base64;
        $this->ficha->binariomin=$this->base64min;
        File::delete($filee);
        File::delete($fileemin);
        $this->files=[];
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        $this->dispatch('scrolltop',['title' => 'ATENCIÓN']);
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
            case 111:
                // importe total del pago
                $s=str_replace("NewByte","importepago",$s);
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

    public function validarceros(){
        $this->ficha->caducidad=strlen($this->ficha->caducidad)==10?$this->ficha->caducidad:Carbon::now()->addYear();

        if(strlen($this->ficha->nombreinterno)==0)
            $this->ficha->nombreinterno=$this->ficha->nombre;
        if(strlen($this->ficha->nombre)==0)
            $this->ficha->nombre=$this->ficha->nombreinterno;

        if(strlen($this->ficha->diascaducidad)==0)
            $this->ficha->diascaducidad=10;
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

}
