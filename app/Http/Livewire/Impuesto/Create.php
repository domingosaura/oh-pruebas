<?php

namespace App\Http\Livewire\Impuesto;

use App\Models\Impuesto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Create extends Component
{
    use AuthorizesRequests;
    public $userid=0;
    public $nombre='';
    public $porcentaje=0;
    public $activo=true;

    protected function rules(){

        return [
            'nombre' => 'required|max:50',
            'porcentaje' => 'decimal:0,2',
            ];
    }


    public function mount(){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $this->userid=Auth::id();
    }

    public function store(){

        $this->validate();
        
        $user = Auth::user();
        $this->userid=Auth::id();

        Impuesto::create([
            'nombre' => $this->nombre,
            'porcentaje' =>$this->porcentaje,
            'user_id' =>$this->userid,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        return redirect(route('impuesto-management'))->with('status','impuesto creado correctamente.');
    }


    public function render()
    {
        //$this->authorize('manage-clientes', User::class);
        return view('livewire.impuesto.create');
    }
}
