<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
//use App\Models\ Role;
use Log;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendVerification;
use Carbon\Carbon;

class Register2 extends Component
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
        auth()->login($user);
        $this->notify(new SendVerification($user->id));
        return redirect('micuenta');
    } 

    public function mount(){

        //$this->roles=Role::all();
        $activo=env('REGISTROACTIVO2',true);
        if(!$activo){
            $this->vista="livewire.authentication.error.registrodeshabilitado";
        }
    }

    public function render()
    {
        return view($this->vista);
    }
}
