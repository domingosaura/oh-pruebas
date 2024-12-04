<?php

namespace App\Http\Livewire\Proveedor;

use App\Models\Proveedor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
    public $notasinternas;
    public $activo=true;
    public $errormail="";

    protected function rules(){

        return [
            'nombre' => 'required|max:100',
            'apellidos' => 'required|max:100',
            //'email' => 'required|email|max:200|unique:proveedores,email,null,null,user_id,'.$this->userid,
            'email' => 'email|max:200',
            'nif' => 'required|max:15|unique:proveedores,nif,null,null,user_id,'.$this->userid,
            'notasinternas' => '',
            'activo' => '',
            ];
    }


    public function mount(){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $this->userid=Auth::id();
        $this->singular="proveedor";
        $this->plural="Proveedores";
    }

    public function store(){

        $this->validate();
        
        $user = Auth::user();
        $this->userid=Auth::id();

        Proveedor::create([
            'nombre' => $this->nombre,
            'apellidos' =>$this->apellidos,
            'nif' =>$this->nif,
            'domicilio' =>$this->domicilio,
            'cpostal' =>$this->cpostal,
            'poblacion' =>$this->poblacion,
            'provincia' =>$this->provincia,
            'telefono' =>$this->telefono,
            'email' =>$this->email,
            'notasinternas' =>$this->notasinternas,
            'activo' =>$this->activo,
            'user_id' =>$this->userid,
            'created_at' => Carbon::now(),
        ]);

        return redirect(route('proveedor-management'))->with('status',$this->singular.' creado correctamente.');
    }


    public function render()
    {
        //$this->authorize('manage-clientes', User::class);
        return view('livewire.cliente.create');
    }
}
