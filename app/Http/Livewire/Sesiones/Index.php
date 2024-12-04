<?php

namespace App\Http\Livewire\Sesiones;

use App\Models\Sesiones;
use App\Models\User;
use App\Models\User2;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Utils;
use Carbon\Carbon;
use Response;

class Index extends Component
{
    use WithPagination;
    use AuthorizesRequests;
    public $marcador = 1;
    public $userid = 0;
    public $soloactivos = 1;
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

    public function destroy($idd){
        Sesiones::find($idd)->delete();
        $this->render();
        //return redirect(route('sesiones-management'))->with('status','galería eliminada correctamente');
    }

    public function nuevoregistro(){
        $nid=Sesiones::insertGetId([
            'user_id'=>$this->userid,
            'nombre'=>'',
            'nombreinterno'=>'',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        return redirect(route('edit-sesiones',$nid));
    }
    
    public function vseccion($xx){
        $this->soloactivos=$xx;
        $this->recarga();
    }

    public function archivar($id){
        Sesiones::where('id',$id)->update([
            'activa'=>false
        ]);
        $this->recarga();
    }

    public function desarchivar($id){
        Sesiones::where('id',$id)->update([
            'activa'=>true
        ]);
        $this->recarga();
    }

    public function recarga(){

    }

    public function clone($id){
        $pg=Sesiones::where('id',$id)->get()->toArray()[0];
        $pg['created_at']=Carbon::now();
        $pg['updated_at']=Carbon::now();
        $pg['nombre'].=" (1)";
        unset($pg['id']);
        $nid=Sesiones::insertGetId($pg);
        return redirect(route('edit-sesiones',$nid));
    }

    public function render()
    {
        $acti=$this->soloactivos; // 1 activos 2 archivados 3 trashed
        // sin paginate
        $this->perPage=1000000;

        switch($acti){
            case 1: // activos
                $valor=1;
                break;
            case 2: // archivados
                $valor=0;
                break;
        }

        $this->sortField="created_at";
        $this->sortDirection="desc";

        if(strlen($this->search)==0){
            return view('livewire.sesiones.index', [
                'fichas' => Sesiones::
                    select('id','nombre','nombreinterno','activa','created_at','binario')
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->where('user_id',$this->userid)
                        ->where('activa',$valor)
                        ->paginate($this->perPage)
            ]);
        }
        $sear=$this->search;
        return view('livewire.sesiones.index', [
            'fichas' => Sesiones::
            select('id','nombre','nombreinterno','activa','created_at','binario')
            ->where('user_id',$this->userid)
            ->where('activa',$valor)
            ->where(function ($query) use ($sear) {
                    $query->where('nombre','like','%'.$sear.'%')
                        ->orWhere('nombreinterno','like','%'.$sear.'%'); // ->orWhere('titulo','like','%'.$sear.'%'); // ->orWhere('nif','like','%'.$sear.'%');
                    })
                    ->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
        ]);
    }
}
