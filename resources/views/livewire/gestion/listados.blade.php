<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">{{ $titulo }}</h5>
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


                <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative" nowireignore>
                        <ul class="nav nav-pills nav-fill p-1 " role="tablist">
                            <li></li>
                            <li class="nav-item" wire:click="seltipo(1)">
                                <a class="nav-link mb-0 px-0 py-1 {{ $tipo == 1 ? 'naranjitobold' : '' }}"
                                    data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">apps</i>
                                    <span class="ms-1">Ingresos</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="seltipo(2)">
                                <a class="nav-link mb-0 px-0 py-1 {{ $tipo == 2 ? 'naranjitobold' : '' }}"
                                    data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">apps</i>
                                    <span class="ms-1">Gastos</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="seltipo(3)">
                                <a class="nav-link mb-0 px-0 py-1 {{ $tipo == 3 ? 'naranjitobold' : '' }}"
                                    data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">web_stories</i>
                                    <span class="ms-1">Galerías pagadas</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">

                    <x-inputdate :model="'desde'" :titulo="'Listar desde'" :maxlen="''" :idfor="'xa'"
                        :col="5" :colmd="4" :disabled="''" :change="''" />
                    <x-inputdate :model="'hasta'" :titulo="'hasta'" :maxlen="''" :idfor="'xb'"
                        :col="5" :colmd="4" :disabled="''" :change="''" />
                    <div class="col-3">
                        <button wire:click="listar" class="btn btn-dark mt-4 text-center">Listar</button>
                    </div>

                    @if($tipo==3)
                    <x-separador />
                    <div class="form-check col-4">
                            <input type="checkbox" class="check-naranja" wire:change="listar" wire:model="pago1activo">&nbsp;Efectivo</input>
                    </div>
                    <div class="form-check col-4">
                            <input type="checkbox" class="check-naranja" wire:change="listar" wire:model="pago2activo">&nbsp;Transferencia bancaria</input>
                    </div>
                    <div class="form-check col-4">
                            <input type="checkbox" class="check-naranja" wire:change="listar" wire:model="pago3activo">&nbsp;Redsys (tarjeta de crédito)</input>
                    </div>
                    <div class="form-check col-4">
                            <input type="checkbox" class="check-naranja" wire:change="listar" wire:model="pago4activo">&nbsp;Paypal</input>
                    </div>
                    <div class="form-check col-4">
                            <input type="checkbox" class="check-naranja" wire:change="listar" wire:model="pago5activo">&nbsp;Stripe</input>
                    </div>
                    <div class="form-check col-4">
                            <input type="checkbox" class="check-naranja" wire:change="listar" wire:model="pago6activo">&nbsp;Bizum</input>
                    </div>
                    @endif

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
                </div>
                <x-table>
                    <x-slot name="head">
                        <x-table.heading sortable wire:click="sortBy('id')" :direction="$sortField === 'id' ? $sortDirection : null">Código
                        </x-table.heading>
                        @if ($tipo == 3)
                            <x-table.heading sortable wire:click="sortBy('tipodepago')" :direction="$sortField === 'tipodepago' ? $sortDirection : null">Forma pago
                            </x-table.heading>
                        @endif
                        <x-table.heading sortable wire:click="sortBy('documento')" :direction="$sortField === 'documento' ? $sortDirection : null">Documento
                        </x-table.heading>
                        <x-table.heading>{{ $clipro }}</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('importe')" :direction="$sortField === 'importe' ? $sortDirection : null">Importe &euro;
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('fecha')" :direction="$sortField === 'fecha' ? $sortDirection : null">Fecha
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null">
                            Fecha creación
                        </x-table.heading>
                    </x-slot>

                    <x-slot name="body">
                        @foreach ($fichas as $tag)
                            <x-table.row wire:key="row-{{ $tag->id }}">
                                <x-table.cell>{{ $tag->id }}
                                </x-table.cell>
                                @if ($tipo == 3)
                                    <x-table.cell>
                                        @if ($tag->tipodepago == 1)
                                            &nbsp;efectivo
                                        @endif
                                        @if ($tag->tipodepago == 2)
                                            &nbsp;transferencia
                                        @endif
                                        @if ($tag->tipodepago == 3)
                                            &nbsp;tarjeta
                                        @endif
                                        @if ($tag->tipodepago == 4)
                                            &nbsp;paypal
                                        @endif
                                        @if ($tag->tipodepago == 5)
                                            &nbsp;stripe
                                        @endif
                                        @if ($tag->tipodepago == 6)
                                            &nbsp;bizum
                                        @endif
                                    </x-table.cell>
                                @endif
                                <x-table.cell>{{ $tag->documento }}</x-table.cell>
                                <x-table.cell>{{ $tag->nombre }} {{ $tag->apellidos }}</x-table.cell>
                                <x-table.cell style="text-align:right">{{ $tag->importe }}</x-table.cell>
                                <x-table.cell>{{ Utils::fechaEsp($tag->fecha) }}</x-table.cell>
                                <x-table.cell>{{ Utils::datetime($tag->created_at) }}</x-table.cell>
                            </x-table.row>
                        @endforeach
                    </x-slot>
                </x-table>
                <div id="datatable-bottom">
                    {{ $fichas->links() }}
                </div>
                <div class="col-12 text-center">
                    <button wire:click="exportar(1)" class="btn btn-dark mt-3 text-center">Exportar XLS</button>
                    <button wire:click="exportar(2)" class="btn btn-dark mt-3 text-center">Exportar XLS
                        detallado</button>
                </div>
            </div>

            @push('js')
                <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
            @endpush
