<?php
declare(strict_types=1);
 
namespace App\Http\Controllers;
 
use App\Models\Galeria;
use App\Models\Pgaleria;
use App\Models\Cliente;
use App\Models\Binarios;
use App\Models\Binarios2;
use App\Models\Binarios3;
use App\Models\Binarios4;
use App\Models\Binarios5;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Log;
use Storage;
use Carbon\Carbon;
use Spatie\Image\Image as Spatie;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
use File;
use DB;
use App\Http\Utils;
//use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use ZipArchive;

class DownloadGallery extends Controller
{
    public function processprof($gallery,$md5,$_12)
    {
        // descarga por el fotografo
        //Log::info(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]));
        if(md5('cucu'.$gallery)!=$md5)
            return;
        $x=Galeria::select('nombre','id','user_id')->find($gallery);
        if(!$x){
            return;
        }
        //$storage_path = storage_path('app/public/tmpgallery')."/";
        //$storage_path = storage_path('app/livewire-tmp')."/";
        $storage_path = storage_path('app/public/tmpgallery')."/";
        //$nombrezip=str_replace(" ","_",$x->nombre.".zip");
        $nombrezip="galeria_".$x->id."_".md5('cucu'.$x->id).".zip";
        $userid=$x->user_id;
        $zip = new ZipArchive;
        $zipFileName = $nombrezip;
        if ($zip->open($storage_path.$nombrezip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        }else{
            return;
        }
        //Log::info("discharge");
        $x=Binarios::select('id','nombre')
        ->where('galeria_id',$gallery)
        ->where('selected',1)
        ->get();
        if(count($x)==0){
        $x=Binarios::select('id','nombre')
            ->where('galeria_id',$gallery)
            ->get();
        }
        //Log::info($storage_path.$nombrezip);
        foreach($x as $y){
            //Log::info($y->nombre);
            //Log: :info($y->id);
            //$nombrefoto="oh myphoto_".$this->idgaleria."_".$y->nombre;
            $nombrefoto=$y->nombre;
            //Log: :info($nombrefoto);return;
            $disks3 = Storage::disk('sftp');
            $filePaths3 = '/galerias/'.$userid."/";
            $bindata=$disks3->get($filePaths3.$y->id.".jpg");
            $filee=$storage_path.$nombrefoto;
            $zip->addFromString($nombrefoto, $bindata);
        }
        $zip->close();
        // fichero muy grande falla descarga php.ini memory_limit = 4096M
        //$this->enlacedescarga=$nombrezip;
        //Log::info("endzip ".$storage_path.$nombrezip);
        Galeria::where('id',$gallery)->update(['descargada'=>true,'fechadescarga'=>Carbon::now()]);
    }

    public function process($gallery,$md5)
    {
        // la galeria por el cliente
        //Log::info(route('downloads.process',[$this->idgaleria,md5('cucu'.$this->idgaleria)]));
        if(md5('cucu'.$gallery)!=$md5)
            return;
        $x=Galeria::select('nombre','id','user_id')->find($gallery);
        if(!$x){
            return;
        }
        //$storage_path = storage_path('app/public/tmpgallery')."/";
        //$storage_path = storage_path('app/livewire-tmp')."/";
        $storage_path = storage_path('app/public/tmpgallery')."/";
        //$nombrezip=str_replace(" ","_",$x->nombre.".zip");
        $nombrezip="galeria_".$x->id."_".md5('cucu'.$x->id).".zip";
        $userid=$x->user_id;
        $zip = new ZipArchive;
        $zipFileName = $nombrezip;
        if ($zip->open($storage_path.$nombrezip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        }else{
            return;
        }
        //Log::info("discharge");
        $x=Binarios::select('id','nombre')
        ->where('galeria_id',$gallery)
        ->where('selected',1)
        ->get();
        if(count($x)==0){
        $x=Binarios::select('id','nombre')
            ->where('galeria_id',$gallery)
            ->get();
        }
        //Log::info($storage_path.$nombrezip);
        foreach($x as $y){
            //Log::info($y->nombre);
            //Log: :info($y->id);
            //$nombrefoto="oh myphoto_".$this->idgaleria."_".$y->nombre;
            $nombrefoto=$y->nombre;
            //Log: :info($nombrefoto);return;
            $disks3 = Storage::disk('sftp');
            $filePaths3 = '/galerias/'.$userid."/";
            $bindata=$disks3->get($filePaths3.$y->id.".jpg");
            $filee=$storage_path.$nombrefoto;
            $zip->addFromString($nombrefoto, $bindata);
        }
        $zip->close();
        // fichero muy grande falla descarga php.ini memory_limit = 4096M
        //$this->enlacedescarga=$nombrezip;
        //Log::info("endzip ".$storage_path.$nombrezip);
        Galeria::where('id',$gallery)->update(['descargada'=>true,'fechadescarga'=>Carbon::now()]);
    }
}