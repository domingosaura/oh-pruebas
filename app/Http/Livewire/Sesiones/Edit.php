<?php

namespace App\Http\Livewire\Sesiones;

use App\Models\Sesiones;
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
    public Sesiones $ficha;
    public $seccion = 1;
    public $idsesion = 0;
    public $userid;
    public $photoname;
    public $base64="";
    public $base64min="";
    public $files=[];
    public $galeria=[];
    public $versoloproductos=1; // 1 todos 2 seleccionados 3 no seleccionados
    public $packs=[];
    public $seleccion=[];
    public $packsdisponibles=[];
    public $packsincluidos=0;
    //public $nohay descarga;

    use AuthorizesRequests;
    
    protected function rules(){
        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]
        return [
            'ficha.nombre' => 'required',
            'ficha.nombreinterno' => '',
            'ficha.antelacion' => 'required|numeric',
            'ficha.anotaciones' => '',
            'ficha.anotaciones2' => '',
            'ficha.activa' => '',
            'ficha.pregunta1' => '',
            'ficha.respuesta1' => '',
            'ficha.obliga1' => '',
            'ficha.pregunta2' => '',
            'ficha.respuesta2' => '',
            'ficha.obliga2' => '',
            'ficha.pregunta3' => '',
            'ficha.respuesta3' => '',
            'ficha.obliga3' => '',
            'ficha.pregunta4' => '',
            'ficha.respuesta4' => '',
            'ficha.obliga4' => '',
            'ficha.pregunta5' => '',
            'ficha.respuesta5' => '',
            'ficha.obliga5' => '',
            'ficha.pregunta6' => '',
            'ficha.respuesta6' => '',
            'ficha.obliga6' => '',
            'ficha.pregunta7' => '',
            'ficha.respuesta7' => '',
            'ficha.obliga7' => '',
            'ficha.pregunta8' => '',
            'ficha.respuesta8' => '',
            'ficha.obliga8' => '',
            'ficha.pregunta9' => '',
            'ficha.respuesta9' => '',
            'ficha.obliga9' => '',
            'ficha.pregunta10' => '',
            'ficha.respuesta10' => '',
            'ficha.obliga10' => '',
            'ficha.emailconfirmaasunto' => '',
            'ficha.emailconfirmacuerpo' => '',
            'ficha.emailconfirmacuerpo0' => '',
            'ficha.emailrecuerdaasunto' => '',
            'ficha.emailrecuerdacuerpo' => '',
            'ficha.binario' => '',
            'ficha.packs' => '',
        ];
    }

    public function mount($id){
        $this->userid=Auth::id();
        if($this->userid==6){
            //\Debugbar::enable();
            //log: :info("debug");
        }
        $this->idsesion=$id;
        $this->ficha = Sesiones::where('user_id',$this->userid)->find($id);
        //$this->ficha->binario="";
        $this->ficha->activa=$this->ficha->activa==1?true:false;
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
        $this->packsdisponibles = Packs::where('user_id',$this->ficha->user_id)->where('activa',true)->orderBy('nombre','asc')->get()->toArray();
        if(strlen($this->ficha->emailconfirmaasunto)==0){
            $this->ficha->emailconfirmaasunto="Confirmación de reserva";
            $this->ficha->update();
        }
        if(strlen($this->ficha->emailconfirmacuerpo)<20){
            //$this->ficha->emailconfirmacuerpo='<p>Este es un email de confirmación de reserva de sesión en <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombreempresa</span> </p><p>Sesión reservada: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombresesion</span> - <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombrepack</span> </p><p>Fecha de la reserva: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">fechasesion</span> </p>';
            $this->ficha->emailconfirmacuerpo='<p>Este es un email de confirmación de reserva de sesión en <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombreempresa</span> </p><p>Sesión reservada: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombresesion</span> - <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombrepack</span> </p><p>Fecha de la reserva: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">fechasesion</span> </p><p>Localizador: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">localizador</span> </p>';
            $this->ficha->update();
        }
        if(strlen($this->ficha->emailconfirmacuerpo0)<20){
            //$this->ficha->emailconfirmacuerpo='<p>Este es un email de confirmación de reserva de sesión en <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombreempresa</span> </p><p>Sesión reservada: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombresesion</span> - <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombrepack</span> </p><p>Fecha de la reserva: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">fechasesion</span> </p>';
            $this->ficha->emailconfirmacuerpo0='<p>Este es un email de confirmación de reserva de sesión en <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombreempresa</span> </p><p>Por favor contacte con nosotros para confirmar el pago y formalizar la reserva.</p><p>Sesión reservada: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombresesion</span> - <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombrepack</span> </p><p>Fecha de la reserva: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">fechasesion</span> </p><p>Localizador: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">localizador</span> <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">domicilioempresa</span> <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombrecliente</span> </p>';
            $this->ficha->update();
        }
        if(strlen($this->ficha->emailrecuerdaasunto)==0){
            $this->ficha->emailrecuerdaasunto="Recordatorio de reserva";
            $this->ficha->update();
        }
        if(strlen($this->ficha->emailrecuerdacuerpo)<20){
            //$this->ficha->emailrecuerdacuerpo='<p>Este es un email de recordatorio de reserva de sesión en <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombreempresa</span> </p><p>Sesión reservada: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombresesion</span> - <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombrepack</span> </p><p>Fecha de la reserva: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">fechasesion</span> </p>';
            $this->ficha->emailrecuerdacuerpo='<p>Este es un email de recordatorio de reserva de sesión en <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombreempresa</span> </p><p>Sesión reservada: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombresesion</span> - <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">nombrepack</span> </p><p>Fecha de la reserva: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">fechasesion</span> </p><p>Localizador: <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: orange;">localizador</span> </p>';
            $this->ficha->update();
        }
        $this->cargalistapacks();
    }
    
    public function cargalistapacks(){
        //return;
        $this->seleccion=[];
        if(strlen($this->ficha->packs)>0){
            $this->seleccion=json_decode($this->ficha->packs,true);
        }
        $this->packsincluidos=count($this->seleccion);
        $wherein="-1";
        if(count($this->seleccion)>0){
            foreach($this->seleccion as $key=>$sel){
                $wherein.=",".$sel['id'];
            }
        }
        $this->packs = Packs::
            where('user_id',$this->ficha->user_id)
            ->whereRaw("id in (".$wherein.")")
            ->orderBy('id','asc')
            ->get()->toArray();
    }

    public function addpack($key){
        $idp=$this->packsdisponibles[$key]['id'];

        foreach($this->seleccion as $key=>$sel){
            if($sel['id']==$idp){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'Este pack ya está en esta sesión.',  'title' => 'ATENCIÓN']);
                return;
            }
        }

        $this->seleccion[]=['id'=>$idp];
        $this->ficha->packs=json_encode($this->seleccion);
        $this->ficha->update();
        $this->cargalistapacks();

        $this->dispatch('focusonpack', ['key' => $key]);

        //fprod{{$tag['id']}}

    }

    public function deletepack($id){
        foreach($this->seleccion as $key=>$sel){
            if($sel['id']==$id){
                unset($this->seleccion[$key]);
            }
        }
        $this->ficha->packs=json_encode($this->seleccion);
        $this->ficha->update();
        $this->cargalistapacks();
    }

    public function update(){
        $this->validate();
        if(strlen($this->ficha->nombre)==0){
            $this->ficha->nombre=$this->ficha->nombreinterno;
        }
        $this->ficha->update();
        $this->dispatch('mensajecorto',['type' => 'success',  'message' => 'datos actualizados',  'title' => 'ATENCIÓN']);

    }

    public function updateout(){
        $this->validate();
        if(strlen($this->ficha->nombre)==0){
            $this->ficha->nombre=$this->ficha->nombreinterno;
        }
        $this->ficha->update();
        return redirect(route('sesiones-management'))->with('status','galería actualizada.');
    }

    public function vseccion($num)
    {
        $this->seccion=$num;
        //$this->dispatch('$refresh');
    }

    public function deleteimage(){
        $this->ficha->binario="";
        $this->files=[];
        //$this->ficha->nohay descarga=$this->nohay descarga;
        $this->ficha->update();
    }

    public function variable($tip,$pos){
        $s='<span style="background-color: orange;color: white;padding: 4px 8px;text-align: center;border-radius: 5px;">NewByte</span> ';
        switch($tip){
            case 102:
                // nombre empresa
                $s=str_replace("NewByte","nombreempresa",$s);
                break;
            case 103:
                // nombre propio empresa
                $s=str_replace("NewByte","nombreempresa2",$s);
                break;
            case 104:
                // domicilio empresa
                $s=str_replace("NewByte","domicilioempresa",$s);
                break;
            case 105:
                // cp empresa
                $s=str_replace("NewByte","cpempresa",$s);
                break;
            case 106:
                // poblacion empresa
                $s=str_replace("NewByte","poblacionempresa",$s);
                break;
            case 107:
                // provincia empresa
                $s=str_replace("NewByte","provinciaempresa",$s);
                break;
            case 108:
                // telefono empresa
                $s=str_replace("NewByte","telefonoempresa",$s);
                break;
            case 109:
                // email empresa
                $s=str_replace("NewByte","emailempresa",$s);
                break;
            case 110:
                // nombre cliente
                $s=str_replace("NewByte","nombrecliente",$s);
                break;
            case 113:
                // nombre de la sesion
                $s=str_replace("NewByte","nombresesion",$s);
                break;
            case 115:
                // nombre del pack
                $s=str_replace("NewByte","nombrepack",$s);
                break;
            case 117:
                // fecha
                $s=str_replace("NewByte","fechasesion",$s);
                break;
            case 118:
                // localizador
                $s=str_replace("NewByte","localizador",$s);
                break;
        }
        if($pos==1){
            $this->dispatch('addtoquill_fichaemailconfirmacuerpo', ['ob' => $s]);
        }
        if($pos==2){
            $this->dispatch('addtoquill_fichaemailrecuerdacuerpo', ['ob' => $s]);
        }
        if($pos==3){
            $this->dispatch('addtoquill_fichaemailconfirmacuerpo0', ['ob' => $s]);
        }
    }

    public function render()
    {
        return view('livewire.sesiones.edit');
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
