<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Http\Utils;

class SendVerification extends Notification
{
    use Queueable;
    public $token;
    public $userid = 0;
    public $codigo = "";
    public $verificado = false;
    public $email = "";
    public $md5 = "";

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable)
    {
        $userid=Auth::id();
        $this->email=Auth::user()->email;
        $ficha1 = User::find($userid);
        $this->email=$ficha1->email;
        $this->md5=Utils::left(md5($this->email),5);
        return (new MailMessage)
                    //->line('¡Hola!')
                    ->subject('Verificación de e-mail en OhMyPhoto')
                    ->line('Recibes este email de verificación de cuenta de tu correo electrónico.')
                    ->line('El código de verificación es: '.$this->md5);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
