<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Contratos</h5>
                </div>
                @if (Session::has('status'))
                <div class="alert alert-success-oh alert-dismissible text-white mx-4" role="alert">
                    <span class="text-sm">{{ Session::get('status') }}</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="col-12 text-end">
                    <a class="btn bg-gradient-dark mb-0 me-4" wire:click="nuevoregistro"><i
                            class="material-icons text-sm">add</i>&nbsp;&nbsp;Nuevo contrato</a>
                </div>


                <div class="d-flex flex-row justify-content-between mx-4">


                    <div class="col-6">
                        <div class="nav-wrapper position-relative end-0" nowireignore>
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li></li>
                                <li class="nav-item" wire:click="vseccion(1)">
                                    <a class="nav-link mb-0 px-0 py-1 {{$solopendiente==1?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                        role="tab" aria-selected="false">
                                        <i class="material-icons text-lg position-relative">how_to_reg</i>
                                        <span class="ms-1">Todos</span>
                                    </a>
                                </li>
                                <li class="nav-item" wire:click="vseccion(2)">
                                    <a class="nav-link mb-0 px-0 py-1 {{$solopendiente==2?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                        role="tab" aria-selected="false">
                                        <i class="material-icons text-lg position-relative">close</i>
                                        <span class="ms-1">Pendientes de firma</span>
                                    </a>
                                </li>
                                <li class="nav-item" wire:click="vseccion(3)">
                                    <a class="nav-link mb-0 px-0 py-1 {{$solopendiente==3?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                        role="tab" aria-selected="false">
                                        <i class="material-icons text-lg position-relative">check</i>
                                        <span class="ms-1">Firmados</span>
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

                <x-table>
                    <x-slot name="head">
                        <x-table.heading sortable wire:click="sortBy('nombre')"
                            :direction="$sortField === 'nombre' ? $sortDirection : null">Título
                        </x-table.heading>
                        <x-table.heading>Cliente</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('enviado')"
                            :direction="$sortField === 'enviado' ? $sortDirection : null">Enviado
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('firmado')"
                            :direction="$sortField === 'firmado' ? $sortDirection : null">Firmado
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('created_at')"
                            :direction="$sortField === 'created_at' ? $sortDirection : null">
                            Fecha creación
                        </x-table.heading>
                        <x-table.heading>Acciones</x-table.heading>
                    </x-slot>

                    <x-slot name="body">
                        @foreach ($fichas as $key=>$tag)
                        <x-table.row wire:key="row-{{ $tag->id }}">
                            <x-table.cell><a href="{{ route('edit-clientecontrato', $tag)}}">{{ $tag->nombre }}</a></x-table.cell>
                            <x-table.cell><a href="{{ route('edit-clientecontrato', $tag)}}">{{ $tag->nomcliente }}</a></x-table.cell>
                            <x-table.cell> <i class="material-icons {{ $tag->enviado==1?'negro':'gris' }}">{{
                                    $tag->enviado==1?'mail':'unsubscribe' }}</i>
                            </x-table.cell>
                            <x-table.cell> <i class="material-icons {{ $tag->firmado==1?'negro':'gris' }}">{{
                                    $tag->firmado==1?'check':'close' }}</i>
                            </x-table.cell>
                            <x-table.cell>{{ Utils::datetime($tag->created_at) }}</x-table.cell>
                            <x-table.cell>
                                <a rel="tooltip" class="btn botonoh_negro"
                                    href="{{ route('edit-clientecontrato', $tag)}}" data-original-title=""
                                    title="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                                <button type="button" class="btn botonoh_negro" data-original-title="" title="Eliminar"
                                    onclick="confirm('¿Seguro que desea eliminar este contrato?') || event.stopImmediatePropagation()"
                                    wire:click="destroy({{ $tag->id }})">
                                    <i class="material-icons">delete_forever</i>
                                </button>
                                @if($tag->firmado==0)
                                <button type="button" class="btn botonoh_negro" data-original-title=""
                                    title="Enviar mail para firmar"
                                    onclick="confirm('¿Seguro que desea enviar al cliente?') || event.stopImmediatePropagation()"
                                    wire:click="sendclient({{ $tag->id }})">
                                    <i class="material-icons">mail</i>
                                </button>
                                @if($tag->telefono||1==1)
                                <button type="button" class="btn botonoh_negro" data-original-title=""
                                    title="Enviar Whatsapp para firmar"
                                    onclick="confirm('¿Seguro que desea enviar al cliente?') || event.stopImmediatePropagation()"
                                    wire:click="sendclientwhatsapp({{ $tag->id }})"><i class="material-icons">maps_ugc</i></button>

                                @endif
                                @endif
                                </button>
                                @if($tag->firmado==1)
                                <button type="button" class="btn botonoh_negro" data-original-title=""
                                    title="Descargar PDF" wire:click="seepdf({{ $tag->id }})">
                                    <i class="material-icons">vertical_align_bottom</i>
                                </button>
                                <button type="button" class="btn botonoh_negro" data-original-title="" title="Ver PDF"
                                    wire:click="seepdfinline({{ $tag->id }})">
                                    <i class="material-icons">visibility</i>
                                </button>
                                @endif
                            </x-table.cell>
                        </x-table.row>
                        @endforeach
                    </x-slot>
                </x-table>
                <div id="datatable-bottom">
                    {{ $fichas->links() }}
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




@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>

<script>



</script>


@endpush