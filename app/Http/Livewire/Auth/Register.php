<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
//use App\Models\ Role;
use Log;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendVerification;
use Carbon\Carbon;

class Register extends Component
{
    use Notifiable;
    public $acepto=false;
    public $leido=false;
    public $name ='';
    public $email = '';
    public $password = '';
    public $vista = 'livewire.auth.register';
    //public $role_id='';
    //public $roles;

    protected $rules=[
    'name' => 'required|min:3',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6',
];
    protected $messages=[
    'name' => 'Introduzca nombre de empresa/usuario',
    'email' => 'Introduzca un email válido. ¿está ya registrado con este email?',
    'password' => 'Introduzca una contraseña de 6 ó más caracteres',
];

    public function showaccept(){
        if($this->leido==false){
            $this->acepto=true;
            $this->leido=true;
            $this->dispatch('showmodalreg', []);
        }
    }
    public function showaccept2(){
        $this->dispatch('showmodalreg', []);
        $this->leido=true;
        $this->acepto=true;
    }

    public function store(){
        $attributes = $this->validate();
        if($this->acepto==false){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Por favor, acepte los términos y condiciones',  'title' => 'ATENCIÓN']);
            return;
        }
        $user = User::create($attributes);


        if(1==1){
            // fecha null pone caducidad 1 mes
            $unmes = Carbon::now()->addMonth();
            //$endyear = Carbon::create(2024, 12, 31, 0, 0, 0, 'Europe/Madrid'); // periodo de gracia arranque app
            $endyear = Carbon::create(2025, 1, 31, 0, 0, 0, 'Europe/Madrid'); // periodo de gracia arranque app
            if($unmes->diffInDays($endyear)>0)// negativo, unmes mayor que fin de año positivo endyear mayor
                $unmes=$endyear;
            User::where('id',$user->id)->update(['caducidad'=>$unmes]);
        }


        auth()->login($user);
        $this->notify(new SendVerification($user->id));
        return redirect('micuenta');
    } 

    public function mount(){

        //$this->roles=Role::all();
        $activo=env('REGISTROACTIVO',true);
        if(!$activo){
            $this->vista="livewire.authentication.error.registrodeshabilitado";
        }
    }

    public function render()
    {
        return view($this->vista);
    }
}
