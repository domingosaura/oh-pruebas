<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\DownloadGallery;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Cliente\Create as ClienteCreate;
use App\Http\Livewire\Cliente\Edit as ClienteEdit;
use App\Http\Livewire\Cliente\Index as ClienteIndex;
use App\Http\Livewire\Cliente\Rellenarporcliente as Rellenarporcliente;
use App\Http\Livewire\Proveedor\Create as ProveedorCreate;
use App\Http\Livewire\Proveedor\Edit as ProveedorEdit;
use App\Http\Livewire\Proveedor\Index as ProveedorIndex;
//use App\Http\Livewire\Calendarios\Create as CalendariosCreate;
use App\Http\Livewire\Calendarios\Edit as CalendariosEdit;
use App\Http\Livewire\Calendarios\Index as CalendariosIndex;
//use App\Http\Livewire\Calendarios\Calendario as Calendario;
use App\Http\Livewire\Calendarios\Calendariocliente as Calendariocliente;
//use App\Http\Livewire\Calendarios\Calendarioclientecancelar as Calendarioclientecancelar;
use App\Http\Livewire\Impuesto\Create as ImpuestoCreate;
use App\Http\Livewire\Impuesto\Edit as ImpuestoEdit;
use App\Http\Livewire\Impuesto\Index as ImpuestoIndex;
//use App\Http\Livewire\Gestion\Create as GestionCreate;
use App\Http\Livewire\Gestion\Edit as GestionEdit;
use App\Http\Livewire\Gestion\Index as GestionIndex;
use App\Http\Livewire\Gestion\Listados as GestionListados;
use App\Http\Livewire\Plantillacontrato\Edit as PlantillacontratoEdit;
use App\Http\Livewire\Plantillacontrato\Index as PlantillacontratoIndex;
use App\Http\Livewire\Plantillagaleria\Edit as PlantillagaleriaEdit;
use App\Http\Livewire\Plantillagaleria\Index as PlantillagaleriaIndex;
use App\Http\Livewire\Productos\Edit as ProductosEdit;
use App\Http\Livewire\Productos\Edit2 as ProductosEdit2;
use App\Http\Livewire\Productos\Index as ProductosIndex;
use App\Http\Livewire\Galeria\Edit as GaleriaEdit;
//use App\Http\Livewire\Galeria\EditDropzone as GaleriaEditDropzone;
use App\Http\Livewire\Galeria\Index as GaleriaIndex;
use App\Http\Livewire\Sesiones\Edit as SesionesEdit;
use App\Http\Livewire\Sesiones\Index as SesionesIndex;
use App\Http\Livewire\Packs\Edit as PacksEdit;
use App\Http\Livewire\Packs\Index as PacksIndex;
use App\Http\Livewire\Clientecontrato\Edit as ClientecontratoEdit;
use App\Http\Livewire\Clientecontrato\Index as ClientecontratoIndex;
use App\Http\Livewire\Galeria\Galeriacliente;
use App\Http\Livewire\Clientecontrato\Contratocliente;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Rutasnodocumentadas;
use App\Http\Livewire\Cookies;
use App\Http\Livewire\Condiciones;
use App\Http\Livewire\Importaciones;
use App\Http\Livewire\Verificaremail;
use App\Http\Livewire\Micuenta;
use App\Http\Livewire\Panel1;
use App\Http\Livewire\Auth\ForgetPassword;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Register2;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Dashboard\Index;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('sign-in');
});

Route::get('cookies', Cookies::class)->name('cookies');
Route::get('condiciones', Condiciones::class)->name('condiciones');
Route::get('sign-up', Register::class)->middleware('guest')->name('register');
Route::get('sign-up2', Register2::class)->middleware('guest')->name('register2');
Route::get('sign-in', Login::class)->middleware('guest')->name('login');
Route::get('forget-password', ForgetPassword::class)->middleware('guest')->name('forget-password');
Route::get('reset-password/{id}', ResetPassword::class)->middleware('guest')->name('reset-password');
Route::get('dashboard/analytics', Index::class)->middleware('auth')->name('analytics');
Route::get('galeriacliente/{idgal}/{md5}/{error?}', Galeriacliente::class)->where('id', '[0-9]+')->name('galeriacliente');
Route::post('galeriacliente/{idgal}/{md5}/{error?}', Galeriacliente::class)->where('id', '[0-9]+')->name('galeriacliente');
Route::get('reservas/{idempresa}/{idcalendario}/{md5recibido}/{error?}', Calendariocliente::class)->where('idempresa', '[0-9]+')->where('idcalendario', '[0-9]+')->name('reservas');
Route::post('reservas/{idempresa}/{idcalendario}/{md5recibido}/{error?}', Calendariocliente::class)->where('idempresa', '[0-9]+')->where('idcalendario', '[0-9]+')->name('reservas');
//Route::get('cancelarreserva/{idempresa}/{idcliente}/{idregistro}/{md5}', Calendarioclientecancelar::class)->where('idempresa', '[0-9]+')->where('idregistro', '[0-9]+')->where('idcliente', '[0-9]+')->name('cancelarreserva');
Route::get('contratocliente/{idcontrato}/{md5}/{error?}', Contratocliente::class)->where('id', '[0-9]+')->name('contratocliente');
Route::get('datosdecliente/{user}/{cliente}/{md5}', Rellenarporcliente::class)->where('id', '[0-9]+')->name('rellenarporcliente');
Route::get('/downloadgallery/{gallery}/{md5}',[DownloadGallery::class, 'process'])->name('downloads.process');
Route::get('/downloadgallery2/{gallery}/{md5}/{_12}',[DownloadGallery::class, 'processprof'])->name('downloads.process2');

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('logout', function () {
        auth()->logout();
        return redirect('sign-in');
    })->name('logout');
    
	Route::post('/uploadgallery/process/{idgaleria}/{userid}',[FileUploadController::class, 'process'])->where('idgaleria', '[0-9]+')->where('userid', '[0-9]+')->name('uploads.process');
	Route::get('verificaremail', Verificaremail::class)->name('verificaremail');
	Route::get('micuenta', Micuenta::class)->name('micuenta');
	Route::get('panel1', Panel1::class)->name('panel1');
	Route::get('dashboard', Dashboard::class)->name('dashboard');
	Route::get('importaciones', Importaciones::class)->name('importaciones');
    Route::get('cliente', ClienteIndex::class)->name('cliente-management');
    Route::get('cliente/{id}', ClienteEdit::class)->where('id', '[0-9]+')->name('edit-cliente');
    Route::get('nuevocliente', ClienteCreate::class)->name('add-cliente');
    Route::get('proveedor', ProveedorIndex::class)->name('proveedor-management');
    Route::get('proveedor/{id}', ProveedorEdit::class)->where('id', '[0-9]+')->name('edit-proveedor');
    Route::get('nuevoproveedor', ProveedorCreate::class)->name('add-proveedor');
    Route::get('calendarios', CalendariosIndex::class)->name('calendarios-management');
    Route::get('calendarios/{id}', CalendariosEdit::class)->where('id', '[0-9]+')->name('edit-calendario');
    //Route::get('nuevocalendario', CalendariosCreate::class)->name('add-calendario');
    //Route::get('calendario/{id}', Calendario::class)->where('id', '[0-9]+')->name('calendario');
    
    Route::get('monetario/impuestos', ImpuestoIndex::class)->name('impuesto-management');
    Route::get('monetario/impuesto/{id}', ImpuestoEdit::class)->where('id', '[0-9]+')->name('edit-impuesto');
    Route::get('monetario/nuevoimpuesto', ImpuestoCreate::class)->name('add-impuesto');
    Route::get('monetario/ingresos', GestionIndex::class)->name('ingreso-management')->defaults('tipo', 1);
    Route::get('monetario/ingreso/{id}', GestionEdit::class)->where('id', '[0-9]+')->name('edit-ingreso')->defaults('tipo', 1);
    Route::get('monetario/gastos', GestionIndex::class)->name('gasto-management')->defaults('tipo', 2);
    Route::get('monetario/gasto/{id}', GestionEdit::class)->where('id', '[0-9]+')->name('edit-gasto')->defaults('tipo', 2);
    Route::get('monetario/listados', GestionListados::class)->name('listados');
    
    Route::get('plantillas/contratos', PlantillacontratoIndex::class)->name('plantillacontrato-management')->defaults('tipo', 2);
    Route::get('plantillas/contrato/{id}', PlantillacontratoEdit::class)->where('id', '[0-9]+')->name('edit-plantillacontrato')->defaults('tipo', 2);
    Route::get('contratos', ClientecontratoIndex::class)->name('clientecontrato-management');
    Route::get('clientecontrato/{id}', ClientecontratoEdit::class)->where('id', '[0-9]+')->name('edit-clientecontrato');
    Route::get('plantillas/galerias', PlantillagaleriaIndex::class)->name('plantillagaleria-management')->defaults('tipo', 2);
    Route::get('plantillas/galeria/{id}', PlantillagaleriaEdit::class)->where('id', '[0-9]+')->name('edit-plantillagaleria')->defaults('tipo', 2);

    Route::get('plantillas/productos', ProductosIndex::class)->name('productos-management')->defaults('tipo', 2);
    Route::get('plantillas/producto/{id}', ProductosEdit::class)->where('id', '[0-9]+')->name('edit-productos')->defaults('tipo', 2);
    Route::get('plantillas/productoX/{id}', ProductosEdit2::class)->where('id', '[0-9]+')->name('edit-productos2')->defaults('tipo', 2);

    Route::get('misgalerias', GaleriaIndex::class)->name('galeria-management');
    //Route::get('migaleria/{id}', GaleriaEdit::class)->where('id', '[0-9]+')->name('edit-galeria');
    Route::get('migaleria/{id}', GaleriaEdit::class)->where('id', '[0-9]+')->name('edit-galeria');
    //Route::get('migaleriaDropzone/{id}', GaleriaEditDropzone::class)->where('id', '[0-9]+')->name('edit-galeriaDropzone');

    Route::get('servicios', SesionesIndex::class)->name('sesiones-management');
    Route::get('servicios/{id}', SesionesEdit::class)->where('id', '[0-9]+')->name('edit-sesiones');
    Route::get('packs', PacksIndex::class)->name('packs-management');
    Route::get('packs/{id}', PacksEdit::class)->where('id', '[0-9]+')->name('edit-packs');

    Route::get('/billing', function (Request $request) {
        return $request->user()->redirectToBillingPortal(route('micuenta',['pos'=>7]));
    })->name('billing');
    
    


    // esta siempre es la ultima ruta
    Route::fallback(function () {
        // retorno para error 404 todas las paginas que intentas y no existen
        //Rutasnodocumentadas::mount;
        Rutasnodocumentadas::grabarevento();
        //$x->not();
        return redirect('dashboard');
    });



});
