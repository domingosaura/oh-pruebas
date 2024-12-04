<div class="container-fluid py-4">
    <div class="row xxmt-4">

        <div class="col-6 text-start">
            <h5 class="mb-0">Packs para sesiones</h5>
        </div>
        <div class="col-6 text-end">
            <a class="btn bg-gradient-dark mb-0 me-4" wire:click="nuevoregistro"><i
                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Nuevo</a>
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
                <div class="col-6">
                    <div class="nav-wrapper position-relative end-0" nowireignore>
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li></li>
                            <li class="nav-item" wire:click="vseccion(1)">
                                <a class="nav-link mb-0 px-0 py-1 {{$soloactivos==1?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                    role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">check</i>
                                    <span class="ms-1">Activos</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion(2)">
                                <a class="nav-link mb-0 px-0 py-1 {{$soloactivos==2?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                    aria-selected="false">
                                    <i class="material-icons text-lg position-relative">close</i>
                                    <span class="ms-1">Archivados</span>
                                </a>
                            </li>
                        </ul>
                    </div>
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
                    <a class="d-block shadow-xl border-radius-xl" href="{{ route('edit-packs', $tag)}}">
                        <img src=
                        @if (strlen($tag->binario) == 0) 
                            "/oh/img/gallery-generic.jpg"
                        @else
                            @if(Session('soporteavif'))
                                "data:image/jpeg;base64,{{ $tag->binario }}"
                            @else
                                "{{ Utils::inMacBase64($tag->binario) }}"
                            @endif
                        @endif
                        class="img-fluid shadow border-radius-xl">


                        </a>
                </div>
                <div class="card-body p-3">
                    <h5>
                        <a href="{{ route('edit-packs', $tag)}}">
                            {{ $tag->nombre }}
                        </a>
                    </h5>
                    <div class="xd-flex align-items-center float-start">
                        <i class="material-icons negro puntero" wire:click="clone({{$tag->id}})" title="copia de este pack">content_copy</i>
                    </div>
                    <div class="xd-flex align-items-center float-end">

                        @if($soloactivos==1)
                        <i class="material-icons negro puntero" wire:click="archivar({{$tag->id}})"
                            title="archivar sesión">archive</i>
                        @endif
                        @if($soloactivos==2)
                        <i class="material-icons negro puntero" wire:click="desarchivar({{$tag->id}})"
                            title="desarchivar sesión">archive</i>
                        @endif
                        <i class="material-icons negro puntero"
                            onclick="confirm('¿Eliminar este pack? Se perderán todos los datos del pack.') || event.stopImmediatePropagation()"
                            wire:click="destroy({{ $tag->id }})" title="eliminar definitivamente">delete_forever</i>

                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script>
</script>
@endpush