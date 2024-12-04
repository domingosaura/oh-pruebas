<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Plantillas para contratos</h5>
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
                            class="material-icons text-sm">add</i>&nbsp;&nbsp;Nueva plantilla</a>
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
                            :direction="$sortField === 'nombre' ? $sortDirection : null">Título
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
                            <x-table.cell><a href="{{ route('edit-plantillacontrato', $tag)}}">{{ $tag->nombre }}</a></x-table.cell>
                            <x-table.cell>{{ Utils::datetime($tag->created_at) }}</x-table.cell>
                            <x-table.cell>
                                <a rel="tooltip" class="btn botonoh_negro"
                                    href="{{ route('edit-plantillacontrato', $tag)}}" data-original-title=""
                                    title="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                                <button type="button" class="btn botonoh_negro" data-original-title="" title="Copia de esta plantilla"
                                    wire:click="clone({{ $tag->id }})">
                                    <i class="material-icons">content_copy</i>
                                </button>
                                <button type="button" class="btn botonoh_negro" data-original-title="" title="Eliminar"
                                    onclick="confirm('¿Seguro que desea eliminar esta plantilla?') || event.stopImmediatePropagation()"
                                    wire:click="destroy({{ $tag->id }})">
                                    <i class="material-icons">delete_forever</i>
                                </button>
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