<div class="modal fade seleccionfotos" id="seleccionfotos{{ $key }}" tabindex="9999" style="z-index:9999"
    data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-start">Marque {{ $tag['numfotos'] }} fotografías para este producto<br/><p>{{ $tag['nombre'] }}</p></h5>
                <h6>{{ $galeriabiscount }} fotografías seleccionadas</h6>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    @if ($productoseleccionid == $key)
                        <div class="row">
                            <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                                <div class="nav-wrapper position-relative mb-2" id="dsfdsf{{ $key }}"
                                    nowireignore>
                                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                        <li></li>
                                        <li class="nav-item" wire:click="mostrarsolofotosproductos(1)">
                                            <a class="nav-link mb-0 px-0 py-1 active {{ $versolofotosproductos == 1 ? 'naranjitobold' : '' }}"
                                                data-bs-toggle="tab" href="" role="tab" aria-selected="true">
                                                <i class="material-icons text-lg position-relative">apps</i>
                                                <span class="ms-1">Todas</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" wire:click="mostrarsolofotosproductos(2)">
                                            <a class="nav-link mb-0 px-0 py-1 {{ $versolofotosproductos == 2 ? 'naranjitobold' : '' }}"
                                                data-bs-toggle="tab" href="" role="tab"
                                                aria-selected="false">
                                                <i class="material-icons text-lg position-relative">favorite</i>
                                                <span class="ms-1">Seleccionadas</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" wire:click="mostrarsolofotosproductos(3)">
                                            <a class="nav-link mb-0 px-0 py-1 {{ $versolofotosproductos == 3 ? 'naranjitobold' : '' }}"
                                                data-bs-toggle="tab" href="" role="tab"
                                                aria-selected="false">
                                                <i class="material-icons text-lg position-relative">close</i>
                                                <span class="ms-1">No seleccionadas</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" wire:click="mostrarsolofotosproductos(4)">
                                            <a class="nav-link mb-0 px-0 py-1  {{ $versolofotosproductossolonotas ? 'naranjitobold' : '' }}"
                                                data-bs-toggle="tab" href="" role="tab"
                                                aria-selected="false">
                                                <i class="material-icons text-lg position-relative">chat</i>
                                                <span class="ms-1">Con anotaciones</span>
                                            </a>
                                        </li>

                                        @if ($origen == 'galeria')
                                            <li class="nav-item" wire:click="">
                                                <div class="dropdown">
                                                    <a class="nav-link mb-0 px-0 py-1 puntero dropdown-toggle"data-bs-toggle="dropdown"
                                                        href="" role="tab" aria-selected="false"
                                                        title="descargar la galería">
                                                        <i
                                                            class="material-icons text-lg position-relative">vertical_align_bottom</i>
                                                        <span class="ms-1">Descargar</span>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                onclick="$('#staticBackdrop').modal('show');"
                                                                wire:click="descargarproducto({{ $key }},1)">Todas
                                                                las fotografías</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                onclick="$('#staticBackdrop').modal('show');"
                                                                wire:click="descargarproducto({{ $key }},2)">Solo
                                                                seleccionadas</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item puntero">
                                                <a class="nav-link mb-0 px-0 py-1 puntero" data-bs-toggle="tab"
                                                    href="#" role="tab" aria-selected="false">
                                                    <span class="ms-1 naranjito" data-bs-target="#export2"
                                                        data-bs-toggle="modal">Lightroom/Photo Mechanic</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            @foreach ($galeriabis as $key2 => $tag2)
                                <?php
                                $print = true;
                                if ($tag['fotosdesde'] == 2 && $tag2['selected'] == false) {
                                    $print = false;
                                }
                                if ($tag['fotosdesde'] == 3 && $tag2['selected'] == true) {
                                    $print = false;
                                }
                                if ($versolofotosproductos == 2 && $tag2['selectedprod'] == false) {
                                    $print = false;
                                }
                                if ($versolofotosproductos == 3 && $tag2['selectedprod'] == true) {
                                    $print = false;
                                }
                                if ($versolofotosproductossolonotas == true && strlen($tag2['notas']) == 0) {
                                    $print = false;
                                }
                                ?>
                                @if ($print)
                                    <div class="col-12 col-md-4 mb-3"
                                        id="tata_{{ $key }}_{{ $key2 }}">
                                        <div class="card Xcard-blog Xcard-plain">
                                            <div class="card-header p-0 mt-2 mx-3">
                                                
                                                
                                                <img src=
                                                @if(Session('soporteavif'))
                                                "{{ Storage::url('tmpgallery/'.$tag2['file']) }}"
                                                @else
                                                "{{ Utils::inMacGallery($tag2['file']) }}"
                                                @endif
                                                    class="img-fluid shadow border-radius-xl"
                                                    title="{{ $tag2['nombre'] }}">


                                            </div>


                                            <div class="card-body p-3">
                                                <div class="d-flex xxalign-items-center text-center">
                                                    <i class="material-icons puntero {{ $tag2['selectedprod'] ? 'rojo' : 'gris' }}"
                                                        title="{{ $tag2['selectedprod'] ? 'seleccionada' : 'no seleccionada' }}"
                                                        wire:click="marcarimagenproducto({{ $key2 }})">
                                                        {{ $tag2['selectedprod'] ? 'favorite' : 'favorite' }}</i>&nbsp;&nbsp;&nbsp;



                                                        @if(!$seleccionconfirmada && !$pagado && !$pagadomanual)
                                                        <button type="button" class="btn botonoh_negro text-center" data-original-title=""
                                                        title="" wire:click="cantiimagenesproducto({{$key2}},1)">
                                                        <i class="material-icons">remove</i>
                                                    </button>
                                                    &nbsp;&nbsp;<h5>{{$galeriabis[$key2]['cantidad']}}</h5>&nbsp;&nbsp;
                                                        <button type="button" class="btn botonoh_negro text-center" data-original-title=""
                                                        title="" wire:click="cantiimagenesproducto({{$key2}},2)">
                                                        <i class="material-icons">add</i>
                                                        </button>
                                                        @else
                                                        <h5>{{$galeriabis[$key2]['cantidad']}}</h5>
                                                        @endif


                                                </div>
                                                @if ($tag2['selectedprod'])
                                                    <div class="col-12 text-start">
                                                        <i class="material-icons">edit_note</i>
                                                        <input type="text" placeholder="notas" title="notas"
                                                            class="inputnosquare" style="width:90%"
                                                            wire:model="galeriabis.{{ $key2 }}.notas">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer xxxtext-center">
                <div class="col-12">
                    <button type="button" class="btn btn-outline-dark" wire:click="cancelarseleccionfotoproducto"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button wire:click="guardarseleccionfotoproducto({{ $key }})" type="button"
                        class="btn btn-primary blanco">Confirmar</button>
                </div>
                <div class="col-12">
                    <h6>
                        {{ $galeriabiscount }} seleccionadas de {{ $tag['numfotos'] }} necesarias&nbsp;&nbsp;
                        @if ($tag['numfotosadicionales']>0)
                        (puede seleccionar hasta {{$tag['numfotosadicionales']}} fotografías adicionales)
                        @endif
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>
