<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar plantilla galería</h5>
                    <p>{{$ficha->nombre}}</p>


                    <div class="form-group col-6 col-md-7 mt-2">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Click para copiar de otra plantilla
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                @foreach($plantillas as $key=>$p)
                                <li><a class="dropdown-item"
                                        wire:click="selectplantilla({{$key}})">{{$p['nombreinterno']?$p['nombreinterno']:$p['nombre']}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>


                    <div class="col-12 text-end">
                        <a class="btn bg-gradient-dark mb-0 me-4 float-end" wire:click="update(1)"
                            href="{{ route('plantillagaleria-management') }}">volver</a>
                    </div>
                </div>
                <div class="card-body">




                    <div class="row">
                        <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                            <div class="nav-wrapper position-relative" xxwireignore>
                                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                    <li></li>
                                    <li class="nav-item" wire:click="vseccion(1)">
                                        <a class="nav-link mb-0 px-0 py-1 active {{$seccion==1?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="true">
                                            <i class="material-icons text-lg position-relative">settings</i>
                                            <span class="ms-1">Básico</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="vseccion(2)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==2?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">tune</i>
                                            <span class="ms-1">Opciones</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="vseccion(4)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==4?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">list</i>
                                            <span class="ms-1">Productos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="vseccion(3)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==3?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">photo_library</i>
                                            <span class="ms-1">Fotografías</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="vseccion(5)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==5?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">payments</i>
                                            <span class="ms-1">Pago</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="vseccion(6)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==6?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">preview</i>
                                            <span class="ms-1">Visualización</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-4 {{$seccion==1?'visible':'oculto'}}">
                            <!--basico-->
                            <x-input :tipo="'name'" :col="9" :colmd="12" :idfor="'idocint'"
                                :model="'ficha.nombreinterno'"
                                :titulo="'Descripción de la plantilla -> en galería, título interno de la galería'"
                                :disabled="''" :maxlen="''" :change="''" />

                            <x-input :tipo="'name'" :col="9" :colmd="12" :idfor="'idoc'" :model="'ficha.nombre'"
                                :titulo="'Descripción de la plantilla -> en galería, título de la galería para el cliente'"
                                :disabled="''" :maxlen="''" :change="''" />

                            <div class="col-12 mt-4" id="eeeditor" style="">
                                <label>Anotaciones para el cliente</label>
                                <livewire:quilloh wire:model.live="ficha.anotaciones" theme="snow"
                                    idid="fichaanotaciones" />
                            </div>
                            <div class="col-12 mt-4" id="eeeditor2" style="">
                                <label>Anotaciones internas</label>
                                <livewire:quilloh wire:model.live="ficha.anotaciones2" theme="snow"
                                    idid="fichaanotaciones2" />
                            </div>


                        </div>
                        <div class="{{$seccion==2?'visible':'oculto'}}">
                            <!--opciones-->
                            <div class="row mt-4">
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'inumf'" :model="'ficha.numfotos'"
                                    :titulo="'Número de fotos a seleccionar'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregal'"
                                    :model="'ficha.preciogaleria'" :titulo="'Precio de la sesión'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregalc'"
                                    :model="'ficha.preciogaleriacompleta'" :titulo="'Precio de la galería completa'"
                                    :disabled="''" :maxlen="''" :change="''" :subtitulo="'(debes sumar el precio de la sesión si lo hay)'" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'imax'" :model="'ficha.maxfotos'"
                                    :titulo="'Máximo de fotos a seleccionar (0-sin límite)'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'iprefot'"
                                    :model="'ficha.preciofoto'" :titulo="'Precio de cada foto adicional'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'icadu'"
                                    :model="'ficha.diascaducidad'"
                                    :titulo="'Días de caducidad desde que se crea la plantilla'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <x-separador />






                                    <div class="form-group col-12 col-md-12 mt-3">
                                        <label>Permitir descarga de galería después del pago:</label>
                                        <div class="form-check form-check-inline">
                                            <input wire:model.live="ficha.permitirdescarga" class="form-check-input" type="radio"
                                                value='1' id="f1a">
                                            <label class="form-check-label" for="f1a">
                                                Permitir después del pago
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input wire:model.live="ficha.permitirdescarga" class="form-check-input" type="radio"
                                                value='2' id="f2a">
                                            <label class="form-check-label" for="f2a">
                                                No permitir descarga
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input wire:model.live="ficha.permitirdescarga" class="form-check-input" type="radio"
                                                value='3' id="f3a">
                                            <label class="form-check-label" for="f3a">
                                                Permitir siempre
                                            </label>
                                        </div>
                                    </div>                                    




                                
                                
                                
                                
                                
                                <x-separador />
                                <x-input :tipo="'number'" :col="3" :colmd="3" :idfor="'pack1'" :model="'ficha.pack1'"
                                    :titulo="'Pack de fotos (x fotos->precio pack)'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="3" :colmd="3" :idfor="'pack1pre'"
                                    :model="'ficha.pack1precio'" :titulo="'Precio del pack'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="3" :colmd="3" :idfor="'pack2'" :model="'ficha.pack2'"
                                    :titulo="'Pack de fotos (x fotos->precio pack)'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="3" :colmd="3" :idfor="'pack2pre'"
                                    :model="'ficha.pack2precio'" :titulo="'Precio del pack'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="3" :colmd="3" :idfor="'pack3'" :model="'ficha.pack3'"
                                    :titulo="'Pack de fotos (x fotos->precio pack)'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="3" :colmd="3" :idfor="'pack3pre'"
                                    :model="'ficha.pack3precio'" :titulo="'Precio del pack'" :disabled="''" :maxlen="''"
                                    :change="''" />

                            </div>
                        </div>



                        <div class="{{$seccion==3?'visible':'oculto'}}">
                            <div class="row mt-4">
                                <!--fotografias-->
                                <x-inputboolean :model="'ficha.marcaagua'"
                                    :titulo="'Al adjuntar fotografías, incrustar marca de agua (IMPORTANTE, MARQUE ANTES DE SUBIRLAS)'" :maxlen="''"
                                    :idfor="'iagua'" :col="12" :colmd="12" :disabled="''" :change="''" />
                                <x-inputboolean :model="'ficha.nombresfotos'"
                                    :titulo="'En galería cliente, mostrar nombres de archivo de las fotografías'" :maxlen="''"
                                    :idfor="'inomfot'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            </div>
                            <div class="col-8 col-md-5 mt-3">
                                <div class="form-group">
                                    <label>Permitir comentarios en las fotografías:</label>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='1' id="f1" wire:change="comentarios()">
                                        <label class="form-check-label" for="f1">Siempre</label>
                                    </div>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='2' id="f2" wire:change="comentarios()">
                                        <label class="form-check-label" for="f2">Solo fotografías seleccionadas</label>
                                    </div>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='3' id="f3" wire:change="comentarios()">
                                        <label class="form-check-label" for="f3">Solo fotografías no
                                            seleccionadas</label>
                                    </div>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='4' id="f4" wire:change="comentarios()">
                                        <label class="form-check-label" for="f4">Nunca</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="{{$seccion==4?'visible':'oculto'}}">
                            <!--contenido-->
                            <div class="row mt-4">



                                @if(1==2)
                                <x-input :tipo="'text'" :col="9" :colmd="7" :idfor="'contenido1'"
                                    :model="'ficha.incluido1'" :titulo="'Contenido incluido en galería - 1'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'text'" :col="9" :colmd="7" :idfor="'contenido2'"
                                    :model="'ficha.incluido2'" :titulo="'Contenido incluido en galería - 2'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'text'" :col="9" :colmd="7" :idfor="'contenido3'"
                                    :model="'ficha.incluido3'" :titulo="'Contenido incluido en galería - 3'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'text'" :col="9" :colmd="7" :idfor="'contenido4'"
                                    :model="'ficha.incluido4'" :titulo="'Contenido incluido en galería - 4'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'text'" :col="9" :colmd="7" :idfor="'contenido5'"
                                    :model="'ficha.incluido5'" :titulo="'Contenido incluido en galería - 5'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-separador />
                                <x-input :tipo="'text'" :col="7" :colmd="7" :idfor="'opcional1'"
                                    :model="'ficha.opcional1'" :titulo="'Contenido opcional en galería - 1'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="5" :colmd="2" :idfor="'preopc1'"
                                    :model="'ficha.precioopc1'" :titulo="'Precio'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'text'" :col="7" :colmd="7" :idfor="'opcional2'"
                                    :model="'ficha.opcional2'" :titulo="'Contenido opcional en galería - 2'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="5" :colmd="2" :idfor="'preopc2'"
                                    :model="'ficha.precioopc2'" :titulo="'Precio'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'text'" :col="7" :colmd="7" :idfor="'opcional3'"
                                    :model="'ficha.opcional3'" :titulo="'Contenido opcional en galería - 3'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="5" :colmd="2" :idfor="'preopc3'"
                                    :model="'ficha.precioopc3'" :titulo="'Precio'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'text'" :col="7" :colmd="7" :idfor="'opcional4'"
                                    :model="'ficha.opcional4'" :titulo="'Contenido opcional en galería - 4'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="5" :colmd="2" :idfor="'preopc4'"
                                    :model="'ficha.precioopc4'" :titulo="'Precio'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'text'" :col="7" :colmd="7" :idfor="'opcional5'"
                                    :model="'ficha.opcional5'" :titulo="'Contenido opcional en galería - 5'"
                                    :disabled="''" :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="5" :colmd="2" :idfor="'preopc5'"
                                    :model="'ficha.precioopc5'" :titulo="'Precio'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                @endif





                                <h6 class="mb-0">Productos incluidos en el precio de la galería</h6>
                                <div class="form-group col-12 col-md-12 mt-4">
                                    <div class="dropdown">
                                        <a class="btn btn-secondary mb-0 me-4 float-start dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">Añadir productos
                                            incluidos</a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            @foreach($productosdisponibles as $key=>$p)
                                            <li><a class="dropdown-item"
                                                    wire:click="addproducto({{$key}},1)">{{$p['nombre']}}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @if($productosincluidos==0)
                                    <p>Aún no ha añadido ningún producto incluido (estos productos NO tienen coste para
                                        el cliente)</p>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <p>Si modifica la ficha de un producto que está en alguna plantilla de galería se actualizarán los datos del producto también en las plantillas
                                    </p>
                                </div>
                                @foreach($productos as $key=>$tag)
                                @if($tag['incluido'])
                                @include('livewire.galeria.galeria_producto',['origen'=>'plantilla'])
                                @endif
                                @endforeach
                                <h6 class="mb-0">Productos adicionales</h6>
                                <div class="form-group col-12 col-md-12 mt-4">
                                    <div class="dropdown">
                                        <a class="btn btn-secondary mb-0 me-4 float-start dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">Añadir productos
                                            adicionales</a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            @foreach($productosdisponibles as $key=>$p)
                                            <li><a class="dropdown-item"
                                                    wire:click="addproducto({{$key}},0)">{{$p['nombre']}}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12">
                                    @if($productosadicionales==0)
                                    <p>Aún no ha añadido ningún producto</p>
                                    @endif
                                    <p>Si modifica la ficha de un producto que está en alguna plantilla de galería se actualizarán los datos del producto también en las plantillas
                                    </p>
                                </div>
                                @foreach($productos as $key=>$tag)
                                @if($tag['incluido']==false)
                                @include('livewire.galeria.galeria_producto',['origen'=>'plantilla'])
                                @endif
                                @endforeach








                            </div>
                        </div>







                        <div class="{{$seccion==5?'visible':'oculto'}}">
                            <div class="row mt-4">
                                <!--pago-->
                                <div class="form-group col-12 col-md-6 mt-3">
                                    <label>Formas de pago disponibles en esta galería:</label>
                                    @if($formaspago->efectivo)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago1activo">&nbsp;Efectivo</input>
                                    </div>
                                    @endif
                                    @if($formaspago->transferencia)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago2activo">&nbsp;Transferencia bancaria a mi cuenta</input>
                                    </div>
                                    @endif
                                    @if($formaspago->redsys)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago3activo">&nbsp;Redsys (tarjeta de crédito)</input>
                                    </div>
                                    @endif
                                    @if($formaspago->paypal)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago4activo">&nbsp;Paypal {{$formaspago->ppalprc>0?'(incremento
                                            '.$formaspago->ppalprc.'%)':''}}</input>
                                    </div>
                                    @endif
                                    @if($formaspago->stripe)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago5activo">&nbsp;Stripe {{$formaspago->stripeprc>0?'(incremento
                                            '.$formaspago->stripeprc.'%)':''}}</input>
                                    </div>
                                    @endif
                                    @if($formaspago->bizum)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago6activo">&nbsp;Bizum (teléfono para pago: {{$formaspago->bizumtelefono}})</input>
                                    </div>
                                    @endif
                                </div>






                                <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'iepga'"
                                    :model="'ficha.emailpagoasunto'"
                                    :titulo="'Envío de email cuando se realiza el pago: asunto'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <div class="col-12 mt-4" id="eeeditor2" style="">
                                    <label>Envío de email cuando se realiza el pago: cuerpo</label>
                                    <livewire:quilloh wire:model.live="ficha.emailpagocuerpo" theme="snow"
                                        idid="fichaemailpagocuerpo" />
                                </div>
                                <div class="col-12 text-start">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                            data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                        <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(211,2)">enlace a la galería</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(212,2)">caducidad de la galería</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(101,2)">check obligatorio</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(102,2)">nombre empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(103,2)">nombre propio empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(104,2)">domicilio empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(105,2)">código postal empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(106,2)">población empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(107,2)">provincia empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(108,2)">teléfono empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(109,2)">email empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(110,2)">nombre/apellidos cliente</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(111,2)">importe total del pago</a></li>
                                        </ul>
                                    </div>
                                </div>





                                <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'iepga'"
                                    :model="'ficha.emailconfirmaasunto'"
                                    :titulo="'Envío de email cuando se confirma la selección de fotografías: asunto'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <div class="col-12 mt-4" id="eeeditor3" style="">
                                    <label>Envío de email cuando se confirma la selección: cuerpo</label>
                                    <livewire:quilloh wire:model.live="ficha.emailconfirmacuerpo" theme="snow"
                                        idid="fichaemailconfirmacuerpo" />
                                </div>
                                <div class="col-12 text-start">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                            data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                        <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(211,3)">enlace a la galería</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(212,3)">caducidad de la galería</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(101,3)">check obligatorio</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(102,3)">nombre empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(103,3)">nombre propio empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(104,3)">domicilio empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(105,3)">código postal empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(106,3)">población empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(107,3)">provincia empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(108,3)">teléfono empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(109,3)">email empresa</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(110,3)">nombre/apellidos cliente</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(111,3)">importe total del pago</a></li>
                                        </ul>
                                    </div>
                                </div>








                            </div>
                        </div>
                        <div class="{{$seccion==6?'visible':'oculto'}}">
                            <div class="row mt-4">
                                <!--visualizacion-->

                                <div class="col-12 col-md-6">
                                    <p class="mb-0 font-weight-normal text-sm">
                                        Imagen de portada de la galería (debería ser una imagen en apaisado)
                                    </p>
                                    @if(strlen($ficha->binariomin)>0)
                                    <p class="mb-0 font-weight-normal text-sm puntero italica" wire:click="deleteimage">
                                        Eliminar imagen
                                    </p>
                                    <img id="idfot1" src=
                                            @if(Session('soporteavif'))
                                                "data:image/jpeg;base64,{{ $ficha->binariomin }}"
                                            @else
                                                "{{ Utils::inMacBase64($ficha->binariomin) }}"
                                            @endif
                                        class="img-fluid shadow border-radius-xl" />
                                    @endif
                                    <x-filepond wire:model="files" maxsize="50MB" resize="false" width="1920"
                                        height="1080" varname="fi1" w5sec="true"/>
                                </div>

                            </div>
                            <x-separador />

                            <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'ienvasu'"
                                :model="'ficha.emailenvioasunto'"
                                :titulo="'Envío de email al cliente para visualizar la galería: asunto'" :disabled="''"
                                :maxlen="''" :change="''" />
                            <div class="col-12 mt-4" id="eeeditor2" style="">
                                <label>Envío de email para visualizar la galería: cuerpo</label>
                                <livewire:quilloh wire:model.live="ficha.emailenviocuerpo" theme="snow"
                                    idid="fichaemailenviocuerpo" />
                            </div>
                            <div class="col-12 text-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                        data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                    <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(211,1)">enlace a la galería</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(212,1)">caducidad de la galería</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(213,1)">clave de la galería</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(201,1)">check obligatorio</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(202,1)">nombre empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(203,1)">nombre propio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(204,1)">domicilio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(205,1)">código postal empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(206,1)">población empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(207,1)">provincia empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(208,1)">teléfono empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(209,1)">email empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(210,1)">nombre/apellidos cliente</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(214,1)">fotos a seleccionar</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>




                        <hr class="horizontal light mt-4">
                        @if($errors->any())
                        <div class="row">
                            {!! implode('', $errors->all('<div class="col-12 text-center rojo">:message</div>')) !!}
                        </div>
                        @endif

                        <div class="row">

                            <div class="col-12 text-center">
                                <button wire:click="update" class="btn btn-dark mt-3 text-center">Guardar
                                    plantilla</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quilloh.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill-resize-module.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill.imageCompressor.min.js"></script>
<script>
</script>
@endpush