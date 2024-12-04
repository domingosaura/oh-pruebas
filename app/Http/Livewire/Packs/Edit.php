<?php

namespace App\Http\Livewire\Packs;

use App\Models\Packs;
use App\Models\User;
use App\Models\User2;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use File;
use App\Http\Utils;
use DB;
use Log;
use Livewire\WithFileUploads;
use Image;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;

class Edit extends Component
{
    use WithFileUploads;
    public Packs $ficha;
    public $seccion = 1;
    public $idsesion = 0;
    public $userid;
    public $photoname;
    public $base64="";
    public $base64min="";
    public $files=[];
    public $galeria=[];
    public $productos=[];
    public $versoloproductos=1; // 1 todos 2 seleccionados 3 no seleccionados
    public $productosdisponibles=[];
    public $productosincluidos=0;
    public $productosadicionales=0;
    //public $nohay descarga;

    use AuthorizesRequests;
    
    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'ficha.nombre' => 'required',
            'ficha.nombreinterno' => '',
            'ficha.anotaciones' => '',
            'ficha.anotaciones2' => '',
            'ficha.activa' => '',
            'ficha.sinfecha' => '',
            'ficha.preciopack' => 'required|numeric',
            'ficha.precioreserva' => 'required|numeric',
            'ficha.minutos' => 'required|numeric',
            'ficha.binario' => '',
        ];
    }

    public function mount($id){
        $this->userid=Auth::id();
        $this->idsesion=$id;
        $this->ficha = Packs::where('user_id',$this->userid)->find($id);
        $this->ficha->activa=$this->ficha->activa==1?true:false;
        $this->ficha->sinfecha=$this->ficha->sinfecha==1?true:false;
        $this->ficha->obliga1=$this->ficha->obliga1==1?true:false;
        $this->ficha->obliga2=$this->ficha->obliga2==1?true:false;
        $this->ficha->obliga3=$this->ficha->obliga3==1?true:false;
        $this->ficha->obliga4=$this->ficha->obliga4==1?true:false;
        $this->ficha->obliga5=$this->ficha->obliga5==1?true:false;
        $this->ficha->obliga6=$this->ficha->obliga6==1?true:false;
        $this->ficha->obliga7=$this->ficha->obliga7==1?true:false;
        $this->ficha->obliga8=$this->ficha->obliga8==1?true:false;
        $this->ficha->obliga9=$this->ficha->obliga9==1?true:false;
        $this->ficha->obliga10=$this->ficha->obliga10==1?true:false;
    }
    
    public function update(){
        $this->validate();
        if(strlen($this->ficha->nombre)==0){
            //$this->ficha->nombre=$this->ficha->nombreinterno;
        }
        $this->ficha->update();
        $this->dispatch('mensajecorto',['type' => 'success',  'message' => 'datos actualizados',  'title' => 'ATENCIÓN']);
    }

    public function updateout(){
        $this->validate();
        if(strlen($this->ficha->nombre)==0){
            //$this->ficha->nombre=$this->ficha->nombreinterno;
        }
        $this->ficha->update();
        return redirect(route('packs-management'))->with('status','galería actualizada.');
    }

    public function deleteimage(){
        $this->ficha->binario="";
        $this->files=[];
        //$this->ficha->nohay descarga=$this->nohay descarga;
        $this->ficha->update();
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.packs.edit');
    }

    public function saveimage($xzx=""){
        $this->saveimageencabezado();
    }

    public function saveimageencabezado(){
        if(empty($this->files)){
            return;
        }
        $photoname=$this->userid."_".Utils::randomString(5).".jpg";
        $nombreoriginal=$this->files[0]->getClientOriginalName();
        $this->files[0]->storeAs('photos',$photoname);
        $storage_path = storage_path('app/photos')."/";
        $filee=$storage_path.$photoname;
        //$fileemin=str_replace('.jpg','_min.jpg',$filee);
        $fileemin=str_replace('.jpg','_min.avif',$filee);
        $image = Spatie::load($filee);
        $width=$image->getWidth();
        $height=$image->getHeight();
        //1920 - 1080   widt - height
        //800  - x      800  - x
        //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
        $image->Fit(Fit::Crop,425,239)->save($fileemin);
        $this->base64=base64_encode(File::get($fileemin));
        $this->ficha->binario="";
        $this->ficha->binario=$this->base64;
        File::delete($filee);
        File::delete($fileemin);
        $this->files=[];
        $this->ficha->update();
        $this->dispatch('postprocesado_end',['type' => 'success',  'message' => 'todas las fotografías añadidas',  'title' => 'ATENCIÓN']);
    }
}
