<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">{{$titulo}}</h5>
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
                            class="material-icons text-sm">add</i>&nbsp;&nbsp;Nuevo {{$ruta}}</a>
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
                        <x-table.heading sortable wire:click="sortBy('documento')"
                            :direction="$sortField === 'documento' ? $sortDirection : null">Documento
                        </x-table.heading>
                        <x-table.heading>Clipro
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('importe')"
                            :direction="$sortField === 'importe' ? $sortDirection : null">Importe &euro;
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('fecha')"
                            :direction="$sortField === 'fecha' ? $sortDirection : null">Fecha
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('created_at')"
                            :direction="$sortField === 'created_at' ? $sortDirection : null">
                            Fecha creación
                        </x-table.heading>
                        <x-table.heading>Acciones</x-table.heading>
                    </x-slot>

                    <x-slot name="body">
                        @foreach ($fichas as $tag)
                        <x-table.row wire:key="row-{{ $tag->id }}">
                            <x-table.cell>{{ $tag->id }}</x-table.cell>
                            <x-table.cell>{{ $tag->documento }}</x-table.cell>
                            <x-table.cell>{{ $tag->nombre }} {{ $tag->apellidos }}</x-table.cell>
                            <x-table.cell style="text-align:right">{{ $tag->importe }}</x-table.cell>
                            <x-table.cell>{{ Utils::fechaEsp($tag->fecha) }}</x-table.cell>
                            <x-table.cell>{{ Utils::datetime($tag->created_at) }}</x-table.cell>
                            <x-table.cell>
                                <a rel="tooltip" class="btn botonoh_negro"
                                    href="{{ route('edit-'.$ruta, $tag)}}" data-original-title=""
                                    title="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                                @if(1==2)
                                <button type="button" class="btn botonoh_rojo" data-original-title="" title=""
                                    onclick="confirm('¿Seguro que desea eliminar este {{$ruta}}?') || event.stopImmediatePropagation()"
                                    wire:click="destroy({{ $tag->id }})">
                                    <i class="material-icons">delete_forever</i>
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
</div>

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>

@endpush