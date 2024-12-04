<?php

namespace App\Http\Livewire;

use App\Models\Accesoilegal;
use App\Models\User;
use App\Models\User2;
use App\Models\Cliente;
use App\Models\Formaspago;
//use App\Models\Sus cripciones;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Utils;
use Config;
use File;
use DB;
use Log;
use Livewire\WithFileUploads;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Activacion2fa;
use App\Notifications\SendVerification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use function Illuminate\Events\queueable;
use Request;
use Stripe\Stripe;
// pruebas mail quitar despues
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
// pruebas mail quitar despues

class Micuenta extends Component
{
    use Notifiable;
    use WithFileUploads;
    public User $ficha;
    public User2 $ficha2;
    public Formaspago $ficha3;
    public $userid;
    public $photoname;
    public $botonesfirma=true;
    public $files1=[];
    public $files2=[];
    public $files3=[];
    public $usuarios=[];
    //public $sus cripciones=[];
    public $email="";
    public $contra="";
    public $contra1="";
    public $contra2="";
    public $contraactual2="";
    public $firma="";
    public $doblefactor=0; // 0 desactivado 1 en proceso 2 activado
    public $qr;
    public $secret="";
    public $log="";
    public $codigoga="";
    public $seccion=1;
    public $administrador=false;
    public $fechacaducidad;
    public $suscrito=0;
    public $endyear;
    public $errormail="";
    //public $activa=false;
    public $ilegales=0;

    protected $listeners = [
        'savesign' => 'savesign',
    ];

    protected function rules(){
        return [
            'ficha2.nombre' => 'required|max:100',
            'ficha2.nombre2' => 'max:100',
            'ficha2.nif' => 'required|max:15',
            'ficha2.telefono' => 'required|max:12',
            'ficha2.domicilio' => 'required|max:100',
            'ficha2.codigopostal' => 'required|max:10',
            'ficha2.poblacion' => 'required|max:50',
            'ficha2.provincia' => 'required|max:50',
            'ficha2.mail_direccion' => 'max:200',
            'ficha2.mail_username' => 'max:200',
            'ficha2.mail_password' => 'max:200',
            'ficha2.mail_smtp' => 'max:200',
            'ficha2.iban' => 'max:30',
            'ficha.email' => '',
            'ficha3.efectivo' => '',
            'ficha3.transferencia' => '',
            'ficha3.redsys' => '',
            'ficha3.rsreal' => '',
            'ficha3.rscodcomercio' => '',
            'ficha3.rsclacomercio' => '',
            'ficha3.rsterminal' => 'required|numeric',
            'ficha3.stripe' => '',
            'ficha3.stripe_publica' => '',
            'ficha3.stripe_secreta' => '',
            'ficha3.stripeprc' => 'required|numeric',
            'ficha3.paypal' => '',
            'ficha3.ppalemail' => '',
            'ficha3.ppalclientid' => '',
            'ficha3.ppalsecret' => '',
            'ficha3.ppalprc' => 'required|numeric',
            'ficha3.bizum' => '',
            'ficha3.bizumtelefono' => '',
            //'ficha.contra' => '', // si marco required no me actualiza los datos de ficha2 si no la pongo...
        ];
    }

    public function mount(){

        $xp=Request::all();
        if(isset($xp['pos'])){
            if($xp['pos']==7){
                $this->seccion=7; // viene del billing de stripe
            }
            if($xp['pos']==0){
                $this->dispatch('mensajelargo',['type' => 'error',  'message' => 'Contrate un plan de suscripción para continuar usando la aplicación.',  'title' => 'ATENCIÓN']);
                $this->seccion=7; // viene del billing de stripe
            }
        }

        $this->userid=Auth::id();
        $this->doblefactor=is_null(auth()->user()->google2fa_secret)?0:2;
        $this->ficha = User::where('id',$this->userid)->find($this->userid);
        $this->email=$this->ficha->email;
        if(User2::where('id',$this->userid)->count()==0){
            User2::insert(['id'=>$this->userid]);
        }
        if(Formaspago::where('id',$this->userid)->count()==0){
            Formaspago::insert(['id'=>$this->userid]);
        }
        $this->ficha2 = User2::where('id',$this->userid)->find($this->userid);
        $this->ficha3 = Formaspago::find($this->userid);
        $this->ficha3->efectivo=$this->ficha3->efectivo==1?true:false;
        $this->ficha3->transferencia=$this->ficha3->transferencia==1?true:false;
        $this->ficha3->redsys=$this->ficha3->redsys==1?true:false;
        $this->ficha3->stripe=$this->ficha3->stripe==1?true:false;
        $this->ficha3->rsreal=$this->ficha3->rsreal==1?true:false;
        $this->ficha3->paypal=$this->ficha3->paypal==1?true:false;
        $this->ficha3->bizum=$this->ficha3->bizum==1?true:false;
        if(strlen($this->ficha2->mail_password)>0)
            $this->ficha2->mail_password=Utils::codificarPassword($this->ficha2->mail_password,"d");
        if(strlen($this->ficha2->firma)>0){
            $this->dispatch('vselectsetvalue', ['idcli' => $this->ficha2->firma]);
            //$this->firma=($this->ficha2->firma);
        }

        if($this->userid==6)
            $this->administrador=true;
        if($this->userid==4)
            $this->administrador=true;
        if($this->userid==9)
            $this->administrador=true;
        if($this->administrador){
            $this->lusuarios();
            $logFile = base_path() . '/storage/logs/laravel.log';
            $this->log=file_get_contents($logFile);
            $this->ilegales=Accesoilegal::orderBy('id','asc')->get();
        }

        //$this->sus cripciones=Sus cripciones::where('user_id',$this->userid)->orderBy('alta','asc')->get();//->toArray();
        //foreach($this->sus cripciones as $sus){
        //    $this->activa=$sus['activa']==1?true:false;
        //}

        $this->fechacaducidad=Auth::user()->caducidad;
        $this->suscrito=Utils::suscripcionactiva(); // 0 no tiene licencia 1 periodo de prueba 2 tiene una suscripcion

        //$usageRecords = Auth::user()->subscription('default')->usageRecords();
        //Log::info($usageRecords);

        //$usageRecords = Auth::user()->subscription('default')->usageRecordsFor('price_metered');




        if(1==2){
        $this->unmes = Carbon::now()->add(30, 'day');
        $this->unmes = Carbon::now()->addMonth()->addMonth()->addMonth();
        $this->unmes = Carbon::now()->addMonth();
        $this->endyear = Carbon::create(2024, 12, 31, 0, 0, 0, 'Europe/Madrid');
        if($this->unmes->diffInDays($this->endyear)>0)// negativo, unmes mayor que fin de año positivo endyear mayor
        {
            $this->unmes=$this->endyear;
        }
        //Log::info($this->unmes);
            $this->unmes = Carbon::create(2024, 11, 31, 0, 0, 0, 'Europe/Madrid');
            $this->endyear = Carbon::create(2024, 12, 31, 0, 0, 0, 'Europe/Madrid');
            Log::info($this->unmes->diffInDays($this->endyear)); // positivo unmes menor endyear negativo unmes mayor
        }

        //$xuser = User::find(28);
        //Log::info($xuser);
        //Log::info($xuser->name);
        //$xuser = User::where('id',28)->get()[0];
        //Log::info($xuser);
        //Log::info($xuser->name);

    }

    public function lusuarios($order="id"){
            //$this->usuarios=User::select('id','name','email','created_at')->orderBy('created_at','asc')->get();
            $this->usuarios=DB::select(
                "select * from (select users.id,users.name,email,created_at,
                (select count(*) from galerias where user_id=users.id) as galerias,
                (select round(sum(originalsize)/1024/1024/1024,2) from binarios left join galerias on galerias.id=binarios.galeria_id where galerias.user_id=users.id) as gigas 
                 from users) xx order by $order"
            );
    }

    public function render()
    {
        return view('livewire.micuenta');
    }

    public function vseccion($num)
    {
        $this->seccion=$num;
        //$this->dispatch('$refresh');
    }
    
    public function vacialog()
    {
        Utils::vacialog();
        $this->log="";
    }
    public function vaciailegales()
    {
        Accesoilegal::where('id','<>',-1)->delete();
        $this->ilegales=Accesoilegal::orderBy('id','asc')->get();
    }

    public function updateuser2($checkmail=false){
        $this->validate();
        $this->ficha2->mail_password=Utils::codificarPassword($this->ficha2->mail_password,"e");
        $this->ficha2->update();
        $this->ficha2->mail_password=Utils::codificarPassword($this->ficha2->mail_password,"d");
        $this->ficha->name=$this->ficha2->nombre;
        $this->ficha->update();
        $this->updatestripecustomer();

        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Datos actualizados',  'title' => 'ATENCIÓN']);
        if($checkmail && strlen($this->ficha2->mail_direccion)>0 && strlen($this->ficha2->mail_username)>0 && strlen($this->ficha2->mail_smtp)>0){
            // testeamos el correo electronico
            Config::set('mail.driver',"smtp"); // si es gmail solo funciona con driver mail
            if($this->ficha2->mail_smtp=="smtp.gmail.com"){
                //Config::set('mail.driver',"mail"); // si es gmail solo funciona con driver mail
                //Config::set('mail.port',465);
                //Config::set('mail.encryption',"ssl");
                //Config::set('mail.port',587); // gmail 465 en hetzner parece que está bloqueado
                //Config::set('mail.encryption',"tls"); // gmail 587 se supone q es tls
            }

            Config::set('mail.port',587); // gmail 465 en hetzner parece que está bloqueado
            Config::set('mail.encryption',"tls"); // gmail 587 se supone q es tls

            Config::set('mail.host',$this->ficha2->mail_smtp);
            Config::set('mail.from',array(
                    'address'=>$this->ficha2->mail_direccion,
                    'name'=>$this->ficha2->nombre));
            Config::set('mail.username',$this->ficha2->mail_username);
            Config::set('mail.password',$this->ficha2->mail_password);

            //log: :info($this->ficha2->mail_smtp);
            //log: :info($this->ficha2->mail_password);
            $direcciones=[$this->ficha2->mail_direccion];
            $asunto="Prueba de envío de email OhMyPhoto";

            try {
                $this->errormail="";
                Mail::raw('Su cuenta de correo se ha configurado correctamente.', function ($message) use ($direcciones,$asunto) {
                    $message->to($direcciones)->subject($asunto);
                });
                //Mail: :send($vista, array(
                //    'datos' => $datos,
                //), function ($message) use ($direcciones,$asunto) {
                //    $message->to($direcciones)->subject($asunto);
                //});
                $ok = true;
            } catch (\Exception $ex) {
                //Utils::vacialog();
                $this->errormail=Utils::extraererrormail($ex);
                Log::info(Utils::extraererrormail($ex)); // envía el error al registro de logs
                $ok = false;
            }
            if($ok){
                $this->dispatch('mensaje',['type' => 'success',  'message' => 'La configuración de correo es correcta.',  'title' => 'ATENCIÓN']);
            }
            if(!$ok){
                $this->dispatch('mensaje',['type' => 'error',  'message' => 'La configuración de correo no es correcta.',  'title' => 'ATENCIÓN']);
            }
        }
    }

    public function deleteimagelogo(){
        $this->ficha2->logo="";
        $this->ficha2->update();
    }
    
    public function deleteimagewater(){
        $this->ficha2->marcaagua="";
        $this->ficha2->update();
    }

    public function updatefpago(){
        $this->validate();
        $this->ficha3->update();
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Datos actualizados',  'title' => 'ATENCIÓN']);
    }

    public function updatesecurity(Request $request){
        $email=$this->email;
        $emailficha=$this->ficha->email;
        $contra=$this->contra;
        $contra1=$this->contra1;
        $contra2=$this->contra2;

        $hashedPassword = auth()->user()->password; // contraseña actual del usuario
        //log: :info($hashedPassword);
        //log: :info(Hash::make('secret'));

        if ($contra1!==$contra2) {
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'La nueva contraseña no coincide',  'title' => 'ATENCIÓN']);
            return;
        }
        
        if (!Hash::check($contra , $hashedPassword)) {
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'La contraseña actual introducida no es correcta',  'title' => 'ATENCIÓN']);
            return;
        }

        if(User::where('email',$email)->where("id","<>",$this->userid)->count()>0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Esta dirección de correo ya está en uso, seleccione otra',  'title' => 'ATENCIÓN']);
            return;
        }

        // validaciones correctas

        if($emailficha!=$email){
            $this->ficha->email=$email;
            $this->ficha->email_verified_at=null;
            $this->email=$email;
            $this->ficha->update();
            $this->notify(new SendVerification($this->userid));
        }
        if(strlen($contra1>0)){
            $this->ficha->password=$contra1;
            $this->ficha->update();
        }



        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Datos actualizados',  'title' => 'ATENCIÓN']);
    }

    public function saveimage($xzx=""){
        switch($xzx){
            case "files1":
                $files=$this->files1;
                break;
            case "files2":
                $files=$this->files2;
                break;
            case "files3":
                $files=$this->files3;
                break;
        }
        if(empty($files)){
            return;
        }
        $this->photoname=$this->userid."_".Utils::randomString(5).".jpg";
        //log: :info(count($this->files));
        $nombreoriginal=$files[0]->getClientOriginalName();
        //log: :info($nombreoriginal);
        $files[0]->storeAs('photos',$this->photoname);

        $storage_path = storage_path('app/photos')."/";
        $filee=$storage_path.$this->photoname;
        $fileemin=str_replace('.jpg','_min.jpg',$filee);
        //log: :info($filee);
        if(file_exists($filee)){
            //log: :info("existe");
        }else{
            //log: :info("no existe");
        }
        //$image = Image: :make($filee);
        // Main Image Upload on Folder Code
        //$image->resize(425,242, function ($const) {
            //$const->aspectRatio();
        //})->save($fileemin);
        $base64="";
        $base64=base64_encode(File::get($filee));
        //log: :info($this->base64);
        File::delete($filee);
        File::delete($fileemin);
        $this->files1=[];
        $this->files2=[];
        $this->files3=[];
        $this->ficha2->mail_password=Utils::codificarPassword($this->ficha2->mail_password,"e");
        switch($xzx){
            case "files1":
                $this->ficha2->logo=$base64;
                $this->ficha2->update();
                break;
            case "files2":
                $this->ficha2->marcaagua=$base64;
                $this->ficha2->update();
                break;
            case "files3":
                $this->ficha2->firma=$base64;
                $this->ficha2->update();
                $this->botonesfirma=false;
                $this->dispatch('vselectsetvalue', ['idcli' => $this->ficha2->firma]);
                break;
        }
        $this->ficha2->mail_password=Utils::codificarPassword($this->ficha2->mail_password,"d");
    }

    public function savesign($firma)
    {
        if(strlen($firma)==0){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Firma vacía',  'title' => 'ATENCIÓN']);
            return;
        }
        $image=str_replace("data:image/png;base64,","",$firma);
        $this->ficha2->firma=$image;
        User2::where('id',$this->userid)->update(['firma'=>$image]);
        // no no no se carga contraseña mail $this->ficha2->update();
        $this->dispatch('mensaje',['type' => 'success',  'message' => 'Firma guardada',  'title' => 'ATENCIÓN']);
    }

    public function activar2fa(){
        if(strlen($this->contraactual2)==0){
            return back()->with(['error2fa' =>"La contraseña actual no es correcta, no se puede continuar."]);
        }        
        $hashedPassword = auth()->user()->password; // contraseña guardada
        if (!Hash::check($this->contraactual2 , $hashedPassword)) {
            $this->contraactual2="";
            return back()->with(['error2fa' =>"La contraseña actual no es correcta, no se puede continuar."]);
        }
        $this->contraactual2="";

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');
        // Save the registration data in an array
        $registration_data = [];
        // Add the secret key to the registration data
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();
        $registration_data["email"] = $this->email;
        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $this->qr = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );
        $this->secret=$registration_data['google2fa_secret'];
        $this->codigoga="";
        $this->doblefactor=1; // en proceso
        //log: :info($QR_Image);
    }

    public function confirmar2fa(){
        $secret = $this->codigoga;
        $google2fa = app('pragmarx.google2fa');
        $timestamp = $google2fa->verifyKey($this->secret, $secret);
        
        if ($timestamp !== false) {
            //$user->update(['google2fa_ts' => $timestamp]);
            $this->codigoga="";
            $users = User::findorFail(auth()->user()->id);
            $users->google2fa_secret = $this->secret;
            $users->save();
            $this->doblefactor=2; // con doble factor activado
            //$this->email=$this->email;
            $this->notify(new Activacion2fa(0,$this->email,$this->secret));
            auth()->logout();
            return redirect('/sign-in');
                return back()->with(['ok2fa' =>"Se ha activado la autenticación de doble factor"]);
            // successful
        } else {
            // failed
            $this->codigoga="";
            return back()->with(['error2fa' =>"El código introducido no es correcto"]);
        }
    }

    public function desactivar2fa(){
        if(strlen($this->contraactual2)==0){
            return back()->with(['error2fa' =>"La contraseña actual no es correcta, no se puede continuar."]);
        }
        $hashedPassword = auth()->user()->password; // contraseña guardada
        if (!Hash::check($this->contraactual2 , $hashedPassword)) {
            $this->contraactual2="";
            return back()->with(['error2fa' =>"La contraseña actual no es correcta, no se puede continuar."]);
        }
        $this->contraactual2="";
        $users = User::findorFail(auth()->user()->id);
        $users->google2fa_secret = null;
        $users->save();
        $this->doblefactor=0; // sin doble factor activado
    }

    public function updatestripecustomer(){
        if (Auth::user()->hasStripeId()) {
            $options=[
                'phone'=>$this->ficha2->telefono,
                'metadata'=>[
                    'id'=>$this->ficha2->nif
                ]
            ];
            Auth::user()->updateStripeCustomer($options);
            //Log::info($options);
            //Log::info(Auth::user()->asStripeCustomer());
        }
    }

    public function stripesubscribe($tipo){
        $this->updatestripecustomer();
        if(Auth::user()->subscribed('default')){
            $this->dispatch('mensaje',['type' => 'error',  'message' => 'Ya tienes algún tipo de suscripción, pulse en gestionar su suscripción',  'title' => 'ATENCIÓN']);
            return;
        }
        switch($tipo){
            case 1:
                $prodid=env('ID_MENSUAL');
                $dess="default";
                break;
            case 2:
                $prodid=env('ID_TRIMESTRAL');
                $dess="default";
                break;
            case 3:
                $prodid=env('ID_ANUAL');
                $dess="default";
                break;
        }
        return auth()->user()
            ->newSubscription($dess, $prodid)
            //->trialDays(5)
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => route('micuenta',['pos'=>7]),
                'cancel_url' => route('micuenta',['pos'=>7]),
            ]);
    }

    public function test(){
        Utils::vacialog();


        $asunto="Su sesión  caducará mañana userid ";
        $direcciones=['address'=>'josej69@gmail.com','name'=>'jose javier'];

        Config::set('mail.driver',"smtp"); // si es gmail solo funciona con driver mail
        $useridxa=6;
        $x=DB::table('users2')
            ->where('id',$useridxa)
            ->where('mail_direccion',"<>","")
            ->where('mail_username',"<>","")
            ->where('mail_smtp',"<>","")
            ->get()->toArray();
        if(count($x)==0){
            // no hay cuenta de correo del cliente
            Log::info("sin cuenta");
            $mailport=env('MAIL_PORT');
            $mailencryption=env('MAIL_ENCRYPTION');
            $mailhost=env('MAIL_HOST');
            $mailusername=env('MAIL_USERNAME');
            $mailpassword=env('MAIL_PASSWORD');
            $xy=DB::table('users')->where('id',$useridxa)->get()->toArray();
            $mailfrom=array(
                    'address'=>env('MAIL_FROM_ADDRESS'),
                    'name'=>$xy[0]->name);
            $reply=$xy[0]->email;
        }else{
            Log::info("con cuenta");
            $mailport=587;
            $mailencryption="tls";
            $mailhost=$x[0]->mail_smtp;
            $mailusername=$x[0]->mail_username;
            $mailpassword=Utils::codificarPassword($x[0]->mail_password,"d");
            $mailfrom=array(
                    'address'=>$x[0]->mail_direccion,
                    'name'=>$x[0]->nombre);
            $reply=$x[0]->mail_direccion;
        }

        $transport = new EsmtpTransport($mailhost, $mailport, $mailencryption);
        $transport = new EsmtpTransport($mailhost, $mailport, false);
        $transport->setUsername($mailusername);
        $transport->setPassword($mailpassword);
        //$transport->setAutoTls(false);
        $mailer = new Mailer($transport);

        $body = "esto es una prueba de mail";
        $bodyfull=Utils::emailtocid($body);
    
        $email = (new Email())
            ->from(new Address($mailfrom['address'],$mailfrom['name']))
            ->to(new Address($direcciones['address'],$direcciones['name']))
            //->from($mailfrom)
            //->to($direcciones)
            ->replyTo($reply)
            ->subject($asunto)
            ->html($bodyfull['body']);
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->text('Sending emails is fun again!')
        foreach($bodyfull['attach'] as $bf){
            $email->addPart((new DataPart(base64_decode($bf['base64']), $bf['cid'], 'image/jpeg'))->asInline());
            //$email->attachData(base64_decode($bf['base64']),$bf['cid'], [
            //    'as' => $bf['cid'],
            //    'mime' => 'image/jpeg',
            //]);
        }
            
        try {
            $mailer->send($email);
        } catch (\Exception $ex) {
            //Utils::vacialog();
            log::info("fallo envio mail console.php userid "); // envía el error al registro de logs
            log::info(Utils::extraererrormail($ex)); // envía el error al registro de logs
            $ok = false;
        }









        return;
        Utils::vacialog();
        $xaxa=DB::select("select id,cliente_id,user_id,nombre,clavecliente 
        from galerias where date_add(curdate(),interval 1 day)=caducidad 
        and pagado=0 
        and pagadomanual=0 
        and seleccionconfirmada=0 
        and enviado=1 
        and archivada=0 
        and eliminada=0 
        and cliente_id>0");
        //Log::info($xaxa);


        foreach($xaxa as $y){
            $cliente = Cliente::find($y->cliente_id);
            $xuser = User::find($y->user_id);
            $xuser2 = User2::find($y->user_id);
            $asunto="Su sesión ".$y->nombre." caducará mañana";
            $reply=Utils::cargarconfiguracionemailempresa($y->user_id);
            if($y->user_id==4)
                continue;
            Log::info($y->user_id);
            Log::info(Config::get('mail'));
            //Log::info(Config::get('mail.from.name')." ".Config::get('mail.from.address'));
        }

    }


}
