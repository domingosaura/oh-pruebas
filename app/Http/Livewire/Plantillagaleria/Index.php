<?php

namespace App\Http\Livewire\Plantillagaleria;

use App\Models\Pgaleria;
use App\Models\Productos;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Utils;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Index extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $marcador = 1;
    public $userid = 0;
    public $search = '';
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $perPage = 25;

    protected $queryString = ['sortField', 'sortDirection'];
    protected $paginationTheme = 'bootstrap';

    public function mount(){
        //log: :info($this->sortField);
        //$user = Auth::user();
        $this->userid=Auth::id();
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
        Pgaleria::find($id)->delete();
        Productos::where('galeria_id',$id)->delete();
        return redirect(route('plantillagaleria-management'))->with('status','plantilla eliminada correctamente');
    }

    public function nuevoregistro(){
        $nid=Pgaleria::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>'',
            'numfotos'=>0,
            'maxfotos'=>0,
            'preciogaleria'=>0,
            'preciogaleriacompleta'=>0,
            'preciofoto'=>0,
            'diascaducidad'=>7,
            'diascaducidaddescarga'=>30,
            'permitirdescarga'=>2,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        return redirect(route('edit-plantillagaleria',$nid));
    }
    
    public function clone($id){
        $pg=Pgaleria::where('id',$id)->get()->toArray()[0];
        $pg['created_at']=Carbon::now();
        $pg['updated_at']=Carbon::now();
        unset($pg['id']);
        $nid=Pgaleria::insertGetId($pg);
        $productos=Productos::where('galeria_id',$id)->get()->toArray();
        $productos=Utils::objectToArray($productos);
        foreach($productos as $key=>$prod){
            $productos[$key]['galeria_id']=$nid;
            unset($productos[$key]['id']);
            unset($productos[$key]['created_at']);
            unset($productos[$key]['updated_at']);
        }
        foreach($productos as $key=>$prod){
            Productos::insert($prod);
        }
        
        return redirect(route('edit-plantillagaleria',$nid));
    }
    
    public function render()
    {
        //$this->authorize('manage-items', User::class);
        if(strlen($this->search)==0){
            return view('livewire.plantillagaleria.index', [
                'fichas' => Pgaleria::
                    select('id','nombre','nombreinterno','created_at')->
                    orderBy($this->sortField, $this->sortDirection)
                ->where('user_id',$this->userid)
                    ->paginate($this->perPage)
            ]);
        }
        $sear=$this->search;
        return view('livewire.plantillagaleria.index', [
            'fichas' => Pgaleria::
                select('id','nombre','nombreinterno','created_at')->
                where('user_id',$this->userid)->
                where(function ($query) use ($sear) {
                    $query->where('nombre','like','%'.$sear.'%'); // ->orWhere('titulo','like','%'.$sear.'%'); // ->orWhere('nif','like','%'.$sear.'%');
                })->
                orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }
}
