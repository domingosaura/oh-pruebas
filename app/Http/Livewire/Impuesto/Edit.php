<?php

namespace App\Http\Livewire\Impuesto;

use App\Models\Impuesto;
use App\Models\Proveedor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Log;

class Edit extends Component
{

    public Impuesto $ficha;
    public $userid;
    use AuthorizesRequests;
    
    protected function rules(){

        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]

        return [
            'ficha.nombre' => 'required|max:50',
            'ficha.porcentaje' => 'decimal:0,2',
        ];
    }

    public function mount($id){

        $this->userid=Auth::id();
        $this->ficha =Impuesto::where('user_id',$this->userid)->find($id);
        //$this->ficha->porc=$this->ficha->porcentaje;
        //log: :info($this->ficha);
    }

    public function update(){
        
        $this->validate();

        $this->ficha->update();

        return redirect(route('impuesto-management'))->with('status','impuesto actualizado.');
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.impuesto.edit');
    }
}
