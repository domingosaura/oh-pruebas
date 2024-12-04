<?php

namespace App\Http\Livewire\Plantillacontrato;

use App\Models\Pcontrato;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use File;
use App\Http\Utils;
use DB;
use Log;

class Edit extends Component
{

    public Pcontrato $ficha;
    public $tipo = 0;
    public $idmov = 0;
    public $userid;
    public $plantillas;
    use AuthorizesRequests;
    
    protected function rules(){

        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]

        return [
            'ficha.nombre' => 'required|max:250',
            'ficha.texto' => 'required',
        ];
    }

    public function mount($id,$tipo){
        $this->userid=Auth::id();
        $this->idmov=$id;
        $this->ficha = Pcontrato::where('user_id',$this->userid)->find($id);
        //log: :info($this->ficha);
        $this->plantillas = Pcontrato::
            select('id','nombre','texto')
            ->where('user_id',$this->userid)
            ->where('id',"<>",$id)
            ->orderBy('nombre','asc')
            ->get()->toArray();

    }
    
    public function ejemplo($tip){
        // 1 recien nacido 2 comunion 3 boda
        //log: :info(public_path()."/oh/plantilla".$tip.".html");
        $code=File::get(public_path()."/oh/plantilla".$tip.".html");
        $this->ficha->texto=$code;
        $this->dispatch('refreshquill', ['ob' => $this->ficha->texto]);
    }

    public function selectplantilla($keyy)
    {
        $nombre=$this->plantillas[$keyy]['nombre'];
        $texto=$this->plantillas[$keyy]['texto'];
        $this->ficha->nombre=$nombre;
        $this->ficha->texto=$texto;
        $this->dispatch('refreshquill', ['ob' => $texto]);
    }

    public function variable($tip){

        $s=Utils::variablecontrato($tip);
        $this->dispatch('addtoquill', ['ob' => $s]);
    }

    public function update(){
        $this->validate();
        $this->ficha->update();
        //$this->dispatch('mensaje',['type' => 'error',  'message' => 'Faltan datos',  'title' => 'ATENCIÓN']);
        //if($end==1){
        //    return redirect(route($this->ruta.'-management'));
        //}
        return redirect(route('plantillacontrato-management'))->with('status','plantilla de contrato actualizada.');
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.plantillacontrato.edit');
    }
}
