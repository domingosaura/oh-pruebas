<div class="container-fluid py-4" id="master">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar galería</h5>
                    <p>{{$ficha->nombre}}{!!$ficha->nombreinterno?' - <span
                            class="italica">'.$ficha->nombreinterno.'</span>':''!!}</p>
                    <div class="col-6 float-start">
                        @if($ficha['pagado']==false&&$ficha['pagadomanual']==false)
                        <div class="form-group col-6 col-md-7">
                            <div class="dropdown">
                                <a class="btn btn-secondary mb-0 me-4 float-start dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">copiar desde plantilla</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @foreach($plantillas as $key=>$p)
                                    <li><a class="dropdown-item"
                                            wire:click="selectplantilla({{$key}})">{{$p['nombreinterno']?$p['nombreinterno']:$p['nombre']}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-6 float-end">
                        <a class="btn bg-gradient-dark mb-0 me-4 float-end" wire:click="update(1)"
                            href="{{ route('galeria-management') }}">volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">


                        <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                            <div class="nav-wrapper position-relative" nowireignore>
                                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                    <li></li>
                                    <li class="nav-item" wire:click="vseccion(1)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==1?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
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



                        @if($descargadisponible&&strlen($rutadescarga)>0)
                        <div class="row mt-6 mb-4">
                            <div class="col-12 text-center">
                            <a href="{{$rutadescarga}}" wire:click="descargaclear" target="_blank">
                        <button type="button"
                        class="btn fondonaranjito blanco">Descarga lista, pulse para descargar</button>
                        </a>
                        </div>
                        </div>
                        @endif
        



                        <div class="row mt-4 {{$seccion==1?'visible':'oculto'}}">
                            <!--basico-->


                            @if($ficha['enviado'])
                            <div class="col-12 mt-4 navy">
                                @if($ficha['dtenvio'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio2'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio2']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio3'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio3']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio4'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio4']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio5'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio5']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio6'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio6']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio7'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio7']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio8'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio8']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio9'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio9']))}}</strong><br/>
                                @endif
                                @if($ficha['dtenvio10'])
                                <strong>Esta galería fué enviada al cliente en {{date('d/m/Y H:i:s',strtotime($ficha['dtenvio10']))}}</strong>
                                @endif
                            </div>
                            @endif


                            <div style="z-index: 999;z-index: 998;" class="form-group col-auto mb-4">
                                <div class="row">
                                    <label>Cliente de la galería&nbsp;
                                        <i class="material-icons negro puntero" title="nuevo cliente"
                                            data-bs-target="#createcliente" data-bs-toggle="modal">person_add</i>
                                        @if($ficha['cliente_id']>0)
                                        &nbsp;
                                        <a href="{{ route('edit-cliente', $ficha['cliente_id'])}}">
                                        <i class="material-icons negro puntero" title="ficha cliente">border_color</i>
                                        </a>
                                        @endif
                                        <!--
                                        <button type="button" class="btn btn-sm btn-primary abtn-link" title="nuevo"
                                            data-bs-target="#createcliente" data-bs-toggle="modal">
                                            <i class="material-icons">person_add</i>
                                        </button>
                                    -->
                                    </label>
                                    <div {{$ficha['firmado']?'disabled':''}} style="z-index: 999;" class="aform-control"
                                        id="select-cli" placeholder="cliente" value="{{$ficha['cliente_id']}}"
                                        wire:ignore>
                                    </div>
                                </div>
                            </div>

                            <x-input :tipo="'name'" :col="9" :colmd="12" :idfor="'idoci'" :model="'ficha.nombreinterno'"
                                :titulo="'título de la galería (interno, no lo ve el cliente)'" :disabled="''"
                                :maxlen="''" :change="'update(true)'" />

                            <x-input :tipo="'name'" :col="9" :colmd="12" :idfor="'idoc'" :model="'ficha.nombre'"
                                :titulo="'título de la galería (el que ve el cliente)'" :disabled="''" :maxlen="''"
                                :change="'update(true)'" />
                            <x-inputboolean :model="'ficha.archivada'" :titulo="'Galería archivada (inactiva)'"
                                :maxlen="''" :idfor="'iarchived'" :col="12" :colmd="12" :disabled="''" :change="''" />

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
                                    <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'imax'" :model="'ficha.maxfotos'"
                                        :titulo="'Máximo de fotos a seleccionar (0-sin límite)'" :disabled="''" :maxlen="''"
                                        :change="''" />
                                        <x-separador />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregal'"
                                    :model="'ficha.preciogaleria'" :titulo="'Precio de la sesión'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregalc'"
                                    :model="'ficha.preciogaleriacompleta'" :titulo="'Precio de la galería completa'"
                                    :disabled="''" :maxlen="''" :change="''" :subtitulo="'(debes sumar el precio de la sesión si lo hay)'" />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ientrega'"
                                    :model="'ficha.entregado'" :titulo="'¿Ha entregado señal?'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'iprefot'"
                                    :model="'ficha.preciofoto'" :titulo="'Precio de cada foto adicional'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="5" :colmd="3" :idfor="'pack1'" :model="'ficha.pack1'"
                                    :titulo="'Pack de fotos (x fotos->precio pack)'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="3" :colmd="2" :idfor="'pack1pre'"
                                    :model="'ficha.pack1precio'" :titulo="'Precio del pack'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="5" :colmd="3" :idfor="'pack2'" :model="'ficha.pack2'"
                                    :titulo="'Pack de fotos (x fotos->precio pack)'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="3" :colmd="2" :idfor="'pack2pre'"
                                    :model="'ficha.pack2precio'" :titulo="'Precio del pack'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-separador />
                                <x-input :tipo="'number'" :col="5" :colmd="3" :idfor="'pack3'" :model="'ficha.pack3'"
                                    :titulo="'Pack de fotos (x fotos->precio pack)'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'number'" :col="3" :colmd="2" :idfor="'pack3pre'"
                                    :model="'ficha.pack3precio'" :titulo="'Precio del pack'" :disabled="''" :maxlen="''"
                                    :change="''" />

                                <x-separador />
                                <x-inputdate :model="'ficha.caducidad'" :titulo="'Fecha máxima para confirmar galería'"
                                    :maxlen="''" :idfor="'cadugal'" :col="5" :colmd="4" :disabled="''" :change="''" />
                                <p>el cliente recibirá un mail de aviso un día antes de caducar la galería</p>
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
                            </div>

                            <div class="col-8 col-md-5 mt-3">
                                <div class="form-group">
                                    <label>Permitir comentarios en las fotografías:</label>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='1' id="f1b" wire:change="">
                                        <label class="form-check-label" for="f1b">Siempre</label>
                                    </div>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='2' id="f2b" wire:change="">
                                        <label class="form-check-label" for="f2b">Solo fotografías seleccionadas</label>
                                    </div>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='3' id="f3b" wire:change="">
                                        <label class="form-check-label" for="f3b">Solo fotografías no
                                            seleccionadas</label>
                                    </div>
                                    <div class="form-check">
                                        <input wire:model.live="ficha.permitircomentarios" class="form-check-input"
                                            type="radio" value='4' id="f4b" wire:change="">
                                        <label class="form-check-label" for="f4b">Nunca</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="{{$seccion==3?'visible':'oculto'}}">
                            <div class="row mt-4">
                                <!--fotografias-->
                                <div class="col-2"></div>
                                <x-inputboolean :model="'ficha.marcaagua'"
                                    :titulo="'Al adjuntar fotografías, incrustar mi marca de agua (IMPORTANTE, MARQUE ANTES DE SUBIRLAS)'" :maxlen="''"
                                    :idfor="'iagua'" :col="8" :colmd="8" :disabled="''" :change="'updatemarcaagua()'" />
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <x-inputboolean :model="'ficha.nombresfotos'"
                                    :titulo="'En galería cliente, mostrar nombres de archivo de las fotografías'" :maxlen="''"
                                    :idfor="'inomfot'" :col="8" :colmd="8" :disabled="''" :change="''" />
                                <div class="col-2"></div>

                                    
                                    

                                <div class="col-2"></div>
                                <div class="col-8">
                                    <x-filepondgallery2 wire:model="images" maxsize="90MB" resize="false" width="425"
                                        height="283" varname="fi2" multiple w5sec="true" idgaleria="{{$idgaleria}}" userid="{{$userid}}"/>
                                </div>
                                <div class="col-2"></div>
                                <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                                    <div class="nav-wrapper position-relative" nowireignore>
                                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                            <li></li>
                                            <li class="nav-item" wire:click="loadimages(1)">
                                                <a class="nav-link mb-0 px-0 py-1 {{$versele==1?'naranjitobold':''}}" data-bs-toggle="tab"
                                                    href="" role="tab" aria-selected="false">
                                                    <i class="material-icons text-lg position-relative">apps</i>
                                                    <span class="ms-1">Todas {{$totalfotos}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" wire:click="loadimages(2)">
                                                <a class="nav-link mb-0 px-0 py-1 {{$versele==2?'naranjitobold':''}}" data-bs-toggle="tab"
                                                    href="" role="tab" aria-selected="false">
                                                    <i class="material-icons text-lg position-relative">favorite</i>
                                                    <span class="ms-1">Seleccionadas {{$seleccionadas}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" wire:click="loadimages(3)">
                                                <a class="nav-link mb-0 px-0 py-1 {{$versele==3?'naranjitobold':''}}" data-bs-toggle="tab"
                                                    href="" role="tab" aria-selected="false">
                                                    <i class="material-icons text-lg position-relative">close</i>
                                                    <span class="ms-1">No seleccionadas {{$totalfotos-$seleccionadas}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" wire:click="loadimages(4)">
                                                <a class="nav-link mb-0 px-0 py-1 {{$versele==4?'naranjitobold':''}}" data-bs-toggle="tab"
                                                    href="" role="tab" aria-selected="false">
                                                    <i class="material-icons text-lg position-relative">chat</i>
                                                    <span class="ms-1">Con anotaciones</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" wire:click="">
                                                <div class="dropdown">
                                                    <a class="nav-link mb-0 px-0 py-1 dropdown-toggle"data-bs-toggle="dropdown"
                                                    href="" role="tab" aria-selected="false" title="Ordenar">
                                                    <i class="material-icons text-lg position-relative">sort</i>
                                                    <span class="ms-1">Ordenar</span>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            wire:click="ordenar(1)">Nombre, ascendente</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            wire:click="ordenar(2)">Nombre, descendente</a>
                                                    </li>
                                                </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item" wire:click="">
                                                <div class="dropdown">
                                                    <a class="nav-link mb-0 px-0 py-1 dropdown-toggle puntero" data-bs-toggle="dropdown"
                                                    role="tab" aria-selected="false" title="Acciones disponibles">
                                                    <i class="material-icons text-lg position-relative">list</i>
                                                    <span class="ms-1">Acciones</span>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1m">
                                                    <li>
                                                        <a class="dropdown-item" onclick="postprocesado_init()"
                                                            wire:click="descargargaleria(1)">Descargar galería completa ({{$sizegallery}}Mb.)</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" onclick="postprocesado_init()"
                                                            wire:click="descargargaleria(2)">Descargar selección del cliente</a>
                                                    </li>
                                                    <!--<li>
                                                        <a class="dropdown-item" onclick="postprocesado_init()"
                                                            wire : click="descargargaleria(3)">Descargar fotografías marcadas por mí</a>
                                                    </li>-->
                                                    <li>
                                                        <a class="dropdown-item"
                                                        onclick="if(confirm('¿Eliminar todas las fotografías marcadas?.')){postprocesado_init();}else{event.stopImmediatePropagation();}"
                                                        wire:click="vaciarfotosmarcadas()">Eliminar fotografías marcadas</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                        onclick="if(confirm('¿Eliminar todas las fotografías de la galería?.')){postprocesado_init();}else{event.stopImmediatePropagation();}"
                                                        wire:click="vaciarfotos()">Eliminar TODAS las fotografías</a>
                                                    </li>
                                                </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                    href="#" role="tab" aria-selected="false">
                                                    <span class="ms-1 naranjito" data-bs-target="#export"
                                                        data-bs-toggle="modal">Lightroom/Photo Mechanic</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <x-separador />
                                @foreach ($galeria as $key=>$tag)
                                <div class="col-xl-3 col-md-6 mb-2 mt-2" id="fot1_{{$key}}">
                                    <div class="card Xcard-blog Xcard-plain {{$tag['selected']?'fondoseleccion':''}}">
                                        <div class="card-header p-0 mt-2 mx-3">
                                            
                                            
                                            
                                            <img src=
                                            @if(Session('soporteavif'))
                                            "{{ Storage::url('tmpgallery/'.$tag['file']) }}"
                                            @else
                                            "{{ Utils::inMacGallery($tag['file']) }}"
                                            @endif
                                               class="img-fluid shadow border-radius-xl">
                                               

                                            </div>
                                        <div class="card-body p-3">
                                            <div class="xd-flex align-items-center float-start">
                                                <i class="material-icons negro puntero" title="mover"
                                                    wire:click="move({{ $tag['id'] }},{{ $tag['position'] }},1)">keyboard_arrow_left</i>
                                            </div>
                                            <div class="xd-flex align-items-center float-end">
                                                <i class="material-icons negro puntero" title="mover"
                                                    wire:click="move({{ $tag['id'] }},{{ $tag['position'] }},2)">keyboard_arrow_right</i>
                                            </div>
                                            <div class="col-12">&nbsp;
                                            </div>
                                            <h6>
                                                <a href="">
                                                    {{ $tag['nombre'] }}
                                                </a>
                                            </h6>
                                            @if($tag['anotaciones']&&1==11)
                                            <div class="xd-flex align-items-center xxfloat-start">
                                                <p class="navy">{{$tag['anotaciones']}}</p>
                                            </div>
                                            @endif

                                            

                                            <div class="xd-flex align-items-center float-start">
                                                <i class="material-icons puntero {{ $tag['selected']?'negro':'gris' }}"
                                                    title="{{ $tag['selected']?'seleccionada':'no seleccionada' }}"
                                                    onclick="confirm('¿Seguro que desea modificar la selección del cliente?') || event.stopImmediatePropagation()"
                                                    wire:click="marcarimagennck({{$key}})">
                                                    {{$tag['selected']?'favorite':'favorite' }}</i>
                                                <i class="material-icons {{ $tag['anotaciones']?'negro':'gris' }} puntero"
                                                    title="{{ $tag['anotaciones']?'anotaciones de cliente':'sin anotaciones de cliente' }}"
                                                    data-bs-target="#notascliente" data-bs-toggle="modal"
                                                    wire:click="cargaranotaciones({{$key}})"
                                                    onclick="
                                                    @if($tag['anotaciones']&&1==11)
                                                    alert('{{ $tag['anotaciones']}}');
                                                    @endif
                                                    ">chat</i>
                                            </div>


                                            <div class="xd-flex align-items-center float-end">
                                                <i class="material-icons negro puntero" title="descargar imagen"
                                                    wire:click="descargarimagen({{ $tag['id'] }},{{$key}})">vertical_align_bottom</i>
                                                <i class="material-icons negro puntero"
                                                    title="colocar como imagen de portada de galería"
                                                    wire:click="toencabezado({{ $tag['id'] }})"
                                                    >image</i>

                                                <i class="material-icons negro puntero"
                                                    onclick="confirm('¿Eliminar la fotografía de la galería?.') || event.stopImmediatePropagation()"
                                                    wire:click="deleteimagegallery({{ $tag['id'] }})"
                                                    title="eliminar la fotografía">delete_forever</i>
                                                    <input class="check-naranja" type="checkbox" wire:model="galeria.{{$key}}.selectedfordelete" title="marcar para acciones"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="{{$seccion==4?'visible':'oculto'}}">
                            <div class="row mt-4">
                                <!--contenido-->


                                <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                                    <div class="nav-wrapper position-relative" id="seleoprod" nowireignore>
                                        <ul class="nav nav-pills nav-fill p-1" role="tablist" id="seleoprod1">
                                            <li></li>
                                            <li class="nav-item" wire:click="mostrarsoloproductos(1)" id="seleoprod2">
                                                <a class="nav-link mb-0 px-0 py-1 {{$versoloproductos==1?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                                    role="tab" aria-selected="true" id="seleoprod3">
                                                    <i class="material-icons text-lg position-relative">apps</i>
                                                    <span class="ms-1">Todos</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" wire:click="mostrarsoloproductos(2)" id="seleoprod4">
                                                <a class="nav-link mb-0 px-0 py-1 {{$versoloproductos==2?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                                    aria-selected="false" id="seleoprod5">
                                                    <i class="material-icons text-lg position-relative">favorite</i>
                                                    <span class="ms-1">Seleccionados</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" wire:click="mostrarsoloproductos(3)" id="seleoprod6">
                                                <a class="nav-link mb-0 px-0 py-1 {{$versoloproductos==3?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                                    role="tab" aria-selected="false" id="seleoprod7">
                                                    <i class="material-icons text-lg position-relative">close</i>
                                                    <span class="ms-1">No seleccionados</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                    


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
                                    <p>Una vez que haya añadido un producto desde su plantilla éste no cambiará si
                                        modifica
                                        esa plantilla, si quiere cambiar el producto en la galería tendrá que eliminarlo y añadirlo de nuevo
                                    </p>
                                </div>
                                @foreach($productos as $key=>$tag)
                                @if($tag['incluido'])
                                @if(  ($versoloproductos==2&&$tag['seleccionada'])||($versoloproductos==3&&$tag['seleccionada']==false)||$versoloproductos==1  )
                                @include('livewire.galeria.galeria_producto',['origen'=>'galeria'])
                                @endif
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
                                    <p>Una vez que haya añadido un producto desde su plantilla éste no cambiará si
                                        modifica
                                        esa plantilla, si quiere cambiar el producto en la galería tendrá que eliminarlo y añadirlo de nuevo
                                    </p>
                                </div>
                                @foreach($productos as $key=>$tag)
                                @if($tag['incluido']==false)
                                @if(  ($versoloproductos==2&&$tag['seleccionada'])||($versoloproductos==3&&$tag['seleccionada']==false)||$versoloproductos==1  )
                                @include('livewire.galeria.galeria_producto',['origen'=>'galeria'])
                                @endif
                                @endif
                                @endforeach

                                </div>
                        </div>
                        <div class="{{$seccion==5?'visible':'oculto'}}">
                            <div class="row mt-4">
                                <!--pago-->
                                <div class="col-4 col-md-4 text-center mb-4">
                                    <div class="border border-light border-1 border-radius-md py-3">
                                        <h6 class="text-primary text-gradient mb-0">Precio de la galería</h6>
                                        <h4 class="font-weight-bolder mb-0">
                                            <span id="state1" countTo="">{{$precio}}</span>
                                            <span class="small"> &euro;</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-8 col-md-8 text-center mb-4">
                                    <div class="border border-light border-1 border-radius-md py-3">
                                        @if(!$procesable)
                                        <h6 class="text-primary text-gradient mb-0">No se han seleccionado las opciones
                                            necesarias para confirmar/descargar la galería</h6>
                                        @endif
                                        @if($procesable && !$ficha->pagado && !$ficha->pagadomanual)
                                        <h6 class="text-primary text-gradient mb-0">La galería se puede procesar</h6>
                                        @endif
                                    </div>
                                </div>
                                <x-separador />
                                <div class="col-12 col-md-6 mt-3">
                                    <h6>Formas de pago elegibles por el cliente en esta galería:</h6>
                                    @if($formaspago->efectivo)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago1activo">&nbsp;Efectivo</input>
                                    </div>
                                    @endif
                                    @if($formaspago->transferencia)
                                    <div class="form-check">
                                            <input type="checkbox" class="check-naranja" wire:model="ficha.pago2activo">&nbsp;Transferencia bancaria a cuenta {{$iban}}</input>
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




                                <div class="col-12 col-md-6 mt-3">
                                    <div class="border border-light border-1 border-radius-md py-3">
                                        <div class="row">
                                            @foreach($desgloseado as $des)
                                            <div class="col-8 col-md-6 text-start">
                                                {{$des['texto']}}
                                            </div>
                                            <div class="col-3 col-md-3 text-end">
                                                @if($des['importe']!=-1110)
                                                {{$des['importe']}}&euro;
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                

                                <x-separador />

                                <div class="col-12 col-md-6 mt-3">
                                    <h6>Forma de pago seleccionada por el cliente: </h6>
                                        <p><strong>
                                    @if($ficha->tipodepago==0)
                                            Aún sin asignar
                                    @endif
                                    @if($ficha->tipodepago==1)
                                            Efectivo
                                    @endif
                                    @if($ficha->tipodepago==2)
                                            Transferencia bancaria a cuenta {{$iban}}
                                    @endif
                                    @if($ficha->tipodepago==3)
                                            Redsys (tarjeta de crédito)
                                    @endif
                                    @if($ficha->tipodepago==4)
                                            Paypal {{$formaspago->ppalprc>0?'(incremento '.$formaspago->ppalprc.'%)':''}}
                                    @endif
                                    @if($ficha->tipodepago==5)
                                            Stripe {{$formaspago->stripeprc>0?'(incremento '.$formaspago->stripeprc.'%)':''}}
                                    @endif
                                    @if($ficha->tipodepago==6)
                                            Bizum (teléfono para pago: {{$formaspago->bizumtelefono}})
                                    @endif
                                    </strong></p>
                                    <x-inputboolean :model="'ficha.pagado'" :titulo="'El cliente ha pagado la galería'"
                                    :maxlen="''" :idfor="'ipagado'" :col="12" :colmd="12" :disabled="''" :change="''" />
                                    <x-inputboolean :model="'ficha.pagadomanual'" :titulo="'El cliente ha pagado la galería MANUALMENTE'"
                                    :maxlen="''" :idfor="'ipagadom'" :col="12" :colmd="12" :disabled="''" :change="''" />
                                    @if($ficha['pagado'])
                                    <p>Fecha del pago: {{ Utils::datetime($ficha['fechapago']) }} Importe: {{$ficha['imppago']}}&euro;</p>
                                    @else
                                    <p>Fecha del pago: Pendiente</p>
                                    @endif
                                    <x-inputboolean :model="'ficha.seleccionconfirmada'"
                                    :titulo="'El cliente ha confirmado la selección de fotos'" :maxlen="''"
                                    :idfor="'iselecon'" :col="12" :colmd="12" :disabled="''" :change="''" />
                                    
                                    @if($ficha['fechafirma'])
                                    <p>Fecha confirmación selección: {{ Utils::datetime($ficha['fechafirma']) }}</p>
                                    @else
                                    <p>Fecha confirmación selección: Pendiente</p>
                                    @endif

                                    <x-inputboolean :model="'ficha.descargada'"
                                    :titulo="'¿El cliente ha descargado la galería?'" :maxlen="''" :idfor="'idescargada'"
                                    :col="12" :colmd="12" :disabled="'disabled'" :change="''" />

                                    @if($ficha['descargada'])
                                    <p>Fecha de la descarga: {{ Utils::datetime($ficha['fechadescarga']) }}</p>
                                    @endif

                                    @if(1==2)
                                    @if($formaspago->efectivo)
                                    <div class="form-check">
                                        <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                            value='1' id="f1c" wire:change="fpago()" {{$ficha['pagado']||$ficha['pagadomanual']?'disabled':''}}>
                                        <label class="form-check-label" for="f1c">
                                            Efectivo&nbsp;&nbsp;
                                        </label>
                                    </div>
                                    @endif
                                    @if($formaspago->transferencia)
                                    <div class="form-check">
                                        <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                            value='2' id="f2c" wire:change="fpago()" {{$ficha['pagado']||$ficha['pagadomanual']?'disabled':''}}>
                                        <label class="form-check-label" for="f2c">
                                            Transferencia bancaria a cuenta {{$iban}}&nbsp;&nbsp;
                                        </label>
                                    </div>
                                    @endif
                                    @if($formaspago->redsys)
                                    <div class="form-check">
                                        <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                            value='3' id="f3c" wire:change="fpago()" {{$ficha['pagado']||$ficha['pagadomanual']?'disabled':''}}>
                                        <label class="form-check-label" for="f3c">
                                            Redsys (tarjeta de crédito)&nbsp;&nbsp;
                                        </label>
                                    </div>
                                    @endif
                                    @if($formaspago->paypal)
                                    <div class="form-check">
                                        <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                            value='4' id="f4a" wire:change="fpago()" {{$ficha['pagado']||$ficha['pagadomanual']?'disabled':''}}>
                                        <label class="form-check-label" for="f4a">
                                            Paypal {{$formaspago->ppalprc>0?'(incremento
                                            '.$formaspago->ppalprc.'%)':''}}&nbsp;&nbsp;
                                        </label>
                                    </div>
                                    @endif
                                    @if($formaspago->stripe)
                                    <div class="form-check">
                                        <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                            value='5' id="f5" wire:change="fpago()" {{$ficha['pagado']||$ficha['pagadomanual']?'disabled':''}}>
                                        <label class="form-check-label" for="f5">
                                            Stripe {{$formaspago->stripeprc>0?'(incremento
                                            '.$formaspago->stripeprc.'%)':''}}&nbsp;&nbsp;
                                        </label>
                                    </div>
                                    @endif
                                    @if($formaspago->bizum)
                                    <div class="form-check">
                                        <input wire:model.live="ficha.tipodepago" class="form-check-input" type="radio"
                                            value='6' id="f6" wire:change="fpago()" {{$ficha['pagado']||$ficha['pagadomanual']?'disabled':''}}>
                                        <label class="form-check-label" for="f6">
                                            Bizum (teléfono para pago: {{$formaspago->bizumtelefono}})&nbsp;&nbsp;
                                        </label>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                
                                <x-separador />

                                <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'iepga'"
                                    :model="'ficha.emailpagoasunto'"
                                    :titulo="'Envío de email cuando se realiza el pago: asunto (si no rellena asunto/cuerpo se enviará mail genérico)'"
                                    :disabled="''" :maxlen="''" :change="''" />
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
                                                    wire:click="variable(112,2)">forma de pago</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(113,2)">nombre de la galería</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(111,2)">importe total del pago</a></li>
                                            </ul>
                                    </div>
                                </div>

                                <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'iepga'"
                                    :model="'ficha.emailconfirmaasunto'"
                                    :titulo="'Envío de email cuando se confirma la selección de fotografías: asunto (si no rellena asunto/cuerpo se enviará mail genérico)'"
                                    :disabled="''" :maxlen="''" :change="''" />
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
                                                    wire:click="variable(112,3)">forma de pago</a></li>
                                            <li><a class="mb-0 me-4 float-start puntero italica"
                                                    wire:click="variable(113,3)">nombre de la galería</a></li>
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

                            <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'iclacli'"
                                :model="'ficha.clavecliente'"
                                :titulo="'Clave que tiene que introducir el cliente para ver la galería'"
                                :disabled="''" :maxlen="'20'" :change="''" />
                            <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'ienvasu'"
                                :model="'ficha.emailenvioasunto'"
                                :titulo="'Envío de email al cliente para visualizar la galería: asunto (si no rellena asunto/cuerpo se enviará mail genérico)'"
                                :disabled="''" :maxlen="''" :change="''" />
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
                            <div class="col-12 col-md-12 text-center">

                                @if($ficha->cliente_id>0)
                                <a wire:click="update" target="_blank" style="color:white" class="btn bg-dark mt-3 text-center"
                                    href="{{ route('galeriacliente',[$idgaleria,$galmd5]) }}">Previsualizar como cliente</a>
                                @else
                                <a style="color:white" class="btn bg-dark mt-3 text-center" href="#">Previsualizar (no ha seleccionado cliente)</a>
                                @endif
                                @if($ficha->cliente_id>0)
                                <button wire:click="sendclient" class="btn btn-dark mt-3 text-center">Enviar por mail</button>
                                    @if($clientetelefono||1==1)
                                        <a href="https://api.whatsapp.com/send/?phone={{$clientetelefono}}&text={{urlencode($nombreempresa.' - '.$ficha->nombre.' - '.
                                        str_replace("ccaaccaa","ccaaccaa",str_replace('https://www.','www.',route('galeriacliente',[$idgaleria,$galmd5])))
                                             )}}"
                                        target="_blank" style="color:white" class="btn bg-dark mt-3 text-center"
                                        title="Whatsapp" wire:click="sendclientwhatsapp">Enviar por Whatsapp</a>
                                    @endif
                                @endif
                                <button wire:click="update" class="btn btn-dark mt-3 text-center">Guardar
                                    galería</button>
                                <button wire:click="updateout" class="btn btn-dark mt-3 text-center">Guardar
                                    galería y salir</button>
                            </div>
                        </div>


                        @if($errormail)
                        <div class="col-12 text-center">
                            <p class="rojo">{{$errormail}}</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createcliente" tabindex="9999" style="z-index:9999" data-bs-backdrop="static"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo cliente</h5>
                    <br />
                    <h6></h6>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="row">
                            <x-input :model="'newnombre'" :tipo="'text'" :titulo="'Nombre'" :maxlen="100"
                                :idfor="'newno'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            <x-input :model="'newapellidos'" :tipo="'text'" :titulo="'Apellidos'" :maxlen="100"
                                :idfor="'newap'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            <x-input :model="'newdni'" :tipo="'text'" :titulo="'N.I.F.'" :maxlen="15" :idfor="'newdn'"
                                :col="9" :colmd="7" :disabled="''" :change="''" />
                            <x-input :model="'newtelefono'" :tipo="'text'" :titulo="'Teléfono'" :maxlen="200"
                                :idfor="'newtel'" :col="9" :colmd="7" :disabled="''" :change="''" />
                            <x-input :model="'newemail'" :tipo="'text'" :titulo="'e-mail'" :maxlen="200"
                                :idfor="'newmail'" :col="9" :colmd="7" :disabled="''" :change="''" />

                            <x-separador />
                            <p class="rojo text-center mt-2">{{$newtext}}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="cancelarnuevocliente" type="button" class="btn btn-outline-dark"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button wire:click="crearnuevocliente" type="button" class="btn btn-primary">Crear</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="export" tabindex="9999" style="z-index:9999" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Exportar a LightRoom / Photo Mechanic
                    <h6></h6>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="row">
                            <p class="wordwrap" id="ilr">{{$lightroom}}</p>
                            <button type="button" class="copiapega btn btn-sm bg-gradient-primary float-start" onclick=""
                                data-clipboard-action="copy" data-clipboard-target="#ilr">
                                copiar</button>
                            <p class="wordwrap" id="ipm">{{$photomechanic}}</p>
                            <button type="button" class="copiapega btn btn-sm bg-gradient-primary float-start" onclick=""
                                data-clipboard-action="copy" data-clipboard-target="#ipm">
                                copiar</button>
                            <x-separador />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn  bg-gradient-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="notascliente" tabindex="9999" style="z-index:9999" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Anotaciones del cliente
                    <h6></h6>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="row">
                            <input type="text" placeholder="notas" title="notas"
                            class="form-control border border-2 p-2"
                            wire:model="notascliente">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-dark"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button data-bs-dismiss="modal" wire:click="guardaranotaciones" type="button" class="btn bg-gradient-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="export2" tabindex="9999" style="z-index:9999" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Exportar a LightRoom / Photo Mechanic
                    <h6></h6>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="row">
                            <p class="wordwrap" id="ilr2">{{$lightroom2}}</p>
                            <button type="button" class="copiapega btn btn-sm bg-gradient-primary float-start" onclick=""
                                data-clipboard-action="copy" data-clipboard-target="#ilr2">
                                copiar</button>
                            <p class="wordwrap" id="ipm2">{{$photomechanic2}}</p>
                            <button type="button" class="copiapega btn btn-sm bg-gradient-primary float-start" onclick=""
                                data-clipboard-action="copy" data-clipboard-target="#ipm2">
                                copiar</button>
                            <x-separador />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn  bg-gradient-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @if($peticiondescarga)
    <div wire:poll.5s="descargalista"></div>
    @endif
</div>

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quilloh.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill-resize-module.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill.imageCompressor.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/flatpickr.min.js"></script>
<script>
    var vselect;
    var selectedUsers;

    document.addEventListener('livewire:initialized', function() {

    var datac = @this.clientes;
    vselect=VirtualSelect.init({
  ele: '#select-cli',
  search: true,
  searchNormalize:true,
  options: JSON.parse(datac),
});
selectedUsers = document.querySelector('#select-cli');
    selectedUsers.addEventListener('change', () => {
        seleo = selectedUsers.value;
        seleo=(seleo==''?0:seleo);
        @this.setidcliente(seleo);
});

document.querySelector('#select-cli').setValue({{$ficha['cliente_id']}});
//quill.disable();
});

window.addEventListener('focusonproducto', event => {
    document.getElementById('fprod'+event.detail[0].key).scrollIntoView();
});

window.addEventListener('focusonproductosel', event => {
    document.getElementById('fprodsel'+event.detail[0].key).scrollIntoView();
});

window.addEventListener('closemodalproducto', event => { 
    $('.modalproducto').modal('hide');
});

window.addEventListener('closemodal', event => { 
    $('#createcliente').modal('hide');
});
window.addEventListener('asignarcliente', event => {
    vselect.setOptions(JSON.parse(@this.clientes));
    document.querySelector('#select-cli').setValue(event.detail[0].id);
});

var clipboard = new ClipboardJS('.copiapega');
clipboard.on('success', function (e) {
    //console.info('Action:', e.action);
    console.info('Text:', e.text);
    //console.info('Trigger:', e.trigger);
});
clipboard.on('error', function (e) {
  console.log(e);
});

window.addEventListener('borrar_postprocesado', event => { 
    Swal.fire({
        position: "center",
        icon: event.detail[0].type,
        iconColor:'#ff6f0e',
        title: event.detail[0].message,
        showConfirmButton: false,
        showCloseButton:true,
        timer: 4000
        });
});

function postprocesadogallery(){
    Swal.fire({
        position: "center",
        icon: 'info',
        iconColor:'#ff6f0e',
        //title: event.detail[0].message,
        titleText: 'Por favor, espere...',
        html: 'Estamos procesando las imágenes recibidas, espere...<br/><div class="col-12 loader text-center mb-4"></div>',
        showConfirmButton: false,
        showCloseButton:false,
        allowOutsideClick:false,
        //timer: 4000
        });
}
window.addEventListener('postprocesadogalleryrefresh', event => { 
    Swal.fire({
        position: "center",
        icon: 'info',
        iconColor:'#ff6f0e',
        //title: event.detail[0].message,
        titleText: 'Por favor, espere...',
        html: 'Estamos procesando las imágenes recibidas, espere...<br/>'+event.detail[0].message,
        showConfirmButton: false,
        showCloseButton:false,
        allowOutsideClick:false,
        //timer: 4000
        });
  });
window.addEventListener('postprocesadogalleryend', event => { 
    setTimeout(function(){
        Swal.close();
    }, 5000);
    Swal.close();
  });

  window.addEventListener('livewire-upload-progress', event => {
    alert(event.detail[0].progress);
    //@this.set( 'progress', event.detail[0].progress );
    });




</script>
@endpush