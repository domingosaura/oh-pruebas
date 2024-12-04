<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Importar clientes</h5>
                    <div class="row">
                        <div class="col-8 text-center">
                            @if ($filename)
                                <p class="naranjito negrita">Se ha cargado un archivo</p>
                            @else
                                <p>No se ha cargado ningún archivo</p>
                            @endif
                            <p>La primera fila del archivo debe contener los nombres de las columnas.</p>
                            <p>Solo se muestran en pantalla los primeros registros del archivo.</p>
                            <p><strong>No se importan clientes sin email</strong></p>
                            <p>SEA PACIENTE NO CAMBIE DE PANTALLA HASTA QUE EL PROCESO TERMINE</p>
                            <x-filepond wire:model="files" maxsize="10MB" resize="false" width="425" height="283"
                                varname="fi1" w5sec="true" />
                        </div>
                        <div class="col-4 text-end">
                            <a class="btn bg-gradient-dark mb-0 me-4" wire:click="importar"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Importar datos</a>
                        </div>
                    </div>
                </div>



                <x-table2>
                    <x-slot name="head">
                        @foreach ($columns as $key => $col)
                            <x-table.heading>{{ $col['nombre'] }}
                                <select class="form-select" aria-label="ddss" id="dsas{{ $key }}"
                                    wire:model="columns.{{ $key }}.val" wire:change="revisar" wire:ignore>
                                    @foreach ($colimport as $col2)
                                        <option value="{{ $col2['val'] }}">{{ $col2['titulo'] }}</option>
                                    @endforeach
                                </select>

                            </x-table.heading>
                        @endforeach
                    </x-slot>

                    <x-slot name="body">
                        @foreach ($data as $key => $tag)
                            @if ($key > 100)
                            @break
                        @endif
                        <x-table.row>
                            @foreach ($tag as $tagdata)
                                <x-table.cell>{{ $tagdata }}</x-table.cell>
                            @endforeach
                        </x-table.row>
                    @endforeach
                </x-slot>
            </x-table2>
            <div id="datatable-bottom">
            </div>
        </div>
    </div>
</div>
</div>

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>

<script>
    document.addEventListener('livewire:initialized', function() {
        Livewire.hook('morph.added', ({
            el
        }) => {
            //alert("a");
            $('#staticBackdrop').modal('hide');
        });
    });
</script>
@endpush
