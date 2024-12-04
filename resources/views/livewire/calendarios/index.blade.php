<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-6 text-start">
            <h5 class="mb-0">Mis calendarios</h5>
        </div>
        <div class="col-6 text-end">
        <!--<a class="btn bg-gradient-dark mb-0 me-4" href="{ { route('add-calendario') } }"><i
                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Nuevo calendario</a>-->

            <a class="btn bg-gradient-dark mb-0 me-4" wire:click="nuevoregistro"><i
                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Nuevo</a>



                </div>
        <div class="row mt-4 mb-4">
            <div class="col-6 col-md-9">
                <div class="col-12">
                    <div class="nav-wrapper position-relative end-0" nowireignore>
                        <ul class="nav nav-pills nav-fill p-1 " role="tablist">
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
                                    <span class="ms-1">Inactivos</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion(3)">
                                <a class="nav-link mb-0 px-0 py-1 {{$soloactivos==3?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                    aria-selected="false">
                                    <i class="material-icons text-lg position-relative">apps</i>
                                    <span class="ms-1">Todos</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-6 col-md-3">
                    <input id="ibus" wire:model.live="search" type="text"
                        class="form-control fondoblanco border border-2 p-2" placeholder="Buscar...">
            </div>
        </div>

        @if (Session::has('status'))
        <div class="col-12">
            <div class="alert alert-success-oh alert-dismissible text-white mx-4" role="alert">
                <span class="text-sm">{{ Session::get('status') }}</span>
                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif

        @foreach ($datos as $tag)
        <div class="col-xl-3 col-md-6 mb-2 mb-4">
            <div class="card Xcard-blog Xcard-plain">
                <div class="card-header p-0 mt-2 mx-3">
                    <a class="d-block shadow-xl border-radius-xl" href="{{ route('edit-calendario', $tag)}}">
                        


                        <img src=
                        @if (strlen($tag['binario']) == 0) 
                            "/oh/img/gallery-generic.jpg"
                        @else
                            @if(Session('soporteavif'))
                                "data:image/jpeg;base64,{{ $tag['binario'] }}"
                            @else
                                "{{ Utils::inMacBase64($tag['binario']) }}"
                            @endif
                        @endif
                        class="img-fluid shadow border-radius-xl">

                </a>
                </div>
                <div class="card-body p-3">
                    <h5>
                        <a href="{{ route('edit-calendario', $tag)}}">
                            {{ $tag->nombre }}&nbsp;
                        </a>
                    </h5>
                    <p class="mb-4 text-sm" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;">
                        {{ $tag->descripcion }}&nbsp;
                    </p>
                    <div class="xd-flex align-items-center float-start">
                        <i class="material-icons {{$tag->activo==1?'negro':'gris'}}" 
                        title="{{$tag->activo==1?'activo':'inactivo'}}">{{$tag->activo==1?'task_alt':'hide_source'}}</i>
                        @if($tag->activo==1)
                        <i class="material-icons negro puntero" data-bs-toggle="modal" data-bs-target="#pedircita"
                        title="enviar enlace a cliente para reservar" wire:click="setcalendar({{$tag->id}})">mail</i>
                        <i class="material-icons negro puntero" data-bs-toggle="modal" data-bs-target="#pedircita"
                        title="enviar enlace a cliente para reservar" wire:click="setcalendar({{$tag->id}})">maps_ugc</i>
                        @endif
                    </div>
                    <div class="xd-flex align-items-center float-end">
                        <a rel="tooltip" class="" href="{{ route('edit-calendario', $tag)}}"
                            data-original-title="" title="Editar">
                            <i class="material-icons negro puntero">edit</i>
                        </a>
                        <i class="material-icons negro puntero" 
                        onclick="confirm('¿Eliminar este calendario? Se perderán todos los datos de citas.') || event.stopImmediatePropagation()"
                        wire:click="destroy({{ $tag->id }})" title="eliminar definitivamente">delete_forever</i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @include('livewire.calendarios.modalpedircita')

</div>

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script>
    var clipboard = new ClipboardJS('.copiapega');
    clipboard.on('success', function (e) {
        //console.info('Action:', e.action);
        //console.info('Text:', e.text);
        //console.info('Trigger:', e.trigger);
        Swal.fire({
            position: "center",
            icon: 'success',
            iconColor:'#ff6f0e',
            title:"copiado al portapapeles",
            showConfirmButton: false,
            showCloseButton:true,
            timer: 4000
            });
    });
</script>

@endpush