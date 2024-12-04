<?php

namespace App\Http\Livewire\Plantillacontrato;

use App\Models\Pcontrato;
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
    public $userid = 0;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
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
        Pcontrato::find($id)->delete();
        return redirect(route('plantillacontrato-management'))->with('status','plantilla eliminada correctamente');
    }

    public function nuevoregistro(){
        $nid=Pcontrato::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>'',
            'texto'=>'',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        return redirect(route('edit-plantillacontrato',$nid));
    }
    
    public function clone($id){
        $pg=Pcontrato::where('id',$id)->get()->toArray()[0];
        $pg['created_at']=Carbon::now();
        $pg['updated_at']=Carbon::now();
        unset($pg['id']);
        $nid=Pcontrato::insertGetId($pg);
        return redirect(route('edit-plantillacontrato',$nid));
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        if(strlen($this->search)==0){
            return view('livewire.plantillacontrato.index', [
                'fichas' => Pcontrato::
                    orderBy($this->sortField, $this->sortDirection)
                    ->where('user_id',$this->userid)
                    ->paginate($this->perPage)
            ]);
        }
        $sear=$this->search;
        return view('livewire.plantillacontrato.index', [
            'fichas' => Pcontrato::
                where('user_id',$this->userid)->
                where(function ($query) use ($sear) {
                    $query->where('nombre','like','%'.$sear.'%'); // ->orWhere('apellidos','like','%'.$sear.'%')->orWhere('nif','like','%'.$sear.'%');
                })->
                orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }
}
