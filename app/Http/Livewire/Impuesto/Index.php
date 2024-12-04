<?php

namespace App\Http\Livewire\Impuesto;

use App\Models\Impuesto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Index extends Component
{

    use WithPagination;
    use AuthorizesRequests;
    //use SoftDeletes;

    public $userid = 0;
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $perPage = 25;
    public $search = '';

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
        Impuesto::find($id)->delete();
        return redirect(route('impuesto-management'))->with('status','impuesto eliminado correctamente');
    }

    
    public function render()
    {
        //$this->authorize('manage-items', User::class);
        if(strlen($this->search)==0){
            return view('livewire.impuesto.index', [
                'clientes' => Impuesto::orderBy($this->sortField, $this->sortDirection)->where('user_id',$this->userid)->paginate($this->perPage)
            ]);
        }
        $sear=$this->search;
        return view('livewire.impuesto.index', [
            'clientes' => Cliente::
                where('user_id',$this->userid)->
                where(function ($query) use ($sear) {
                    $query->where('nombre','like','%'.$sear.'%');
                })->
                orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }
}
