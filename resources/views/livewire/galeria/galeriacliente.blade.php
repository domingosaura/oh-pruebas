<div class="{{!$start?'container-fluid px-2 px-md-2 bg-gray-200':''}} mb-6">
    @if($start)
    <div class="page-header" style="
        @if(strlen($imagen)>0)
            background-image: url('{{ $imagen }}');
        @endif
        background-repeat: no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-attachment: fixed;
        height:100vh;
        width:100%;">
        <div class="col-12 text-center">
            <h1 class="text-center blanco opacity-9">&nbsp;</h1>
            <h1 class="text-center blanco opacity-9">&nbsp;</h1>
            <h1 class="text-center blanco opacity-9">&nbsp;</h1>
            <h1 class="text-center blanco opacity-9">&nbsp;</h1>
            <h1 class="text-center blanco opacity-9">&nbsp;</h1>
            <h1 class="text-center {{strlen($imagen)>0?'blanco':'negro'}} opacity-9">{{$ficha->nombre}}</h1>
        @if($ficha->clavecliente)
        <div class="col-12 text-center">
            <h6 class="text-center blanco">Clave de acceso:</h6>
            <input type="text" wire:model="claveacceso" class="opacity-7"/>
        </div>
        @endif
        <button wire:click="vergaleria" type="button" class="btn text-lg botonoh_gal opacity-7">
            Ir a la galería
        </button>
        </div>
    </div>
    <div class="mb-7">
        &nbsp;
    </div>
    @endif


    @if(!$start)
    @if(strlen($imagen)>0)
    <div class="page-header min-height-300 border-radius-xl mt-3" style="
                background-image: url('{{ $imagen }}');
            background-position: 0;bpos:top;">
        <span class="xxmask xxbg-gradient-info xxopacity-3"></span>
        <div class="col-12 text-center">
            <h1 class="text-center blanco opacity-9">{{$ficha->nombre}}</h1>
        </div>
    </div>
    @endif
    @if(strlen($imagen)==0)
    <div class="page-header min-height-250 border-radius-xl mt-3" style="
            background-color:black;">
        <div class="col-12 text-center">
            <h1 class="text-center titulogaleria">{{$ficha->nombre}}</h1>
        </div>
    </div>
    @endif
    @endif

    @if(!$start)
    <div class="row" style="position: fixed;bottom: 0;z-index:9999;background-color:black;padding-top:5px;">
    <div class="col-12" style="position: fixed;bottom: 0;z-index:9999;background-color:black;padding-top:5px;">
        <div class="row">
            <div class="col-2 copyright text-center text-sm">
                © <script>
                    document.write(new Date().getFullYear())
                </script>&nbsp;OhMyPhoto
            </div>
            <div style="text-align:center;" class="text-center col-8">
                <h6 style="color:white !important;">{{$seleccionadas}} de {{count($galeria)}} fotografías seleccionadas.
                    Importe: {{$precio}}&euro;</h6>
            </div>

            <div class="col-2 copyright text-center text-sm">
                <a href="mailto:{{env('MAIL_FROM_ADDRESS', 'info@ohmyphoto.es')}}">contacto</a>
                &nbsp;&nbsp;<a href="{{ route('cookies')}}">política de cookies</a>
            </div>
        </div>
    </div>
    </div>
    @endif

    <div class="card card-body mx-2 mx-md-3 mt-n6" style="{{$start?'display:none':'display:block'}}">
        <div class="row gx-4">
            <div class="col-auto">
                @if($logo)
                <div class="avatar avatar-xl position-relative">
                    <img id="idfot1" src="{{$logo}}" alt=""
                        class="img-fluid shadow border-radius-xl" />
                </div>
                @endif
            </div>


            @if ($error=="errorpago")
            <div class="col-12" role="alert">
                <div class="alert alert-danger alert-dismissible text-white mx-4" role="alert">
                    <span class="text-sm">Se produjo un error al pagar la galería</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            @if ($error=="pagado")
            <div class="col-12" role="alert">
                <div class="alert alert-success-oh alert-dismissible text-white mx-4" role="alert">
                    <span class="text-sm">Se ha completado el pago de la galería</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif

            <div class="col-auto my-auto">
                <div class="h-100">
                    <h6 class="mb-1">
                        {{$empresa}}
                    </h6>
                    <!--<p class="mb-0 font-weight-normal text-sm">
                        CEO / Co-Founder
                    </p>-->
                </div>
            </div>
            <div class="col-12 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0" nowireignore>
                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                        <li></li>
                        <li class="nav-item" wire:click="vseccion(1)">
                            <a class="nav-link mb-0 px-0 py-1 {{$seccion==1?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                role="tab" aria-selected="false">
                                <i class="material-icons text-lg position-relative">photo_library</i>
                                <span class="ms-1">Fotografías</span>
                            </a>
                        </li>
                        <li class="nav-item" wire:click="vseccion(2)">
                            <a class="nav-link mb-0 px-0 py-1 {{$seccion==2?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                aria-selected="false">
                                <i class="material-icons text-lg position-relative">list</i>
                                <span class="ms-1">Complementos</span>
                            </a>
                        </li>
                        <li class="nav-item" wire:click="vseccion(3)">
                            <a class="nav-link mb-0 px-0 py-1 {{$seccion==3?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                aria-selected="false">
                                <i class="material-icons text-lg position-relative">payments</i>
                                <span class="ms-1">Confirmar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="{{$seccion==1?'visible':'oculto'}}">

            @include('livewire.galeria.galeriacliente_selectorfotos')

            @if(strlen($ficha->anotaciones)>15)
            <link href="{{ asset('assets') }}/css/quill.snow.css" rel="stylesheet">
            <div class="col-12 col-md-12 text-center mb-4 ql-editor quillnomargen">
                <div class="border border-light border-1 border-radius-md py-3">
                    {!!$ficha->anotaciones!!}
                </div>
            </div>
            @endif

            @if(($ficha->pagado && $ficha->permitirdescarga==1)||$ficha->permitirdescarga==3)
                <div class="col-12 text-center mt-2">
                    @if(!$descargadisponible)
                    <button wire:click="descargarcliente" type="button" onclick="postprocesado_init_descarga();"
                    class="btn bg-gradient-primary">Descargar la galería</button>
                    @else
                    <a href="{{$rutadescarga}}" target="_blank">
                    <button type="button"
                    class="btn fondonaranjito blanco">Descarga lista, pulse para descargar</button>
                    </a>
                    @endif
                </div>
                @if($peticiondescarga)
                <div wire:poll.5s="descargalista"></div>
                @endif
            @else
            @endif
            @if($ficha['preciogaleriacompleta']>0 && !$ficha->pagado && !$ficha->pagadomanual&&!$seleccionconfirmada)
                <div class="col-12 text-center">
                    @if($seleccionadas<count($galeria))
                    <button wire:click="seleccionartodo" type="button" class="btn bg-gradient-primary">
                        Seleccionar galería completa
                    </button>
                    @endif
                    @if($seleccionadas>0)
                    <button wire:click="seleccionartodo(false)" type="button" class="btn bg-gradient-primary">
                        Desmarcar todo
                    </button>
                    @endif
                </div>
            @endif

            <div class="row">
                <!--fotografias-->
                @if(!$start)



                    <div class="col-12 xxcol-md-4 d-block d-lg-none">
                        @foreach ($galeria as $keyy=>$tag)
                        @if($seleccionamostrar==1 || ($seleccionamostrar==2 && $tag['selected']==true)||($seleccionamostrar==3 && $tag['selected']==false))
                            @include('livewire.galeria.galeriacliente_objimage')
                        @endif
                        @endforeach
                    </div>




                    <div class="col-12 col-md-4 d-none d-lg-block">
                        @php($i=0)
                        @foreach ($galeria as $keyy=>$tag)
                        @if($seleccionamostrar==1 || ($seleccionamostrar==2 && $tag['selected']==true)||($seleccionamostrar==3 && $tag['selected']==false))
                            @php($i++)
                            @if(($i)%3==1 )
                                @include('livewire.galeria.galeriacliente_objimage')
                            @endif
                        @endif
                        @endforeach
                    </div>
                    <div class="col-12 col-md-4 d-none d-lg-block">
                        @php($i=0)
                        @foreach ($galeria as $keyy=>$tag)
                        @if($seleccionamostrar==1 || ($seleccionamostrar==2 && $tag['selected']==true)||($seleccionamostrar==3 && $tag['selected']==false))
                            @php($i++)
                            @if(($i)%3==2 )
                                @include('livewire.galeria.galeriacliente_objimage')
                            @endif
                        @endif
                        @endforeach
                    </div>
                    <div class="col-12 col-md-4 d-none d-lg-block">
                        @php($i=0)
                        @foreach ($galeria as $keyy=>$tag)
                        @if($seleccionamostrar==1 || ($seleccionamostrar==2 && $tag['selected']==true)||($seleccionamostrar==3 && $tag['selected']==false))
                            @php($i++)
                            @if(($i)%3==0 )
                                @include('livewire.galeria.galeriacliente_objimage')
                            @endif
                        @endif
                        @endforeach
                    </div>
                @endif
            </div>




            @if($seleccionamostrar==1||($seleccionamostrar==2&&$seleccionadas>0)||($seleccionamostrar==3&&count($galeria)-$seleccionadas>0))
                @include('livewire.galeria.galeriacliente_selectorfotos')
            @endif
            
            
            
            
            




        </div>
        <div class="{{$seccion==2?'visible':'oculto'}}">
            <div class="row mt-4">
                <!--complementos-->
                @if(1==2)
                <div class="col-6">
                    <h6>Contenido incluido en la galería</h6>
                    @if($ficha->incluido1)
                    <h5>- {{$ficha->incluido1}}</h5>
                    @endif
                    @if($ficha->incluido2)
                    <h5>- {{$ficha->incluido2}}</h5>
                    @endif
                    @if($ficha->incluido3)
                    <h5>- {{$ficha->incluido3}}</h5>
                    @endif
                    @if($ficha->incluido4)
                    <h5>- {{$ficha->incluido4}}</h5>
                    @endif
                    @if($ficha->incluido5)
                    <h5>- {{$ficha->incluido5}}</h5>
                    @endif
                    @if($ficha->incluido6)
                    <h5>- {{$ficha->incluido6}}</h5>
                    @endif
                    @if($ficha->incluido7)
                    <h5>- {{$ficha->incluido7}}</h5>
                    @endif
                    @if($ficha->incluido8)
                    <h5>- {{$ficha->incluido8}}</h5>
                    @endif
                    @if($ficha->incluido9)
                    <h5>- {{$ficha->incluido9}}</h5>
                    @endif
                    @if($ficha->incluido10)
                    <h5>- {{$ficha->incluido10}}</h5>
                    @endif
                </div>
                <div class="col-6">
                    <h6>Contenido opcional en la galería</h6>
                    @if($ficha->opcional1)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc1" id="selo1" wire:change="adicional('selopc1')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo1">
                            <h5>&nbsp;- {{$ficha->opcional1}} - {{$ficha->precioopc1}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional2)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc2" id="selo2" wire:change="adicional('selopc2')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo2">
                            <h5>&nbsp;- {{$ficha->opcional2}} - {{$ficha->precioopc2}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional3)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc3" id="selo3" wire:change="adicional('selopc3')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo3">
                            <h5>&nbsp;- {{$ficha->opcional3}} - {{$ficha->precioopc3}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional4)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc4" id="selo4" wire:change="adicional('selopc4')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo4">
                            <h5>&nbsp;- {{$ficha->opcional4}} - {{$ficha->precioopc4}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional5)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc5" id="selo5" wire:change="adicional('selopc5')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo5">
                            <h5>&nbsp;- {{$ficha->opcional5}} - {{$ficha->precioopc5}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional6)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc6" id="selo6" wire:change="adicional('selopc6')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo6">
                            <h5>&nbsp;- {{$ficha->opcional6}} - {{$ficha->precioopc6}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional7)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc7" id="selo7" wire:change="adicional('selopc7')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo7">
                            <h5>&nbsp;- {{$ficha->opcional7}} - {{$ficha->precioopc7}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional8)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc8" id="selo8" wire:change="adicional('selopc8')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo8">
                            <h5>&nbsp;- {{$ficha->opcional8}} - {{$ficha->precioopc8}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional9)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc9" id="selo9" wire:change="adicional('selopc9')"
                            class="form-check-input">
                        <label class="form-check-label" for="selo9">
                            <h5>&nbsp;- {{$ficha->opcional9}} - {{$ficha->precioopc9}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                    @if($ficha->opcional10)
                    <div class="form-check">
                        <input type="checkbox" wire:model="ficha.selopc10" id="selo10"
                            wire:change="adicional('selopc10')" class="form-check-input">
                        <label class="form-check-label" for="selo10">
                            <h5>&nbsp;- {{$ficha->opcional10}} - {{$ficha->precioopc10}} &euro;</h5>
                        </label>
                    </div>
                    @endif
                </div>
                @endif

                @if($productos)


                <?php
                $incluidos=false;
                $opcionales=false;
                foreach($productos as $key=>$tag){
                    if($tag['incluido'])
                        $incluidos=true;
                    if(!$tag['incluido'])
                        $opcionales=true;
                }
                ?>


                <div class="col-12">
                    @if($incluidos)
                    <h6>Productos incluidos sin coste adicional</h6>
                    @endif
                    @foreach($productos as $key=>$tag)
                    @if($tag['incluido'])
                    @include('livewire.galeria.galeria_producto',['origen'=>'galeriacliente'])
                    @endif
                    @endforeach
                </div>
                <div class="col-12">
                    @if($opcionales)
                    <h6>Productos adicionales</h6>
                    @endif
                    @foreach($productos as $key=>$tag)
                    @if($tag['incluido']==false)
                    @include('livewire.galeria.galeria_producto',['origen'=>'galeriacliente'])
                    @endif
                    @endforeach
                </div>
                @endif

            </div>
        </div>
        <div class="{{$seccion==3?'visible':'oculto'}}">
            <div class="row mt-4">
                <!--Confirmar-->
                <div class="col-4 col-md-4 text-center mb-4">
                    <div class="border border-light border-1 border-radius-md py-3">
                        <h6 class="text-primary text-gradient mb-0">Precio actual de la galería</h6>
                        <h4 class="font-weight-bolder mb-0">
                            <span id="state1" countTo="">{{$precio}}</span>
                            <span class="small"> &euro;</span>
                        </h4>
                        @if(!$pagado)
                            @if(!$procesable && !$pagado && !$pagadomanual)
                                <h6 class="text-primary text-gradient mb-0">No se han seleccionado las fotografías necesarias
                                    para confirmar la galería</h6>
                            @endif
                            @if($procesable)
                                <h6 class="text-primary text-gradient mb-0">La galería se puede confirmar y efectuar el pago
                                </h6>
                            @endif
                        @endif
                        @if($pagado)
                            <h6 class="text-primary text-gradient mb-0">La galería ya se ha marcado como PAGADA</h6>
                        @endif
                    </div>
                </div>

                <div class="col-8 col-md-8 text-center mb-4">
                    <div class="border border-light border-1 border-radius-md py-3">
                        <div class="row">
                            @foreach($desgloseado as $des)
                            <div class="col-8 col-md-6 text-start">
                                {{$des['texto']}}
                            </div>
                            <div class="col-3 col-md-3 text-end">
                                @if($des['importe']!=0)
                                {{$des['importe']}}&euro;
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 text-center mb-4 border border-light border-1 border-radius-md py-3">
                    @if(!$seleccionconfirmada)
                    @if($precio>0)
                    <button wire:click="confirmarseleccion" class="btn btn-dark mt-3 text-center"
                    onclick="if(confirm('Una vez confirmada, no podrá modificar su selección de fotografías actual, ¿continuar?.')){}else{event.stopImmediatePropagation();}">
                        Confirmar mi selección de fotografías</button>
                    @endif
                    @if($precio==0)
                    <button wire:click="confirmarseleccionypago0" class="btn btn-dark mt-3 text-center">Confirmar mi selección
                        de fotografías y finalizar la edición</button>
                    @endif
                    @else
                    <div class="border border-light border-1 border-radius-md py-3">
                        <h6 class="text-primary text-gradient mb-0">Se ha confirmado la selección de fotografías, ya no se
                            pueden hacer cambios</h6>
                    </div>
                    @endif
                </div>

                @if($procesable&&$precio>0&&!$pagado&&!$pagadomanual)
                <div class="col-12 col-md-12 text-start mb-4 border border-light border-1 border-radius-md py-3">
                    <h6 class=" mb-0">Procedimiento de pago</h6>

                    <div class="row">
                        <div class="col-5 col-md-5 mt-3">
                            <div class="form-group">
                                <label>Seleccione la forma de pago:</label>
                                @if($formaspago->efectivo && $ficha['pago1activo'])
                                <div class="form-check">
                                    <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                        value='1' id="f1" wire:change="fpago()" {{$pagado?'disabled':''}}>
                                    <label class="form-check-label" for="f1">
                                        Efectivo
                                    </label>
                                </div>
                                @endif
                                @if($formaspago->transferencia && $ficha['pago2activo'])
                                <div class="form-check">
                                    <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                        value='2' id="f2" wire:change="fpago()" {{$pagado?'disabled':''}}>
                                    <label class="form-check-label" for="f2">
                                        Transferencia bancaria a cuenta {{$iban}}
                                    </label>
                                </div>
                                @endif
                                @if($formaspago->redsys && $ficha['pago3activo'])
                                <div class="form-check">
                                    <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                        value='3' id="f3" wire:change="fpago()" {{$pagado?'disabled':''}}>
                                    <label class="form-check-label" for="f3">
                                        Redsys (tarjeta de crédito)
                                    </label>
                                </div>
                                @endif
                                @if($formaspago->paypal && $ficha['pago4activo'])
                                <div class="form-check">
                                    <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                        value='4' id="f4" wire:change="fpago()" {{$pagado?'disabled':''}}>
                                    <label class="form-check-label" for="f4">
                                        Paypal {{$formaspago->ppalprc>0?'(incremento '.$formaspago->ppalprc.'%)':''}}
                                    </label>
                                </div>
                                @endif
                                @if($formaspago->stripe && $ficha['pago5activo'])
                                <div class="form-check">
                                    <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                        value='5' id="f5" wire:change="fpago()" {{$pagado?'disabled':''}}>
                                    <label class="form-check-label" for="f5">
                                        Stripe {{$formaspago->stripeprc>0?'(incremento
                                        '.$formaspago->stripeprc.'%)':''}}
                                    </label>
                                </div>
                                @endif
                                @if($formaspago->bizum && $ficha['pago6activo'])
                                <div class="form-check">
                                    <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                        value='6' id="f6" wire:change="fpago()" {{$pagado?'disabled':''}}>
                                    <label class="form-check-label" for="f6">
                                        Bizum (teléfono para pago: {{$formaspago->bizumtelefono}})
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-5 col-md-5 mt-3">
                            @if(($ficha->tipodepago==1||$ficha->tipodepago==2||$ficha->tipodepago==6)&&!$pagadomanual)
                            <button wire:click="finalizarpagomanual" class="btn btn-dark mt-3 text-center">Finalizar y
                                pagar</button>
                            <h5>Por favor, confirme el pago con su fotógrafo. Al tratarse de un pago no automatizado queda pendiente hasta confirmar por el fotógrafo.</h5>
                            @endif

                            @if($ficha->tipodepago==5 && !$pagado)
                            <script src="https://js.stripe.com/v3/"></script>
                            <button wire:click="finalizarpagostripe" class="btn btn-dark mt-3 text-center">Finalizar y
                                realizar el pago con Stripe</button>
                            @endif

                            @if($ficha->tipodepago==4 && !$pagado)
                            <button wire:click="finalizarpagopaypal" class="btn btn-dark mt-3 text-center">Finalizar y
                                realizar el pago con Paypal</button>
                            @endif

                            @if($ficha->tipodepago==3 && !$pagado)
                            <form name='formulariopago' action='{{ $redsys['rutaredsys'] }}' method='post'>
                                <input type='hidden' name='Ds_SignatureVersion' value='HMAC_SHA256_V1' />
                                <input type='hidden' name='Ds_MerchantParameters' value='{{ $redsys['tpvredsysMerchantParameters256'] }}' />
                                <input type='hidden' name='Ds_Signature' value='{{ $redsys['tpvredsysSignature256'] }}' />
                                <button wire:click="grabaimportepago" type="button" class="btn btn-dark mt-3 text-center" aria-label="Left Align"
                                    onclick='if(document.getElementById("acepto").checked==false){alert("Acepte antes los términos de servicio");return;}javascript:formulariopago.submit()'>
                                    Finalizar y realizar el pago con tarjeta de crédito
                                </button>
                            </form>
                            <div class="form-check">
                                <label class="form-check-label" for="acepto">
                                    Acepto los términos de servicio y la política de privacidad
                                </label>
                                <input wire:click="grabaimportepago" class="form-check-input" type="checkbox" value="" id="acepto">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-12 col-md-12 text-center mb-4">
                    <div class="border border-light border-1 border-radius-md py-3">
                        <h6 class="mb-0">Actualmente ha seleccionado {{$seleccionadas}}
                            fotografías</h6>
                        @if($ficha['numfotos']<$ficha['maxfotos']) 
                            <h6 class="mb-0">Seleccione un mínimo de {{$ficha['numfotos']}} y un
                                máximo de {{$ficha['maxfotos']}} fotografías</h6>
                        @endif
                    </div>
                </div>
    
                @if($ficha['numfotos']==$ficha['maxfotos'])
                <div class="col-12 col-md-12 text-center mb-4">
                    <div class="border border-light border-1 border-radius-md py-3">
                        <h6 class="text-primary text-gradient mb-0">Tiene que seleccionar {{$ficha['numfotos']}}</h6>
                    </div>
                </div>
                @endif
                <div class="col-12 col-md-12 text-center mb-4">
                    <div class="border border-light border-1 border-radius-md py-3">
                        <h6 class="mb-0">El precio base de la galería es de
                            {{$ficha['preciogaleria']}} &euro; por {{$ficha['numfotos']}}
                            fotografías{!!$ficha['entregado']>0?' (ya se han entregado '.$ficha['entregado'].'
                            &euro;)':''!!}</h6>

                        @if($ficha['preciogaleriacompleta']>0&&!$ficha->pagado&&!$ficha->pagadomanual)
                        <h6 class="mb-0">El precio de la galería COMPLETA es de
                            {{$ficha['preciogaleriacompleta']}} &euro; por las {{count($galeria)}} fotografías</h6>

                            @if($seleccionadas<count($galeria))
                            <button wire:click="seleccionartodo" type="button" class="btn bg-gradient-primary mt-4">
                                Seleccionar galería completa
                            </button>
                            @endif


                        @endif
                    </div>
                </div>
                @if($ficha['numfotos']<$ficha['maxfotos']) <div class="col-12 col-md-12 text-center mb-4">
                    <div class="border border-light border-1 border-radius-md py-3">
                        <h6 class="mb-0">A partir de {{$ficha['numfotos']}}, cada fotografía
                            adicional cuesta {{$ficha['preciofoto']}} &euro;</h6>
                    </div>
            </div>
            @endif

            @if($ficha['pack1']+$ficha['pack2']+$ficha['pack3']>0)
            <div class="col-12 col-md-12 text-center mb-4">
                <div class="border border-light border-1 border-radius-md py-3">
                    @if($ficha['pack1']>0 && $ficha['pack1precio'])
                    <h6 class="mb-0">Puede seleccionar un pack de {{$ficha['pack1']}}
                        fotografías adicionales por
                        {{$ficha['pack1precio']}} &euro;</h6>
                    @endif
                    @if($ficha['pack2']>0 && $ficha['pack2precio'])
                    <h6 class="mb-0">Puede seleccionar un pack de {{$ficha['pack2']}}
                        fotografías adicionales por
                        {{$ficha['pack2precio']}} &euro;</h6>
                    @endif
                    @if($ficha['pack3']>0 && $ficha['pack3precio'])
                    <h6 class="mb-0">Puede seleccionar un pack de {{$ficha['pack3']}}
                        fotografías adicionales por
                        {{$ficha['pack3precio']}} &euro;</h6>
                    @endif
                </div>
            </div>
            @endif
        <x-separador />
    </div>
</div>

</div>


</div>


@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/photoswipe/photoswipe.css">
@endpush
@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<!--<script src="{ { asset('assets') } }/js/lightbox-plus-jquery.min.js"></script>-->
<script src="{{ asset('assets') }}/js/plugins/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets') }}/spotlight/spotlight.bundle.js"></script>


    


<script>

document.addEventListener('livewire:initialized', function() {
    Livewire.hook('morph.added',  ({ el }) => {
        //refreshFsLightbox();
        $('#staticBackdrop').modal('hide');
    });
});

window.addEventListener('closemodalproducto', event => { 
    $('.modalproducto').modal('hide');
});
window.addEventListener('focusonproducto', event => {
    document.getElementById('fprod'+event.detail[0].key).scrollIntoView();
});

window.addEventListener('focusonproductosel', event => {
    document.getElementById('fprodsel'+event.detail[0].key).scrollIntoView();
});

window.addEventListener('refreshFsLightbox', event => {
    //refreshFsLightbox();
});

//function postprocesado_init(){
//    $('#staticBackdrop').modal('show');
//}

//window.addEventListener('postprocesado_endd', event => { 
//    $('#staticBackdrop').modal('hide');
//});

function marcarimagenjs(x,btn){
    @this.marcarimagennocheck(x);
    const collection = document.getElementsByClassName("spl-button");
    
    if(collection[0].innerHTML=="marcar"){
        collection[0].innerHTML = "desmarcar";
    }else{
        collection[0].innerHTML = "marcar";
    }
}

</script>
@if(!env('TESTMODE')||!env('TESTMODE'))
<script type="text/javascript">
    document.oncontextmenu =new Function("return false;")
    //document.onselectstart =new Function("return false;")
</script>
@endif    
@endpush