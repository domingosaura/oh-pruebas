<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar {{$ruta}}</h5>
                    <p>ATENTO, TODOS LOS MOVIMIENTOS SE GRABAN AUTOMÁTICAMENTE</p>
                </div>
                <div class="col-12 text-end">
                    <a class="btn bg-gradient-dark mb-0 me-4" wire:click="update(1)"
                        href="{{ route($ruta.'-management') }}">volver</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <x-input :tipo="'name'" :col="9" :colmd="4" :idfor="'idoc'" :model="'ficha.documento'"
                            :titulo="'Documento'" :disabled="''" :maxlen="''" :change="'update'" />
                        <x-inputdate :model="'ficha.fecha'" :titulo="'Fecha'" :maxlen="''" :idfor="'iniodt'" :col="6"
                            :colmd="4" :disabled="''" :change="'update'" />
                        <x-input :tipo="'number'" :col="6" :colmd="3" :idfor="'iimpo'" :model="'ficha.importe'"
                            :titulo="'Importe'" :disabled="'disabled'" :maxlen="''" :change="''" />
                    </div>

                    <div style="z-index: 999;z-index: 998;" class="form-group col-auto mb-4">
                        <div class="row">
                            <label for="select-cli">{{$clipro}}
                                @if($ficha['cliente_id']>0)
                                &nbsp;
                                <a href="{{ route('edit-cliente', $ficha['cliente_id'])}}">
                                <i class="material-icons negro puntero" title="ficha">border_color</i>
                                </a>
                                @endif
                            </label>
                            <div {{$ficha['firmado']?'disabled':''}} style="z-index: 999;" class="aform-control"
                                id="select-cli" placeholder="{{$clipro}}" value="{{$ficha['cliente_id']}}"
                                wire:ignore>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>





    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <div class="col-6 float-start">
                        <h5 class="mb-0">desglose</h5>
                    </div>
                    <div class="col-6 text-end float-end">
                        <a class="btn bg-gradient-dark mb-0 me-4" wire:click="linea">Nueva línea</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">




                        <x-table>
                            <x-slot name="head">
                                <x-table.heading>Descripción</x-table.heading>
                                <x-table.heading>Base imponible</x-table.heading>
                                <x-table.heading>Impuesto</x-table.heading>
                                <x-table.heading>Acciones</x-table.heading>
                            </x-slot>

                            <x-slot name="body">
                                @foreach ($desglose as $key=>$tag)
                                <x-table.row wire:key="row-{{ $tag->id }}">
                                    <x-table.cell>
                                        <input type="text" wire:model="desglose.{{ $key }}.descripcion"
                                            wire:change="updatelinea({{ $key }})"
                                            class="form-control border border-2 p-2" id="idoc{{ $tag->id }}"
                                            maxlength="70" />
                                    </x-table.cell>
                                    <x-table.cell>
                                        <input type="number" wire:model="desglose.{{ $key }}.importe"
                                            wire:change="updatelinea({{ $key }})" onfocus="this.select()"
                                            class="form-control border border-2 p-2" id="iimpo{{ $tag->id }}" />
                                    </x-table.cell>
                                    <x-table.cell>
                                        <select wire:change="updatelinea({{ $key }})"
                                            class="form-select border border-2 p-2"
                                            wire:model="desglose.{{ $key }}.impuesto_id" id="iimpues{{ $tag->id }}">
                                            <option value="0">Sin definir</option>
                                            @foreach($impuestos as $imp)
                                            <option value="{{$imp->id}}">{{$imp->nombre}} {{$imp->porcentaje}}</option>
                                            @endforeach
                                        </select>
                                    </x-table.cell>
                                    <x-table.cell>
                                        <button type="button" class="btn botonoh_negro" data-original-title=""
                                            title="" wire:click="deleteline({{ $tag->id }})">
                                            <i class="material-icons">delete_forever</i>
                                        </button>
                                    </x-table.cell>
                                </x-table.row>
                                @endforeach
                            </x-slot>
                        </x-table>



                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-end" id="ttotal">
                            Importe total: {{$total}}
                        </div>
                        <div class="col-12 text-center" id="borra">
                            <button type="button" class="btn bg-gradient-dark mb-0 me-4" data-original-title="" title=""
                                onclick="confirm('¿Seguro que desea eliminar este {{$ruta}}?') || event.stopImmediatePropagation()"
                                wire:click="eliminardocumento()">
                                Eliminar documento
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>




@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script>
    var vselect;
    var selectedUsers;
    document.addEventListener('livewire:initialized', function() {
    var datac = @this.clientes;
    vselect=VirtualSelect.init({
        ele: '#select-cli',
        search: true,
        searchNormalize:true,
        options: JSON.parse(datac),
    });
    selectedUsers = document.querySelector('#select-cli');
    selectedUsers.addEventListener('change', () => {
        seleo = selectedUsers.value;
        seleo=(seleo==''?0:seleo);
        @this.setidcliente(seleo);
    });
    console.log(datac);
    
document.querySelector('#select-cli').setValue({{$ficha['cliente_id']}});
});

</script>
@endpush