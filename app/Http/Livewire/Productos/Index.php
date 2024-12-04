<?php

namespace App\Http\Livewire\Productos;

use App\Models\Pproductos;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        Pproductos::find($id)->delete();
        return redirect(route('productos-management'))->with('status','producto eliminado correctamente');
    }

    public function nuevoregistro(){
        $nid=Pproductos::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>'',
            'anotaciones'=>'',
            'binario'=>'',
            'numfotos'=>0,
            'fotosdesde'=>0,
            'precioproducto'=>0,
            //'created_at'=>DB::raw("now()"),
            //'updated_at'=>DB::raw("now()"),
        ]);
        return redirect(route('edit-productos',$nid));
    }
    
    public function clone($id){
        $pg=Pproductos::where('id',$id)->get()->toArray()[0];
        //$pg['created_at']=Carbon::now();
        //$pg['updated_at']=Carbon::now();
        unset($pg['id']);
        unset($pg['created_at']);
        unset($pg['updated_at']);
        $nid=Pproductos::insertGetId($pg);
        return redirect(route('edit-productos',$nid));
    }
    
    public function render()
    {
        //$this->authorize('manage-items', User::class);
        if(strlen($this->search)==0){
            return view('livewire.productos.index', [
                'fichas' => Pproductos::
                    select('id','nombre','created_at')->
                    orderBy($this->sortField, $this->sortDirection)
                ->where('user_id',$this->userid)
                    ->paginate($this->perPage)
            ]);
        }
        $sear=$this->search;
        return view('livewire.productos.index', [
            'fichas' => Pproductos::
                select('id','nombre','created_at')->
                where('user_id',$this->userid)->
                where(function ($query) use ($sear) {
                    $query->where('nombre','like','%'.$sear.'%'); // ->orWhere('titulo','like','%'.$sear.'%'); // ->orWhere('nif','like','%'.$sear.'%');
                })->
                orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }
}
