<?php

namespace App\Http\Livewire\Gestion;

use App\Models\Basegestion;
use App\Models\Gestion;
use App\Models\Galeria;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Http\Utils;

class ArchivoPrimarioExport implements FromArray
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function array(): array
    {
        return $this->data;
    }
}

class Listados extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    public $tipo = 1;
    public $clipro = "Cliente";
    public $titulo = "";
    public $ruta = "";
    public $desde;
    public $hasta;
    public $userid = 0;
    public $sortField = 'fecha';
    public $sortDirection = 'asc';
    public $perPage = 25;
    public $pago1activo = true; // efectivo
    public $pago2activo = true; // trans
    public $pago3activo = true; // redsys
    public $pago4activo = true; // paypal
    public $pago5activo = true; // stripe
    public $pago6activo = true; // bizum

    protected $queryString = ['sortField', 'sortDirection'];
    protected $paginationTheme = 'bootstrap';

    public function mount(){
        $this->userid=Auth::id();
        $this->titulo="Listados";
        $this->ruta="listados";

        $fecha_actual = date("d-m-Y");
        $tmon=date("Y-m-d",strtotime($fecha_actual."- 3 month"));

        $this->desde=date('Y-m-d');
        $this->hasta=date('Y-m-d');
        $this->desde=$tmon;
    }
    public function sortBy($field){
        if($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function listar(){
        $this->render();
    }

    public function tiposdpago(){
        $x="(-9";
        if($this->pago1activo)
            $x.=",1";
        if($this->pago2activo)
            $x.=",2";
        if($this->pago3activo)
            $x.=",3";
        if($this->pago4activo)
            $x.=",4";
        if($this->pago5activo)
            $x.=",5";
        if($this->pago6activo)
            $x.=",6";
        $x.=")";
        return $x;
    }

    public function exportar($detalle){
        // 1 normal 2 detallado
        if($detalle==2 && $this->tipo==3)
            $detalle=1;
        if($detalle==1){
            switch($this->tipo){
                case 1:
                    $nom="ingresos";
                    $ficha=Basegestion::
                        leftJoin('clientes','clientes.id','=','basegestion.cliente_id')
                        ->select('documento','fecha','nombre','apellidos','nif','importe')
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->whereBetween('fecha',[$this->desde,$this->hasta])
                        ->orderBy('fecha', 'asc')
                        ->get()->toArray();
                        foreach($ficha as $key=>$lin){
                            if(is_null($lin['importe'])){
                                $ficha[$key]['importe']=0;
                            }
                        }
                    array_unshift($ficha,['documento','fecha','nombre','apellidos','nif','importetotal']);
                    break;
                case 3:
                    $nom="galerias";
                    $ficha=Galeria::
                        leftJoin('clientes','clientes.id','=','galerias.cliente_id')
                        ->select('nombreinterno as documento','fechapago as fecha','clientes.nombre','apellidos','nif','imppago as importe','tipodepago')
                        ->where('galerias.user_id',$this->userid)
                        ->whereRaw("(pagado+pagadomanual>0)")
                        ->whereRaw("tipodepago in ".$this->tiposdpago())
                        ->whereRaw("date(fechapago) between '".$this->desde."' and '".$this->hasta."'")
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->get()->toArray();
                        foreach($ficha as $key=>$lin){
                            if(is_null($lin['importe'])){
                                $ficha[$key]['importe']=0;
                            }
                            $ficha[$key]['importe']=$ficha[$key]['importe']."";
                            switch($ficha[$key]['tipodepago']){
                                case 1:
                                    $ficha[$key]['tipodepago']="efectivo";
                                    break;
                                case 2:
                                    $ficha[$key]['tipodepago']="transferencia";
                                    break;
                                case 3:
                                    $ficha[$key]['tipodepago']="tarjeta";
                                    break;
                                case 4:
                                    $ficha[$key]['tipodepago']="paypal";
                                    break;
                                case 5:
                                    $ficha[$key]['tipodepago']="stripe";
                                    break;
                                case 6:
                                    $ficha[$key]['tipodepago']="bizum";
                                    break;
                            }

                        }
                    array_unshift($ficha,['documento','fecha','nombre','apellidos','nif','importetotal','tipo pago']);
                    break;
                case 2:
                    $nom="gastos";
                    $ficha=Basegestion::
                        leftJoin('proveedores','proveedores.id','=','basegestion.cliente_id')
                        ->select('documento','fecha','nombre','apellidos','nif','importe')
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->whereBetween('fecha',[$this->desde,$this->hasta])
                        ->orderBy('fecha', 'asc')
                        ->get()->toArray();
                        foreach($ficha as $key=>$lin){
                            if(is_null($lin['importe'])){
                                $ficha[$key]['importe']=0;
                            }
                            $ficha[$key]['importe']+=0;
                        }
                    array_unshift($ficha,['documento','fecha','nombre','apellidos','nif','importetotal']);
                    break;
            }
            //Log::info($ficha);
        }
        if($detalle==2){
            switch($this->tipo){
                case 1:
                    $nom="ingresos";
                    $ficha=Gestion::
                        leftJoin('basegestion','basegestion.id','=','gestion.basegestion_id')
                        ->leftJoin('clientes','clientes.id','=','basegestion.cliente_id')
                        ->leftJoin('impuestos','impuestos.id','=','gestion.impuesto_id')
                        ->select('documento','fecha','clientes.nombre','apellidos','descripcion as descripcionlinea','basegestion.importe','gestion.importe as importelinea','porcentaje')
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->whereBetween('fecha',[$this->desde,$this->hasta])
                        ->orderBy('fecha', 'asc')
                        ->get()->toArray();
                    break;
                case 2:
                    $nom="gastos";
                    $ficha=Gestion::
                        leftJoin('basegestion','basegestion.id','=','gestion.basegestion_id')
                        ->leftJoin('proveedores','proveedores.id','=','basegestion.cliente_id')
                        ->leftJoin('impuestos','impuestos.id','=','gestion.impuesto_id')
                        ->select('documento','fecha','proveedores.nombre','apellidos','descripcion as descripcionlinea','basegestion.importe','gestion.importe as importelinea','porcentaje')
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->whereBetween('fecha',[$this->desde,$this->hasta])
                        ->orderBy('fecha', 'asc')
                        ->get()->toArray();
                    break;
            }
            foreach($ficha as $key=>$lin){
                if(is_null($lin['importe'])){
                    $ficha[$key]['importe']=0;
                }
                $ficha[$key]['importe']+=0;
                if(is_null($lin['importelinea'])){
                    $ficha[$key]['importelinea']=0;
                }
                $ficha[$key]['importelinea']+=0;
                $ficha[$key]['importelineaiva']= $ficha[$key]['importelinea']*(1+($ficha[$key]['porcentaje']/100)) ;
                $ficha[$key]['importelineaiva']=round($ficha[$key]['importelineaiva'],2);
            }
            array_unshift($ficha,['documento','fecha','nombre','apellidos','nif','importetotal','importelinea','porcentaje','totallinea']);
        }
        //log: :info($ficha);
        $export = new ArchivoPrimarioExport($ficha);
        return Excel::download($export, $nom.'.xls');


                
    }

    public function filtrofecha(){
        if(strlen($this->desde)==0){
            $this->desde=date("Y-m-d");
        }
        if(strlen($this->hasta)==0){
            $this->hasta=date("Y-m-d");
        }
    }

    public function seltipo($x){
        $this->tipo=$x;
        $this->clipro="Cliente";
        if($x==2)
            $this->clipro="Proveedor";
        if($x==3)
            $this->clipro="Cliente";
    }

    public function render()
    {
        $this->filtrofecha();
        switch($this->tipo){
            case 1:
                return view('livewire.gestion.listados', [
                    'fichas' => Basegestion::
                        leftJoin('clientes','clientes.id','=','basegestion.cliente_id')
                        ->select('basegestion.id','documento','fecha','basegestion.created_at','nombre','apellidos','importe',DB::raw('0 as tipodepago'))
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->whereBetween('fecha',[$this->desde,$this->hasta])
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->perPage)
                ]);
                break;
            case 3:
                return view('livewire.gestion.listados', [
                    'fichas' => Galeria::
                        leftJoin('clientes','clientes.id','=','galerias.cliente_id')
                        ->select('galerias.id','nombreinterno as documento','fechapago as fecha','galerias.created_at',
                        'clientes.nombre','apellidos','imppago as importe','tipodepago')
                        ->where('galerias.user_id',$this->userid)
                        ->whereRaw("(pagado+pagadomanual>0)")
                        ->whereRaw("tipodepago in ".$this->tiposdpago())
                        ->whereRaw("date(fechapago) between '".$this->desde."' and '".$this->hasta."'")
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->perPage)
                ]);
                break;
            case 2:
                return view('livewire.gestion.listados', [
                    'fichas' => Basegestion::
                        leftJoin('proveedores','proveedores.id','=','basegestion.cliente_id')
                        ->select('basegestion.id','documento','fecha','basegestion.created_at','nombre','apellidos','importe',DB::raw('0 as tipodepago'))
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->where('basegestion.user_id',$this->userid)
                        ->where('tipo',$this->tipo)
                        ->whereBetween('fecha',[$this->desde,$this->hasta])
                        ->paginate($this->perPage)
                ]);
                break;
        }


    }
}
