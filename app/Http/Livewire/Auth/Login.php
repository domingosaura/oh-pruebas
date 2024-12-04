<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Validation\ValidationException;
use DB;
use Log;
use Auth;
use Request;
use App\Http\Utils;
use Illuminate\Support\Facades\Mail;
use App\Models\Iniciosdesesion;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use App\Models\Sesion;
use App\Models\User;
use App\Models\Accesoilegal;
use Session;

class Login extends Component
{
    public $email='';
    public $password='';
    public $aut2='';

    protected $rules= [
        'email' => 'required|email',
        'password' => 'required',
        //'aut2' => ''

    ];

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function mount() {

        //$this->fill(['email' => 'admin@material.com', 'password' => 'secret']);    
       
    }
    
    public function store()
    {
        //log: :info("pass");
        $attributes = $this->validate();
        //log: :info($attributes['password']);


        // bloqueo por demasiados intentos
        $noww=Carbon::now();
        //Log::info($noww);
        $noww10=$noww->subUnitNoOverflow('minute', 10, 'day');
        //Log::info($noww10);
        $errores=Accesoilegal::where('ip',Request::getClientIp())->where('is_login',true)->where('created_at',">=",$noww10)->count();
        if($errores>15){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'demasiados intentos, pruebe dentro de 10 minutos',  'title' => 'ATENCIÓN']);
            return;
        }
        // bloqueo por demasiados intentos




        if (! auth()->attempt($attributes)) {
            Accesoilegal::insert([
                'ip'=>Request::getClientIp(),
                'ruta'=>"login erróneo user ".$this->email,
                'navegador'=>"",
                'is_mobile'=>false,
                'is_login'=>true,
            ]);

            //log: :info("passno");
            throw ValidationException::withMessages([
                'email' => 'Datos de inicio de sesión incorrectos.'
            ]);
        }


        $userid=Auth::id();


        $x=DB::table('users')
            ->select('email','google2fa_secret','caducidad')
            ->where('id',$userid)
            ->get()[0];

        if($x->caducidad==null){
            // fecha null pone caducidad 1 mes
            $unmes = Carbon::now()->addMonth();
            $endyear = Carbon::create(2024, 12, 31, 0, 0, 0, 'Europe/Madrid'); // periodo de gracia arranque app
            if($unmes->diffInDays($endyear)>0)// negativo, unmes mayor que fin de año positivo endyear mayor
                $unmes=$endyear;
            User::where('id',$userid)->update(['caducidad'=>$unmes]);
        }


        // autenticacion doble factor 2fa
        if(!is_null($x->google2fa_secret)){
            // comparar
            $google2fa = app('pragmarx.google2fa');
            $secret = $this->aut2; // el que han escrito
            $timestamp = $google2fa->verifyKey($x->google2fa_secret, $secret);
            if ($timestamp === false && $secret==$x->google2fa_secret) {
                // metieron la clave generada en su momento
                $timestamp=true;
            }
            
            if ($timestamp !== false) {
                // successful
            } else {
                // failed
                auth()->logout();
                throw ValidationException::withMessages([
                    'aut2' => 'Error en código único.'
                ]);
            }
        }
        //
        
        session()->regenerate();
        
        $userid=Auth::id();
        $email=Auth::user()->email;

        $session_id = session()->getId();
        //log: :info($userid." ".$session_id);

        //$suscrito=Utils::suscripcionactiva(); // 0 no tiene licencia 1 periodo de prueba 2 tiene una suscripcion
        //Session::put('suscrito',$suscrito);
        Session::forget('suscrito');

        $borrarlasdemassesiones=false;
        if($borrarlasdemassesiones){
            Sesion::where('user_id',$userid)->where('id',"<>",$session_id)->delete(); // solo dejamos una sesion por usuario
        }
        $dejarsolo3sesiones=true;
        if($dejarsolo3sesiones){
            $x=Sesion::select('id as idd')->where('user_id',$userid)->orderby('last_activity','desc')->get()->toArray();
            foreach($x as $key=>$sesion){
                if($key>1){
                    Sesion::where('user_id',$userid)->where('id',$sesion['idd'])->delete(); // solo dejamos una sesion por usuario
                }
            }
        }
        
        $agent = new Agent(); // https://github.com/jenssegers/agent
        //$reply=Auth::user()->email;
        $direcciones=[$email];
        $cip=Request::getClientIp();
        $asunto="Nuevo inicio de sesión en OhMyPhoto";
        Iniciosdesesion::insert([
            'is_mobile'=>$agent->isDesktop()?false:true,
            'ip'=>$cip,
            'navegador'=>$agent->platform().' '.$agent->browser().' '.$agent->version($agent->browser()),
            'user_id'=>$userid,
            'session_at'=>Carbon::now(),
        ]);
        
        $repo=Utils::cargarconfiguracionemailempresaarray($userid);
        $cuentapropia=$repo['configuradocliente'];
        $reply=$repo['direccion'];
        $emailok=true;
        try {
            Mail::raw("Se ha producido un inicio de sesión con su cuenta desde la ip ".$cip.".\n\nSi ha configurado su propia dirección de mail esta notificación también le indica que su correo está funcionando correctamente.\n\nSI NO HA SIDO USTED QUIEN HA INICIADO SESIÓN INTENTE CAMBIAR SU CONTRASEÑA LO ANTES POSIBLE.", function ($message) use ($direcciones,$asunto) {
                $message->to($direcciones)->subject($asunto);
            });
            $emailok=true;
        } catch (\Exception $ex) {
            Log::info("falla mail ".$ex);
            $emailok=false;
        }
        if($emailok==false && $cuentapropia){
            // algo ha ido mal la cuenta configurada por el cliente no es correcta
            // Log::info("fallo envio mail propio");
            return redirect('dashboard')->with('status','los datos de la cuenta de correo que tiene configurada son incorrectos.');
        }



        return redirect('dashboard');

    }
}
