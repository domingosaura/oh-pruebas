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
use Spatie\Image\Drivers\ImageDriver;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;
use File;
use DB;
use App\Http\Utils;
use Illuminate\Support\Facades\Auth;
//use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use ZipArchive;

class FileUploadController extends Controller
{
    //public $storage diskexterno = "sftp";
    public $userid;
    public $idgaleria;
    public $images;
    public function process($idgaleria,$userid,Request $request): string
    {
        // We don't know the name of the file input, so we need to grab
        // all the files from the request and grab the first file.
        /** @var UploadedFile[] $files */
        //Utils::vacialog();
        $this->idgaleria=$idgaleria;
        $this->userid=$userid;
        //Log::info($userid);
        //Log::info(Auth::id());
        if(Auth::id()!=$userid){
            return false;
        }

        $ficha=Galeria::where('user_id',$userid)->find($idgaleria);
        $contadorimagenes=Binarios::where('galeria_id',$idgaleria)->count();

        $this->images = $request->allFiles();
        //Log::info($files);

        $storage_pathtmp = storage_path('app/public/tmpgallery')."/";
        $livewiretemp = storage_path('app/livewire-tmp')."/";
        $stw = storage_path('app/watermark')."/";
        $filew=$stw.$this->userid.".png";
        $disks3 = Storage::disk('sftp');
        $filePaths3 = '/galerias/'.$this->userid."/";

        //$cantifotoactual=count($this->galeria);
        $cantiparasubir=count($this->images);
        $actual=0;
        $sustitucion=false;

        //Log::info($this->images);

        foreach($this->images as $imagen){
            //$this->dispatch('livewire-upload-progress',['progress' => 80,  'title' => 'ATENCIÓN']);
            $actual++;
            //$this->dispatch('postprocesadogalleryrefresh',['type' => 'error',  'message' => $this->textobackdrop,  'title' => 'ATENCIÓN']);
            //$mime=$imagen->getMimeType();
            //Log::info($imagen);
            $nombreoriginal=$imagen->getClientOriginalName();
            $nombretemporal=$imagen->getFileName();
            //Log::info($nombreoriginal);
            // mueve foto a dir photos con nombre temporal
            $rnd=str()->random();
            //$rnd=Utils::randomString(5);
            if(is_null($rnd))
                $rnd=Utils::randomString(5);
            $photoname=$this->userid."_".$rnd.".jpg";
            $path = $imagen->storeAs('/livewire-tmp',$nombreoriginal);
            //$path = $imagen->storeAs('uploads', 'public');
            //Log::info($path);
            //Log::info($nombretemporal);
            //return false;

            //$imagen->storeAs('photos',$photoname);
            //$filee=$storage_path.$photoname;
            //$fileemin=str_replace('.jpg','_min.jpg',$filee);
            //$fileemin=str_replace($storage_path,$storage_pathtmp,$filee);
            $filee=$livewiretemp.$nombreoriginal; // no saltamos storeas
            //$fileemin=str_replace($livewiretemp,$storage_pathtmp,$filee);
            $fileemin=str_replace($livewiretemp,$storage_pathtmp,$filee.".avif");
            //$fileemin=str_replace('.jpg','.avif',$fileemin);
            // cargamos en $image
            //$image = Spatie::load($filee);



            // putos espacios de color
            $imagever = new \imagick($filee);
            //$profile = $imagever->getImageProperties('icc:model', true);
            //$profile = $imagever->getImageProperties('*', true);
            $profile = $imagever->getImageProperties('icc:description', true);
            // grises, vacio
            // rgb, Adobe RGB (1998)
            // srgb, sRGB IEC61966-2.1
            $imagedriver="imagick";
            if(empty($profile)){
                // suponemos que es escala de grises, que es por lo que puse por defecto gd en lugar de imagick
                $imagedriver="gd";
            }
            //Log::info($imagedriver);
            //

            $driver = Spatie::useImageDriver($imagedriver); // 'imagick me da problemas con algunas fotos en escala de grises'
            
            $image = $driver->loadFile($filee);
            $width=$image->getWidth();
            $height=$image->getHeight();
            //1920 - 1080   widt - height
            //800  - x      800  - x 

            //Log::info($width>$height?"horizontal":"vertical");
            //Log::info("width: ".$width);
            //Log::info("height: ".$height);
            if($width>$height){
                //horizontal segun parece se ve peor
                //Log::info("horizontal");
                $image->Fit(Fit::Contain,2048,   intval((2048*$height)/$width)  );
            }
            if($width<=$height){
                //vertical
                //Log::info("vertical");
                $image->Fit(Fit::Contain,1024,   intval((1024*$height)/$width)  );
            }

            //$image = Image ::make($filee);
            //$image->resize(800, null);
            if($ficha->marcaagua==1){
                //Log::info("marcaagua");
                $image->watermark($filew,
                AlignPosition::MiddleMiddle,
                //AlignPosition::MiddleMiddle,
                width:90,widthUnit:Unit::Percent,
                height:90,heightUnit:Unit::Percent,
                fit: Fit::Contain,
                alpha: 50
                );

                //$image->watermark($filew,
                //    paddingX: 10,
                //    paddingY: 10,
                //    paddingUnit: Unit::Percent
                //); // 10% padding around the watermark
            }


            $image->save($fileemin);
            
            //Log::info(($fileemin));
            
            $base64min=base64_encode(File::get($fileemin));

            $x=DB::select("select position from binarios where galeria_id=".$this->idgaleria." order by position desc limit 1");
            $poss=1;
            if($x){
                $poss=$x[0]->position+1;
            }
            $orisize=File::size($filee);

            // si existe la fulmino
            $x=Binarios::
            select('id','position')->where('galeria_id',$this->idgaleria)->where('nombre',$nombreoriginal)->get()->toArray();
            if($x){
                $iddelete=$x[0]['id'];
                $poss=$x[0]['position'];
                Binarios::where('id',$iddelete)->delete();
                $disks3->delete($filePaths3.$iddelete.".jpg");
                $sustitucion=true;
            }
            //

            $idimage=Binarios::insertGetId([
                'galeria_id'=>$this->idgaleria,
                'nombre'=>$nombreoriginal,
                'anotaciones'=>'',
                'binario'=>$base64min,
                'selected'=>false,
                'originalsize'=>$orisize,
                'position'=>$poss,
                'created_at'=>Carbon::now()->toDateTimeString(),
            ]);

            // upload a aws???!!!???
            $disks3->put($filePaths3.$idimage.".jpg", file_get_contents($filee));
            if(!($ficha->binario)){
                $fileemin1=str_replace('.jpg','_min1.avif',$filee);
                $filee1=str_replace('.jpg','.avif',$filee);
                $image = Spatie::load($filee);
                $width=$image->getWidth();
                $height=$image->getHeight();
                //1920 - 1080   widt - height
                //800  - x      800  - x
                //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
                $image->Fit(Fit::Crop,425,239)->save($fileemin1);
        
                $image = Spatie::load($filee);
                $width=$image->getWidth();
                $height=$image->getHeight();
                //1920 - 1080   widt - height
                //800  - x      800  - x
                //$image->Fit(Fit: :Contain,1080, (1080*$height)/$width );
                $image->Fit(Fit::Crop,1920,1080)->save($filee1);
                $base64=base64_encode(File::get($filee1));
                $base64min=base64_encode(File::get($fileemin1));
                $ficha->binario=$base64;
                $ficha->binariomin=$base64min;
                File::delete($fileemin1);
                File::delete($filee1);
                //$this->ficha->nohay descarga=$this->nohay descarga;
                //$this->validate();
                $ficha->update();
            }

            File::delete($filee);
            //File::move($filee,$storage_path.'tos3_'.$idimage.".jpg");
            //
            $filee2=$storage_pathtmp.$this->idgaleria."-".$idimage.".jpg";
            //$bindata=base64_decode($base64min);
            //File::put($filee2,$bindata);
            File::move($fileemin,$filee2);

            $this->galeria[]=[
                'id'=>$idimage,
                'nombre'=>$nombreoriginal,
                'galeria_id'=>$this->idgaleria,
                'position'=>$poss,
                'binario'=>'',
                'anotaciones'=>'',
                'selected'=>false,
                'file'=>$this->idgaleria."-".$idimage.".jpg"
            ];
            //log: :info("end storage");
        }

        if($contadorimagenes==0 && 1==2){
            // no habia ninguna foto en la galeria
            // no me vale por que lo sube a trozos
            $x=Binarios::
                select('id','nombre','position')
                ->where('galeria_id',$this->idgaleria)
                ->orderby('nombre',"asc")
                ->get()
                ->toArray();
            $iss=0;
            usort($x, function($a, $b) {
                return strnatcasecmp($a['nombre'], $b['nombre']);
            });
            foreach($x as $key=>$y){
                $iss++;
                Binarios::where('id',$x[$key]['id'])->update(['position'=>$iss]);
            }
        }


        if (empty($this->images)) {
            abort(422, 'No files were uploaded.');
        }
 
        //Log::info("fin bloque");
        return "";

        return $file->store(
            path: 'tmp/'.now()->timestamp.'-'.Str::random(20)
        );
    }
}