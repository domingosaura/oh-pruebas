<?php

namespace App\Http\Livewire\Gestion;

use App\Models\Basegestion;
use App\Models\Gestion;
use App\Models\Impuesto;
use App\Models\Cliente;
use App\Models\Proveedor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Utils;
use DB;
use Log;
use Carbon\Carbon;

class Edit extends Component
{

    public Basegestion $ficha;
    public $tipo = 0;
    public $idmov = 0;
    public $total = 0;
    public $impuestos = [];
    public $desglose = null;
    public $titulo = "";
    public $ruta = "";
    public $clipro = "";
    public $userid;
    public $clientes;
    use AuthorizesRequests;
    
    protected function rules(){

        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]

        return [
            'ficha.documento' => 'required|max:50',
            'ficha.tipo' => 'required',
            'ficha.fecha' => 'required',
            'ficha.importe' => '',
        ];
    }

    public function mount($id,$tipo){
        $this->userid=Auth::id();
        $this->idmov=$id;
        $this->tipo=$tipo;
        $this->titulo="";
        $this->titulo=$tipo==1?"Ingresos":$this->titulo;
        $this->titulo=$tipo==2?"Gastos":$this->titulo;
        $this->clipro=$tipo==1?"Cliente":$this->clipro;
        $this->clipro=$tipo==2?"Proveedor":$this->clipro;
        $this->ruta="";
        $this->ruta=$tipo==1?"ingreso":$this->ruta;
        $this->ruta=$tipo==2?"gasto":$this->ruta;
        $this->impuestos = Impuesto::where('user_id',$this->userid)->get();
        //log: :info($this->impuestos);
        $this->ficha = Basegestion::where('user_id',$this->userid)->find($id);
        //log: :info($this->ficha);

        if($tipo==1){
            $clientes=DB::table("clientes")
            ->select(DB::raw("concat(nombre,' ',apellidos,' ',telefono,' ',email,' ',nif) as label"),'id as value')
            ->where('user_id',$this->userid)
            ->orderBy('nombre','asc')
            ->get();
        }
        if($tipo==2){
            $clientes=DB::table("proveedores")
            ->select(DB::raw("concat(nombre,' ',apellidos,' ',telefono,' ',email,' ',nif) as label"),'id as value')
            ->where('user_id',$this->userid)
            ->orderBy('nombre','asc')
            ->get();
        }
        $clientes=Utils::objectToArray($clientes);
        $this->clientes=json_encode($clientes);
        $this->desglosar();
    }

    public function setidcliente($idid)
    {
        $this->ficha->cliente_id=$idid;
        Basegestion::where('user_id',$this->userid)->where('id',$this->idmov)->update(['cliente_id'=>$idid]);
    }

    public function linea(){
        Gestion::insert([
            'basegestion_id'=>$this->idmov,
            'impuesto_id'=>0,
            'descripcion'=>'',
            'importe'=>0,
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
        ]);
        $this->desglosar();
    }

    public function deleteline($id){
        $x=DB::table('gestion')->where('id',$id)->delete();
        $this->desglosar();
    }

    public function updatelinea($key){
        $data=$this->desglose[$key];
        $data->impuesto_id=is_numeric($data->impuesto_id)?$data->impuesto_id:0;
        $data->importe=is_numeric($data->importe)?$data->importe:0;
        //log: :info($data->descripcion);
        DB::table('gestion')->where('id',$data->id)->update([
            'impuesto_id'=>$data->impuesto_id,
            'descripcion'=>$data->descripcion,
            'importe'=>$data->importe,
            'updated_at'=>Carbon::now(),
        ]);
        $this->desglosar();
    }

    public function desglosar(){
        $x=DB::table('gestion')->where('basegestion_id',$this->idmov)->orderBy('id','asc')->get();
        //$this->desglose=Utils: : o b j e ctToArray($x);
        $this->desglose=$x;
        $this->total=0;
        $calc=$this->impuestos;
        
        foreach($calc as $impu){
            $impu->suma=0;
        }
        foreach($x as $lin){
            // $x NO es un array
            $impo=$lin->importe;
            $impuestoid=$lin->impuesto_id;
            foreach($calc as $impu){
                if($impu->id==$impuestoid)
                $impu->suma+=$impo;
            }
        }
        foreach($calc as $impu){
            $impu->suma=round($impu->suma,2);
            $impu->total=round($impu->suma*(1+($impu->porcentaje/100)),2);
            $this->total+=$impu->total;
        }
        Basegestion::where('id',$this->idmov)->update(['importe'=>$this->total]);
        $this->ficha->importe=$this->total;

    }

    public function eliminardocumento(){
        Gestion::where('basegestion_id',$this->idmov)->delete();
        Basegestion::find($this->idmov)->delete();
        return redirect(route($this->ruta.'-management'))->with('status',$this->ruta.' eliminado correctamente');
    }

    public function update($end=0){
        $this->validate();
        $this->ficha->update();
        if($end==1){
            return redirect(route($this->ruta.'-management'));
        }
        //return redirect(route($this->ruta.'-management'))->with('status',$this->singular.' actualizado.');
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view('livewire.gestion.edit');
    }
}
