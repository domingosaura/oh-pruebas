<?php

namespace App\Http\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Proveedor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Utils;
use Log;

class Create extends Component
{
    use AuthorizesRequests;
    public $userid=0;
    public $nombre='';
    public $singular='';
    public $plural='';
    public $apellidos='';
    public $nif='';
    public $domicilio='';
    public $cpostal='';
    public $poblacion='';
    public $provincia='';
    public $telefono='';
    public $email='';
    public $permiteimagenes=false;
    public $permitecomunicaciones=false;
    public $nombrepareja;
    public $apellidospareja;
    public $nifpareja;
    public $notasinternas;
    public $hijo1="";
    public $edad1=null;
    public $hijo2="";
    public $edad2=null;
    public $hijo3="";
    public $edad3=null;
    public $hijo4="";
    public $edad4=null;
    public $hijo5="";
    public $edad5=null;
    public $hijo6="";
    public $edad6=null;
    public $activo=true;
    public $errormail="";

    protected function rules(){

        return [
            'nombre' => 'required|max:100',
            'apellidos' => '',
            //'apellidos' => 'required|max:100',
            'activo' => '',
            'permiteimagenes' => '',
            'permitecomunicaciones' => '',
            'email' => 'required|email|max:200|unique:clientes,email,null,null,user_id,'.$this->userid,
            'nif' => '',
            'nombrepareja' => '',
            'apellidospareja' => '',
            'notasinternas' => '',
            'nifpareja' => '',
            'hijo1' => '',
            'edad1' => '',
            'hijo2' => '',
            'edad2' => '',
            'hijo3' => '',
            'edad3' => '',
            'hijo4' => '',
            'edad4' => '',
            'hijo5' => '',
            'edad5' => '',
            'hijo6' => '',
            'edad6' => '',
        ];
    }


    public function mount(){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $this->userid=Auth::id();
        $this->singular="cliente";
        $this->plural="Clientes";
    }

    public function store(){

        if($this->email==""){
            $this->email=Utils::randomString(7)."@mailarrellenarporelcliente.oh";
        }

        $this->validate();
        
        $user = Auth::user();
        $this->userid=Auth::id();
        $this->edad1=strlen($this->edad1)==10?$this->edad1:null;
        $this->edad2=strlen($this->edad2)==10?$this->edad2:null;
        $this->edad3=strlen($this->edad3)==10?$this->edad3:null;
        $this->edad4=strlen($this->edad4)==10?$this->edad4:null;
        $this->edad5=strlen($this->edad5)==10?$this->edad5:null;
        $this->edad6=strlen($this->edad6)==10?$this->edad6:null;
        $xxx=Cliente::create([
            'nombre' => $this->nombre,
            'apellidos' =>$this->apellidos,
            'nif' =>$this->nif,
            'domicilio' =>$this->domicilio,
            'cpostal' =>$this->cpostal,
            'poblacion' =>$this->poblacion,
            'provincia' =>$this->provincia,
            'telefono' =>$this->telefono,
            'email' =>$this->email,
            'activo' =>$this->activo,
            'user_id' =>$this->userid,
            'nombrepareja' =>$this->nombrepareja,
            'apellidospareja' =>$this->apellidospareja,
            'nifpareja' =>$this->nifpareja,
            'notasinternas' =>$this->notasinternas,
            'hijo1' =>$this->hijo1,
            'edad1' =>$this->edad1,
            'hijo2' =>$this->hijo2,
            'edad2' =>$this->edad2,
            'hijo3' =>$this->hijo3,
            'edad3' =>$this->edad3,
            'hijo4' =>$this->hijo4,
            'edad4' =>$this->edad4,
            'hijo5' =>$this->hijo5,
            'edad5' =>$this->edad5,
            'hijo6' =>$this->hijo6,
            'edad6' =>$this->edad6,
            'activo' =>true,
            'permiteimagenes' =>$this->permiteimagenes,
            'permitecomunicaciones' =>$this->permitecomunicaciones,
            'created_at' => Carbon::now(),
        ]);

        return redirect(route('edit-cliente',$xxx->id));
        return redirect(route('cliente-management'))->with('status',$this->singular.' creado correctamente.');
    }

    public function saveandgoto(){
        //log: :info("goto");
    }

    public function render()
    {
        //$this->authorize('manage-clientes', User::class);
        return view('livewire.cliente.create');
    }
}
