<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">{{$plural}}</h5>
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
                    <a class="btn bg-gradient-dark mb-0 me-4" href="{{ route('add-'.$singular) }}"><i
                        class="material-icons text-sm">add</i>&nbsp;&nbsp;Nuevo {{$singular}}</a>
                </div>


                <div class="d-flex flex-row justify-content-between mx-4">
                    <div class="col-6">
                        <div class="nav-wrapper position-relative end-0" nowireignore>
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li></li>
                                <li class="nav-item" wire:click="vseccion(1)">
                                    <a class="nav-link mb-0 px-0 py-1 {{$quever==1?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                                        aria-selected="false">
                                        <i class="material-icons text-lg position-relative">how_to_reg</i>
                                        <span class="ms-1">Todos</span>
                                    </a>
                                </li>
                                <li class="nav-item" wire:click="vseccion(2)">
                                    <a class="nav-link mb-0 px-0 py-1 {{$quever==2?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                                        role="tab" aria-selected="false">
                                        <i class="material-icons text-lg position-relative">check</i>
                                        <span class="ms-1">Activos</span>
                                    </a>
                                </li>
                                <li class="nav-item" wire:click="vseccion(3)">
                                    <a class="nav-link mb-0 px-0 py-1 {{$quever==3?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
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
    

                <div class="d-flex flex-row justify-content-between mx-4">
                    <div class="d-flex mt-3 align-items-center justify-content-center">
                        <p class="text-secondary pt-2">Mostrar&nbsp;&nbsp;</p>
                        <select wire:model.live="perPage" class="form-control mb-2" id="entries">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option selected value="25">25</option>
                        </select>
                        <p class="text-secondary pt-2">&nbsp;registros</p>
                    </div>
                    <div class="mt-3 ">
                        <input wire:model.live="search" type="text" class="form-control" placeholder="Buscar...">
                    </div>
                </div>
                <x-table>
                    <x-slot name="head">
                        <x-table.heading sortable wire:click="sortBy('id')"
                            :direction="$sortField === 'id' ? $sortDirection : null">Código
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('nombre')"
                            :direction="$sortField === 'nombre' ? $sortDirection : null">Nombre
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('apellidos')"
                            :direction="$sortField === 'apellidos' ? $sortDirection : null">Apellidos
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('created_at')"
                            :direction="$sortField === 'created_at' ? $sortDirection : null">
                            Fecha creación
                        </x-table.heading>
                        <x-table.heading>Acciones</x-table.heading>
                    </x-slot>

                    <x-slot name="body">
                        @foreach ($clientes as $tag)
                        <x-table.row wire:key="row-{{ $tag->id }}">
                            <x-table.cell>{{ $tag->id }}</x-table.cell>
                            <x-table.cell><a href="{{ route('edit-'.$singular, $tag)}}">{{ $tag->nombre }}</a></x-table.cell>
                            <x-table.cell><a href="{{ route('edit-'.$singular, $tag)}}">{{ $tag->apellidos }}</a></x-table.cell>
                            <x-table.cell>{{ Utils::datetime($tag->created_at) }}</x-table.cell>
                            <x-table.cell>
                                <a rel="tooltip" class="btn botonoh_negro"
                                    href="{{ route('edit-'.$singular, $tag)}}" data-original-title=""
                                    title="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                                <button type="button" class="btn botonoh_negro" data-original-title="" title="Eliminar"
                                    onclick="confirm('¿Seguro que desea eliminar este {{$singular}}?') || event.stopImmediatePropagation()"
                                    wire:click="destroy({{ $tag->id }})">
                                    <i class="material-icons">delete_forever</i>
                                </button>
                            </x-table.cell>
                        </x-table.row>
                        @endforeach
                    </x-slot>
                </x-table>
                <div id="datatable-bottom">
                    {{ $clientes->links() }}
                </div>
</div>

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>

@endpush