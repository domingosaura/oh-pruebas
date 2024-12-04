<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\User;
use Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Utils;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendVerification;

class Verificaremail extends Component
{
    use Notifiable;
    public $userid = 0;
    public $codigo = "";
    public $verificado = false;
    public $email = "";
    public $md5 = "";
    public function mount(){
        $this->userid=Auth::id();

        $this->verificado=Auth::user()->hasVerifiedEmail();
        $this->email=Auth::user()->email;
        $this->md5=Utils::left(md5($this->email),5);


    }
    public function resendmail(){
        if($this->verificado){
            return redirect('dashboard');
        }

        $this->notify(new SendVerification($this->userid));

        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Revise su bandeja de entrada',  'title' => 'ATENCIÓN']);

    }
    public function updatesecurity()
    {
        if($this->verificado){
            return redirect('dashboard');
        }
        if($this->md5!=$this->codigo){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'El código no es correcto',  'title' => 'ATENCIÓN']);
            return;
        }
        User::where('id',$this->userid)->update(['email_verified_at'=>Carbon::now()]);
        User::where('id',$this->userid)->where('created_at',null)->update(['created_at'=>Carbon::now()]);
        return redirect('dashboard');
    }
    public function render()
    {
        return view('livewire.verificaremail');
    }
}
