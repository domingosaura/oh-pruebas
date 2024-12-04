<?php

namespace App\Http\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\User;
use App\Models\User2;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Utils;
use Log;

class Rellenarporcliente extends Component
{

    public Cliente $fichacli;
    public $vista="livewire.cliente.rellenarporcliente";
    public $userid;
    public $logo;
    public $empresa;
    use AuthorizesRequests;
    
    protected function rules(){

        //'data.ip' => ['required', 'unique:servers,ip,'.$this->id.','.$request->input('id').',id,hostname,'.$request->input('hostname')]

        return [
            'fichacli.nombre' => 'required|max:100',
            'fichacli.apellidos' => 'required|max:100',
            'fichacli.email' => 'required|email|max:200|unique:clientes,email,'.$this->fichacli->id.',id,user_id,'.$this->userid,
            'fichacli.permiteimagenes' => '',
            'fichacli.permitecomunicaciones' => '',
            'fichacli.nif' => '',
            'fichacli.nombrepareja' => '',
            'fichacli.notasinternas' => '',
            'fichacli.apellidospareja' => '',
            'fichacli.nifpareja' => '',
            'fichacli.hijo1' => '',
            'fichacli.edad1' => '',
            'fichacli.hijo2' => '',
            'fichacli.edad2' => '',
            'fichacli.hijo3' => '',
            'fichacli.edad3' => '',
            'fichacli.hijo4' => '',
            'fichacli.edad4' => '',
            'fichacli.hijo5' => '',
            'fichacli.edad5' => '',
            'fichacli.hijo6' => '',
            'fichacli.edad6' => '',
            'fichacli.domicilio' => '',
            'fichacli.cpostal' => '',
            'fichacli.poblacion' => '',
            'fichacli.provincia' => '',
            'fichacli.telefono' => '',
        ];
    }

    //Route::get('datosdecliente/{user}/{cliente}/{md5}', Rellenarporcliente::class)->where('id', '[0-9]+')->name('rellenarporcliente');
    public function mount($user,$cliente,$md5){
        $this->fichacli = Cliente::find($cliente);
        $this->fichacli->activo=$this->fichacli->activo==1?true:false;
        $this->fichacli->permiteimagenes=$this->fichacli->permiteimagenes==1?true:false;
        $this->fichacli->permitecomunicaciones=$this->fichacli->permitecomunicaciones==1?true:false;
        $this->userid=$this->fichacli->user_id;
        // comprobamos si el cliente es del usuario correcto
        if($this->userid!=$user ||!$this->fichacli->activo){
            $this->vista="livewire.authentication.error.error404";
            return;
        }
        // si el cliente no tiene mail
        if(strlen(trim($this->fichacli->email))==37 && !strpos($this->fichacli->email,"@mailarrellenarporelcliente.oh")===false)
            $this->fichacli->email="";
        // comprobamos md5 con fecha de 7 dias
        $datee = Utils::left(Carbon::now(),10); // enlace solo para hoy y mañana
        $datee1 = Utils::left(Carbon::now()->add(1, 'day'),10); // enlace solo para hoy y mañana
        $datee2 = Utils::left(Carbon::now()->add(2, 'day'),10); // enlace solo para hoy y mañana
        $datee3 = Utils::left(Carbon::now()->add(3, 'day'),10); // enlace solo para hoy y mañana
        $datee4 = Utils::left(Carbon::now()->add(4, 'day'),10); // enlace solo para hoy y mañana
        $datee5 = Utils::left(Carbon::now()->add(5, 'day'),10); // enlace solo para hoy y mañana
        $datee6 = Utils::left(Carbon::now()->add(6, 'day'),10); // enlace solo para hoy y mañana
        $datee7 = Utils::left(Carbon::now()->add(7, 'day'),10); // enlace solo para hoy y mañana
        $md5_1=md5($cliente.$datee."dattacli");
        $md5_2=md5($cliente.$datee1."dattacli");
        $md5_3=md5($cliente.$datee2."dattacli");
        $md5_4=md5($cliente.$datee3."dattacli");
        $md5_5=md5($cliente.$datee4."dattacli");
        $md5_6=md5($cliente.$datee5."dattacli");
        $md5_7=md5($cliente.$datee6."dattacli");
        $md5_8=md5($cliente.$datee7."dattacli");
        if($md5!=$md5_1 && $md5!=$md5_2 && $md5!=$md5_3 && $md5!=$md5_4 && $md5!=$md5_5 && $md5!=$md5_6 && $md5!=$md5_7 && $md5!=$md5_8){
            $this->vista="livewire.authentication.error.error404";
            return;
        }
        $xuser = User::find($this->fichacli->user_id);
        $xuser2 = User2::find($this->fichacli->user_id);
        $this->logo=$xuser2->logo;
        $this->empresa=$xuser2->nombre;
    }

    public function update(){
        
        $this->validate();

        $this->fichacli->edad1=strlen($this->fichacli->edad1)==10?$this->fichacli->edad1:null;
        $this->fichacli->edad2=strlen($this->fichacli->edad2)==10?$this->fichacli->edad2:null;
        $this->fichacli->edad3=strlen($this->fichacli->edad3)==10?$this->fichacli->edad3:null;
        $this->fichacli->edad4=strlen($this->fichacli->edad4)==10?$this->fichacli->edad4:null;
        $this->fichacli->edad5=strlen($this->fichacli->edad5)==10?$this->fichacli->edad5:null;
        $this->fichacli->edad6=strlen($this->fichacli->edad6)==10?$this->fichacli->edad6:null;

        $this->fichacli->update();
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Sus datos se han actualizado correctamente',  'title' => 'ATENCIÓN']);

        //return redirect(route('cliente-management'))->with('status',$this->singular.' actualizado.');
    }

    public function render()
    {
        //$this->authorize('manage-items', User::class);
        return view($this->vista);
    }
}
