<?php

namespace App\Http\Livewire\Productos;

use App\Models\Pproductos;
use App\Models\Productos;
use App\Models\Formaspago;
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
    public Pproductos $ficha;
    public $seccion = 1;
    public $idmov = 0;
    public $userid;
    public $photoname;
    public $base64="";
    public $files=[];
    public $formaspago=[];

    use AuthorizesRequests;
    
    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'ficha.nombre' => 'required|max:100',
            'ficha.anotaciones' => '',
            'ficha.permitecantidad' => '',
            'ficha.binario' => '',
            'ficha.numfotos' => 'required|numeric',
            'ficha.fotosdesde' => '',
            'ficha.precioproducto' => 'required|numeric',
            'ficha.pregunta1' => '',
            'ficha.respuesta1' => '',
            'ficha.pregunta2' => '',
            'ficha.respuesta2' => '',
            'ficha.pregunta3' => '',
            'ficha.respuesta3' => '',
            'ficha.pregunta4' => '',
            'ficha.respuesta4' => '',
            'ficha.pregunta5' => '',
            'ficha.respuesta5' => '',
            'ficha.txtopc1' => '',
            'ficha.txtopc2' => '',
            'ficha.txtopc3' => '',
            'ficha.txtopc4' => '',
            'ficha.txtopc5' => '',
            'ficha.selopc1' => '',
            'ficha.selopc2' => '',
            'ficha.selopc3' => '',
            'ficha.selopc4' => '',
            'ficha.selopc5' => '',
            'ficha.pre1obligatorio' => '',
            'ficha.pre2obligatorio' => '',
            'ficha.pre3obligatorio' => '',
            'ficha.pre4obligatorio' => '',
            'ficha.pre5obligatorio' => '',
            'ficha.precio1' => 'required|numeric',
            'ficha.precio2' => 'required|numeric',
            'ficha.precio3' => 'required|numeric',
            'ficha.precio4' => 'required|numeric',
            'ficha.precio5' => 'required|numeric',
            'ficha.numfotosadicionales' => 'required|numeric',
            'ficha.preciofotoadicional' => 'required|numeric',
        ];
    }

    public function mount($id){
        $this->userid=Auth::id();
        $this->idmov=$id;
        $this->ficha = Pproductos::where('user_id',$this->userid)->find($id);
        $this->ficha->permitecantidad=$this->ficha->permitecantidad==1?true:false;
        $this->ficha->selopc1=$this->ficha->selopc1==1?true:false;
        $this->ficha->selopc2=$this->ficha->selopc2==1?true:false;
        $this->ficha->selopc3=$this->ficha->selopc3==1?true:false;
        $this->ficha->selopc4=$this->ficha->selopc4==1?true:false;
        $this->ficha->selopc5=$this->ficha->selopc5==1?true:false;
        $this->ficha->pre1obligatorio=$this->ficha->pre1obligatorio==1?true:false;
        $this->ficha->pre2obligatorio=$this->ficha->pre2obligatorio==1?true:false;
        $this->ficha->pre3obligatorio=$this->ficha->pre3obligatorio==1?true:false;
        $this->ficha->pre4obligatorio=$this->ficha->pre4obligatorio==1?true:false;
        $this->ficha->pre5obligatorio=$this->ficha->pre5obligatorio==1?true:false;
        //Log::info($this->ficha);
    }
    
    public function update(){
        $this->validate();
        $this->ficha->update();
        $this->updatedependientes();
        return redirect(route('productos-management'))->with('status','plantilla actualizada.');
    }

    public function deleteimage(){
        $this->ficha->binario="";
        $this->files=[];
        $this->ficha->update();
        $this->updatedependientes();
    }

    public function updatedependientes(){
        // pproductos es la base
        // productos son los productos de cada plantilla, hasta ahora independientes de pproductos
        // productosgaleria son los productos de la galeria independientes a todos los demas
        // ahora productos se actualizará con lo que cambiemos aqui
        $x=$this->ficha->toArray();
        unset($x['id']);
        unset($x['user_id']);
        unset($x['galeria_id']);
        unset($x['base_id']);
        unset($x['seleccionfotos']);
        unset($x['seleccionada']);
        unset($x['incluido']);
        unset($x['created_at']);
        $x['updated_at']=Carbon::now();
        Productos::where('user_id',$this->userid)->where('base_id',$this->idmov)->update($x);
        //Log::info($x);
        //unset($x)
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.productos.edit');
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
        
        $this->validate();
        $this->base64=base64_encode(File::get($fileemin));
        $this->ficha->binario="";
        $this->ficha->binario=$this->base64;
        $this->ficha->update();
        $this->updatedependientes();
        File::delete($filee);
        File::delete($fileemin);
        $this->files=[];
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
        //$this->dispatch('scrolltop',['title' => 'ATENCIÓN']);
    }

}
