<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Log;

class Activacion2fa extends Notification
{
    use Queueable;
    public $userid;
    public $usermail;
    public $secret;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userid,$usermail,$secret)
    {
        $this->userid=$userid;
        $this->usermail=$usermail;
        $this->secret=($secret);
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
        return (new MailMessage)
                    ->subject('Activación de sistema de autenticación de doble factor')
                    ->line('Se ha activado el sistema de autenticación de doble factor')
                    ->line('Por favor guarde el siguiente código para recuperar su cuenta en caso de pérdida de acceso desde su app de autenticación:')
                    ->line($this->secret);
                    //->line('¡Hola!')
                    //->action('Verificar e-mail', $ruta )
                    //->line("Si usted no se ha registrado por favor contacte con nosotros.");
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
