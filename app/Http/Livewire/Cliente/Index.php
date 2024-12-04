<?php

namespace App\Http\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Galeria;
use App\Models\Proveedor;
use App\Models\Basegestion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Index extends Component
{

    use WithPagination;
    use AuthorizesRequests;
    //use SoftDeletes;

    public $tipo = "";
    public $singular = "";
    public $plural = "";
    public $userid = 0;
    public $search = '';
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $perPage = 25;
    public $quever=1; // todos activos inactivos
    public $errormail="";

    protected $queryString = ['sortField', 'sortDirection'];
    protected $paginationTheme = 'bootstrap';

    public function mount(){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $this->userid=Auth::id();
        $this->singular="cliente";
        $this->plural="Clientes";
    }
    public function sortBy($field){
        if($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }


    public function destroy($id){
        $x=Contrato::where('cliente_id',$id)->count();
        if($x>0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'El cliente tiene contratos asociados, no se puede eliminar',  'title' => 'ATENCIÓN']);
            return;
        }
        $x=Galeria::where('cliente_id',$id)->count();
        if($x>0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'El cliente tiene galerías asociadas, no se puede eliminar',  'title' => 'ATENCIÓN']);
            return;
        }
        $x=Basegestion::where('cliente_id',$id)->where('tipo',1)->count();
        if($x>0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'El cliente tiene movimientos de ingresos/gastos, no se puede eliminar',  'title' => 'ATENCIÓN']);
            return;
        }
        Cliente::find($id)->delete();
        return redirect(route('cliente-management'))->with('status',$this->singular.' eliminado correctamente');
    }

    public function vseccion($x){
        $this->quever=$x;
    }

    public function nuevoregistro(){
        $nid=Cliente::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>'',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        return redirect(route('edit-'.$this->singular,$nid));
    }

    public function render()
    {


        switch($this->quever){
            case 1:
                $wherein="1,0";
                break;
            case 2:
                $wherein="1";
                break;
            case 3:
                $wherein="0";
                break;
        }


        //$this->authorize('manage-items', User::class);
        if(strlen($this->search)==0){
            return view('livewire.cliente.index', [
                'clientes' => Cliente::
                    orderBy($this->sortField, $this->sortDirection)
                    ->where('user_id',$this->userid)
                    ->whereRaw("activo in (".$wherein.")")
                    ->paginate($this->perPage)
            ]);
        }
        $sear=$this->search;
        return view('livewire.cliente.index', [
            'clientes' => Cliente::
                where('user_id',$this->userid)
                ->whereRaw("activo in (".$wherein.")")
                ->where(function ($query) use ($sear) {
                    $query->where('nombre','like','%'.$sear.'%')
                        ->orWhere('apellidos','like','%'.$sear.'%')
                        ->orWhere('telefono','like','%'.$sear.'%')
                        ->orWhere('email','like','%'.$sear.'%')
                        ->orWhere('nif','like','%'.$sear.'%');
                })->
                orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
