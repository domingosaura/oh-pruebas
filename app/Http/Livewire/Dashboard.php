<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Basecalendario;
use App\Models\Contrato;
use App\Models\Galeria;
//use App\Models\Binario s;
//use App\Models\Binario s2;
//use App\Models\Binario s3;
//use App\Models\Binario s4;
//use App\Models\Binario s5;
use App\Models\Avisos;
use Log;
use DB;
use Session;
use App\Http\Utils;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $totalclientes=0;
    public $totalcalendarios=0;
    public $userid = 0;
    public $firmados=0;
    public $nofirmados=0;
    public $totalgalerias=0;
    public $totalgigas=0;
    public $totalgigastotal=0;
    public $next;
    public $avisos;
    public function mount(){
        //Utils::vacialog();
        $this->userid=Auth::id();
        
        // suscripcion
        if(!Session::has('suscrito')){
            $suscrito=Utils::suscripcionactiva(); // 0 no tiene licencia 1 periodo de prueba 2 tiene una suscripcion
            Session::put('suscrito',$suscrito);
            //Log::info(Session('suscrito'));
        }
        if(Session('suscrito')==0){
            $suscrito=Utils::suscripcionactiva(); // 0 no tiene licencia 1 periodo de prueba 2 tiene una suscripcion
            Session::put('suscrito',$suscrito);
        }
        if(Session('suscrito')==0){
            $this->dispatch('mensajelargo',['type' => 'error',  'message' => 'Contrate un plan de suscripción para continuar usando la aplicación.',  'title' => 'ATENCIÓN']);
        }
        // end suscripcion
        
        $bi=Utils::browserInfo($this->userid); // no quitar establece variables de sesion en el arranque!!!
        //Log::info($bi);
        //Log::info(Session('soporteavif',true));
        
        
        $this->totalclientes=Cliente::where('user_id',$this->userid)->count();
        $this->totalcalendarios=Basecalendario::where('user_id',$this->userid)->where('activo',1)->count();
        $this->firmados=Contrato::where('user_id',$this->userid)->where('firmado',1)->count();
        $this->nofirmados=Contrato::where('user_id',$this->userid)->where('firmado',0)->count();
        $this->totalgalerias=Galeria::where('user_id',$this->userid)->where('archivada',0)->where('fechaeliminada',null)->count();
        $this->totalgigas=Utils::espaciousado($this->userid);
        $this->totalgigastotal=Utils::espaciousadototal($this->userid);
        // next
        $sel=DB::table("calendario")
        ->leftJoin('clientes','clientes.id','=','calendario.cliente_id')
        ->leftJoin('basecalendario','basecalendario.id','=','calendario.basecalendario_id')
        ->select('title','start','calendario.id','cliente_id','clientes.nombre as nombrecliente','basecalendario_id')
        ->where('basecalendario.user_id',$this->userid)
        ->where('calendario.cliente_id',">",0)
        ->whereRaw('date(start) between curdate() and date_add(curdate(),interval 7 day)')
        ->orderBy('start','asc');
        $x=$sel->get()->toArray();
        //$this->caolores($x);
        foreach ($x as $key => $obe) {
            if($obe->cliente_id>0){
                $obe->title.="->".$obe->nombrecliente;
            }
            // para livewire no pueden ser objetos
            $x[$key]=(array)$x[$key];
            // no lo vamos a usar, cuando intenta eliminar avisa si no puede $x[$key]['movimiento']=false; // falta completar cuando se pueda
        }
        $this->next=(array)$x;
        // next
        $this->loadavisos();
    }
    public function loadavisos(){
        $sel=DB::table("avisos")
        ->leftJoin('galerias','galerias.id','=','avisos.galeria_id')
        ->leftJoin('clientes','clientes.id','=','galerias.cliente_id')
        ->select('avisos.id','avisos.galeria_id','avisos.numerico','avisos.notas','avisos.created_at','clientes.nombre','clientes.apellidos')
        ->where('avisos.user_id',$this->userid)
        ->where('galeria_id','>',0)
        ->where('pendiente',1)
        ->orderBy('id','desc');
        $x=$sel->get()->toArray();
        foreach ($x as $key => $obe) {
            // para livewire no pueden ser objetos
            $x[$key]=(array)$x[$key];
            // no lo vamos a usar, cuando intenta eliminar avisa si no puede $x[$key]['movimiento']=false; // falta completar cuando se pueda
        }
        $this->avisos=(array)$x;
    }

    public function removeaviso($id){
        Avisos::where('user_id',$this->userid)->where('id',$id)->update(['pendiente'=>false]);
        $this->loadavisos();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
