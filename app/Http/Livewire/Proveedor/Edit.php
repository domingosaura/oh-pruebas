<?php

namespace App\Http\Livewire\Proveedor;

use App\Models\Proveedor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Log;

class Edit extends Component
{

    public Proveedor $ficha;
    public $singular='';
    public $plural='';
    public $userid;
    public $errormail="";
    use AuthorizesRequests;
    
    protected function rules(){

        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]

        return [
            'ficha.nombre' => 'required|max:100',
            'ficha.apellidos' => 'required|max:100',
            //'ficha.email' => 'required|email|max:200|unique:proveedores,email,'.$this->ficha->id.',id,user_id,'.$this->userid,
            'ficha.email' => 'email|max:200',
            'ficha.nif' => 'required|max:15|unique:proveedores,nif,'.$this->ficha->id.',id,user_id,'.$this->userid,
            'ficha.notasinternas' => '',
            'ficha.activo' => '',
        ];
    }

    public function mount($id){

        $this->userid=Auth::id();
        $this->ficha = Proveedor::where('user_id',$this->userid)->find($id);
        $this->ficha->activo=$this->ficha->activo==1?true:false;
        $this->singular="proveedor";
        $this->plural="Proveedores";
        //log: :info($this->ficha);
    }

    public function update(){
        
        $this->validate();

        $this->ficha->update();

        return redirect(route('proveedor-management'))->with('status',$this->singular.' actualizado.');
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.cliente.edit');
    }
}
