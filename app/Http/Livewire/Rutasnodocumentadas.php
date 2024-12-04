<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Accesoilegal;
use Log;
use DB;
use Session;
use Request;
use App\Http\Utils;
use Illuminate\Support\Facades\Auth;

class Rutasnodocumentadas extends Component
{
    public static function grabarevento(){
        $userid=Auth::id();
        $cip=Request::getClientIp();
        $bi=Utils::browserInfo(); // no quitar establece variables de sesion en el arranque!!!
        //Log::info("no documentado ".Request::url());
        $ruta=Request::url();

        if (str_contains($ruta, 'ohmyphoto.app/storage/tmpgallery/')) {
            $ruta="gettin image not exists";
            return;
        }
        if (str_contains($ruta, '.css.map')) {
            $ruta="gettin image not exists";
            return;
        }
        if (str_contains($ruta, '.min.map')) {
            $ruta="gettin image not exists";
            return;
        }
        if (str_contains($ruta, '.js.map')) {
            $ruta="gettin image not exists";
            return;
        }
        if (str_contains($ruta, '.png')) {
            $ruta="gettin image not exists";
            return;
        }

        Accesoilegal::insert([
            'ip'=>$cip,
            'ruta'=>$ruta,
            'navegador'=>$bi['plataforma']." ".$bi['navegador']." ".$bi['version'],
            'is_mobile'=>!$bi['desktop'],
        ]);
        //return redirect('dashboard');
    }
}
