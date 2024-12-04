<?php

namespace App\Http\Livewire\Gestion;

use App\Models\Basegestion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Index extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    public $tipo = 0;
    public $titulo = "";
    public $ruta = "";
    public $userid = 0;
    public $search = '';
    public $sortField = 'fecha';
    public $sortDirection = 'asc';
    public $perPage = 25;

    protected $queryString = ['sortField', 'sortDirection'];
    protected $paginationTheme = 'bootstrap';

    public function mount($tipo){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $this->userid=Auth::id();
        $this->tipo=$tipo;
        $this->titulo="";
        $this->titulo=$tipo==1?"Ingresos":$this->titulo;
        $this->titulo=$tipo==2?"Gastos":$this->titulo;
        $this->ruta="";
        $this->ruta=$tipo==1?"ingreso":$this->ruta;
        $this->ruta=$tipo==2?"gasto":$this->ruta;
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
        Basegestion::find($id)->delete();
        return redirect(route($this->ruta.'-management'))->with('status',$this->ruta.' eliminado correctamente');
    }

    public function nuevoregistro(){
        $nid=Basegestion::insertGetId([
            'user_id'=>$this->userid,
            'tipo'=>$this->tipo,
            'documento'=>'número de documento',
            'importe'=>0,
            'fecha'=>DB::raw('curdate()'),
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
        ]);
        return redirect(route('edit-'.$this->ruta,$nid));
    }
    
    public function render()
    {

        switch($this->tipo){
            case 1:
                if(strlen($this->search)==0){
                    return view('livewire.gestion.index', [
                        'fichas' => Basegestion::
                            leftJoin('clientes','clientes.id','=','basegestion.cliente_id')
                            ->select('basegestion.id','documento','fecha','basegestion.created_at','nombre','apellidos','importe')
                            ->orderBy($this->sortField, $this->sortDirection)
                            ->where('basegestion.user_id',$this->userid)
                            ->where('tipo',$this->tipo)
                            ->paginate($this->perPage)
                    ]);
                }
                $sear=$this->search;
                return view('livewire.gestion.index', [
                    'fichas' => Basegestion::
                        leftJoin('clientes','clientes.id','=','basegestion.cliente_id')
                        ->select('basegestion.id','documento','fecha','basegestion.created_at','nombre','apellidos','importe')
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->where(function ($query) use ($sear) {
                            $query->where('documento','like','%'.$sear.'%')->orWhere('nombre','like','%'.$sear.'%')->orWhere('apellidos','like','%'.$sear.'%')->orWhere('nif','like','%'.$sear.'%');
                        })
                        ->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
                ]);
                break;
            case 2:
                if(strlen($this->search)==0){
                    return view('livewire.gestion.index', [
                        'fichas' => Basegestion::
                            leftJoin('proveedores','proveedores.id','=','basegestion.cliente_id')
                            ->select('basegestion.id','documento','fecha','basegestion.created_at','nombre','apellidos','importe')
                            ->orderBy($this->sortField, $this->sortDirection)
                            ->where('basegestion.user_id',$this->userid)
                            ->where('tipo',$this->tipo)
                            ->paginate($this->perPage)
                    ]);
                }
                $sear=$this->search;
                return view('livewire.gestion.index', [
                    'fichas' => Basegestion::
                        leftJoin('proveedores','proveedores.id','=','basegestion.cliente_id')
                        ->select('basegestion.id','documento','fecha','basegestion.created_at','nombre','apellidos','importe')
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->where(function ($query) use ($sear) {
                            $query->where('documento','like','%'.$sear.'%')->orWhere('nombre','like','%'.$sear.'%')->orWhere('apellidos','like','%'.$sear.'%')->orWhere('nif','like','%'.$sear.'%');
                        })
                        ->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
                ]);
                break;
        }


    }
}
