<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar plantilla contrato</h5>
                    <!--<p>ATENTO, TODOS LOS MOVIMIENTOS SE GRABAN AUTOMÁTICAMENTE</p>-->



                    <div class="form-group col-6 col-md-7 mt-2">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Click para copiar de otra plantilla
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                @foreach($plantillas as $key=>$p)
                                <li><a class="dropdown-item"
                                        wire:click="selectplantilla({{$key}})">{{$p['nombre']}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>




                    <div class="col-12 text-end">
                        <a class="btn bg-gradient-dark mb-0 me-4 float-end" wire:click="update(1)"
                            href="{{ route('plantillacontrato-management') }}">volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <x-input :tipo="'name'" :col="9" :colmd="4" :idfor="'idoc'" :model="'ficha.nombre'"
                            :titulo="'Descripción contrato'" :disabled="''" :maxlen="'250'" :change="''" />


                            <div class="col-12 mt-4" id="eeeditor" style="">
                                <label>Texto del contrato</label>
                                <livewire:quill-text-editor wire:model.live="ficha.texto" theme="snow" />
                            </div>



                        <div class="col-6">


                            <div class="dropdown float-left">
                                <a class="dropdown-toggle italica float-left" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">poner texto de ejemplo</a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <a onclick="confirm('Se suistituirá el texto actual del contrato') || event.stopImmediatePropagation()"
                                            class="mb-0 me-4 float-start puntero italica"
                                            wire:click="ejemplo(2)">contrato genérico</a>
                                    </li>
                                    <li>
                                        <a onclick="confirm('Se suistituirá el texto actual del contrato') || event.stopImmediatePropagation()"
                                            class="mb-0 me-4 float-start puntero italica"
                                            wire:click="ejemplo(1)">contrato recién nacido</a>
                                    </li>
                                    <li>
                                        <a onclick="confirm('Se suistituirá el texto actual del contrato') || event.stopImmediatePropagation()"
                                            class="mb-0 me-4 float-start puntero italica"
                                            wire:click="ejemplo(3)">contrato bautizo</a>
                                    </li>
                                    <li>
                                        <a onclick="confirm('Se suistituirá el texto actual del contrato') || event.stopImmediatePropagation()"
                                            class="mb-0 me-4 float-start puntero italica"
                                            wire:click="ejemplo(4)">contrato bebé</a>
                                    </li>
                                    <li>
                                        <a onclick="confirm('Se suistituirá el texto actual del contrato') || event.stopImmediatePropagation()"
                                            class="mb-0 me-4 float-start puntero italica"
                                            wire:click="ejemplo(5)">smash cake</a>
                                    </li>
                                </ul>
                            </div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="dropdown">
                                <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                    data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(1)">check obligatorio</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(2)">check opcional</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(3)">nombre empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(4)">nombre propio empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(5)">nif empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(6)">domicilio empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(7)">código postal empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(8)">población empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(9)">provincia empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(10)">teléfono empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(11)">email empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(12)">nombre/apellidos cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(13)">nif cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(14)">domicilio cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(15)">código postal cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(16)">población cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(17)">provincia cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(18)">teléfono cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(19)">email cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(20)">nombre/apellidos pareja</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(21)">nif pareja</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(22)">Primer hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(23)">Segundo hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(24)">Tercer hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(25)">Cuarto hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(26)">Quinto hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(27)">Sexto hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(28)">Fecha contrato</a></li>
                                    <!--<li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(29)">Cuadro de firma</a></li>-->
                                </ul>
                            </div>


                        </div>
                        <div class="col-12 text-center">
                            <button wire:click="update" class="btn btn-dark mt-3 text-center">Guardar plantilla</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quilloh.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill-resize-module.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill.imageCompressor.min.js"></script>
<script>
</script>
@endpush