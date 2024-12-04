<div class="container-fluid py-4">
    <div class="row xxmt-4">

        <div class="col-6 text-start">
            <h5 class="mb-0">Galerías</h5>
        </div>
        <div class="col-6 text-end">
            <a class="btn bg-gradient-dark mb-0 me-4" wire:click="nuevoregistro"><i
                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Nueva</a>
        </div>

        <div class="col-12">
            @if (Session::has('status'))
            <div class="alert alert-success-oh alert-dismissible text-white mx-4" role="alert">
                <span class="text-sm">{{ Session::get('status') }}</span>
                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="d-flex flex-row justify-content-between mx-4">
                <div class="col-8 col-md-8">
                    <div class="nav-wrapper position-relative end-0" nowireignore>
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li></li>
                            <li class="nav-item" wire:click="vseccion(1)">
                                <a class="nav-link mb-0 px-0 py-1 {{$soloactivos==1?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                    role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">check</i>
                                    <span class="ms-1">Activas</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion(2)">
                                <a class="nav-link mb-0 px-0 py-1 {{$soloactivos==2?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                    aria-selected="false">
                                    <i class="material-icons text-lg position-relative">close</i>
                                    <span class="ms-1">Archivadas</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion(3)">
                                <a class="nav-link mb-0 px-0 py-1 {{$soloactivos==3?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                    aria-selected="false">
                                    <i class="material-icons text-lg position-relative">delete_forever</i>
                                    <span class="ms-1">Papelera</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if($soloactivos==1)
                    <div class="nav-wrapper position-relative end-0 mt-1" nowireignore>
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li></li>
                            <li class="nav-item" wire:click="vseccion2(1)">
                                <a class="nav-link mb-0 px-0 py-1 {{$state==1?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                    role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">apps</i>
                                    <span class="ms-1">Todas</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion2(2)">
                                <a class="nav-link mb-0 px-0 py-1 {{$state==2?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                    role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">check_box</i>
                                    <span class="ms-1">Seleccionadas</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion2(3)">
                                <a class="nav-link mb-0 px-0 py-1 {{$state==3?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                    role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">check_box_outline_blank</i>
                                    <span class="ms-1">Pte. selección</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion2(4)">
                                <a class="nav-link mb-0 px-0 py-1 {{$state==4?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                    role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">euro</i>
                                    <span class="ms-1">Pagadas</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion2(5)">
                                <a class="nav-link mb-0 px-0 py-1 {{$state==5?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                    role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative {{$state==5?'naranjitobold':'gris'}}">euro</i>
                                    <span class="ms-1">Pte. pago</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endif


                </div>
                <div class="mt-3 ">
                    <input id="ibus" wire:model.live="search" type="text"
                        class="form-control fondoblanco border border-2 p-2" placeholder="Buscar...">
                </div>

            </div>


        </div>


        @foreach ($fichas as $key=>$tag)
        <div class="col-xl-3 col-md-6 mb-2 mt-4">
            <div class="card Xcard-blog Xcard-plain">
                <div class="card-header p-0 mt-2 mx-3">
                    <a class="d-block shadow-xl border-radius-xl" href="{{ route('edit-galeria', $tag)}}">
                        <img src="
                        @if (strlen($tag->binariomin) == 0) 
                            /oh/img/gallery-generic.jpg
                        @else
                            {{ $tag->binariomin }}
                        @endif
                        "class="img-fluid shadow border-radius-xl">
            

                    </a>
                </div>
                <div class="card-body p-3">
                    <h5>
                        <a href="{{ route('edit-galeria', $tag)}}">
                            {{ $tag->nombreinterno }}&nbsp;
                            <p>{{ $tag->nombre }}<br/>
                            {{ $tag->nomcli }} {{ $tag->apecli }}&nbsp;</p>
                        </a>
                    </h5>
                    <div class="xd-flex align-items-center float-start">


                        <i class="material-icons {{ $tag->enviado==1?'negro':'gris' }}" title="{{ $tag->enviado==1?'galería enviada a cliente':'galería no enviada a cliente' }}">
                            {{$tag->enviado==1?'mark_email_read':'unsubscribe' }}</i>
                        <i class="material-icons {{ $tag->seleccionconfirmada==1?'negro':'gris' }}" title="{{ $tag->seleccionconfirmada==1?'selección de fotos confirmada por el cliente':'el cliente no ha confirmado aún la selección de fotos' }}">
                            {{$tag->seleccionconfirmada==1?'favorite':'favorite' }}</i>
                        <i class="material-icons {{ $tag->pagado==1?'negro':'gris' }}" title="{{ $tag->pagado==1?'galería pagada':'galería pendiente de pago' }}">
                            {{$tag->pagado==1?'euro_symbol':'euro_symbol' }}</i>
                            
                            <!--<i class="material-icons negro puntero" 
                            onclick="$('#staticBackdrop').modal('show');";
                            wire : click="downloadgallery({ { $tag->id} })" title="descargar galería" wire:ignore>vertical_align_bottom</i>-->
                            
                            <i class="material-icons negro {{ $tag->descargada==1?'negro':'gris' }}" 
                            onclick="$('#staticBackdrop').modal('show');";
                            title="{{ $tag->descargada==1?'el cliente ha descargado la galería':'el cliente no ha descargado la galería' }}">vertical_align_bottom</i>
                            
                            
                        </div>
                        <div class="xd-flex align-items-center float-end">
                            @if($tag->cliente_id>0)
                            <i class="material-icons negro puntero" 
                            onclick="confirm('Enviar enlace de la galería al cliente?.') || event.stopImmediatePropagation()"
                            wire:click="sendclient({{ $tag->id }},{{ $tag->cliente_id }})" title="enviar enlace de la galería por mail">mail</i>
                            <i class="material-icons negro puntero" 
                            onclick="confirm('Enviar enlace de la galería al cliente?.') || event.stopImmediatePropagation()"
                            wire:click="sendclientwhatsapp({{ $tag->id }},{{ $tag->cliente_id }})" title="enviar enlace de la galería por Whatsapp">maps_ugc</i>
                            @endif
                            @if($soloactivos==1)
                            <i class="material-icons negro puntero" wire:click="archivar({{$tag->id}})" title="archivar galería">archive</i>
                            @endif
                            @if($soloactivos==2)
                            <i class="material-icons negro puntero" wire:click="desarchivar({{$tag->id}})" title="desarchivar galería">archive</i>
                            @endif
                            @if($soloactivos==3)
                            <i class="material-icons negro puntero" wire:click="recover({{$tag->id}})" title="recuperar galería">archive</i>
                            @endif


                            @if($tag->eliminada==1)
                                <i class="material-icons negro puntero" 
                                onclick="confirm('¿Eliminar esta galería? Se perderán todos los datos de la misma.') || event.stopImmediatePropagation()"
                                wire:click="destroy({{ $tag->id }})" title="eliminar definitivamente">delete_forever</i>
                            @endif
                            @if($tag->eliminada==-11)
                                <i class="material-icons negro puntero" 
                                onclick="confirm('¿Mover esta galería a la papelera? Se podrá recuperar durante 7 días.') || event.stopImmediatePropagation()"
                                wire:click="totrash({{ $tag->id }})" title="mover a la papelera">delete_forever</i>
                            @endif
                            @if($tag->eliminada==0)
                                <i class="material-icons negro puntero" 
                                wire:click="totrash({{ $tag->id }})" title="mover a la papelera">delete_forever</i>
                            @endif
                            <i class="material-icons negro puntero" 
                            title="tamaño en disco: {{ round($tag->sizeg,2) }} Mb.">perm_media</i>


                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @if($errormail)
        <div class="col-12 text-center">
            <p class="rojo">{{$errormail}}</p>
        </div>
        @endif

    </div>
</div>

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script>
</script>
@endpush