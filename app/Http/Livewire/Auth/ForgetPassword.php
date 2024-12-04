<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Notifications\ResetPassword;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

class ForgetPassword extends Component
{
    use Notifiable;
    public $email='';
    protected $rules = [
        'email' => 'required|email',
    ];
    public function render()
    {
        return view('livewire.auth.forget-password');
    }
    public function routeNotificationForMail() {
        return $this->email;
    }
    public function show(){
        $this->validate();
        $user = User::where('email', $this->email)->first();
            if($user){
                $this->notify(new ResetPassword($user->id));
                return back()->with('status', "Si existe su cuenta se ha enviado un correo con el enlace de recuperación.");
                return back()->with('status', "Se ha enviado un correo con el enlace para recuperar su contraseña.");
            } else {
                return back()->with('status', "Si existe su cuenta se ha enviado un correo con el enlace de recuperación.");
                return back()->with('email', "No podemos encontrar una cuenta con esta dirección de email.");
            }
}
}
