<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar Producto</h5>
                    <!--<p>Edit your tag</p>-->
                </div>
                <div class="col-12 text-end">
                    <a class="btn bg-gradient-dark mb-0 me-4" href="{{ route('productos-management') }}">volver</a>
                </div>
                <div class="card-body">
                    <form wire:submit="update" class='xxd-flex xxflex-column align-items-center'>
                    <div class="row">
                        <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'" :model="'ficha.nombre'" :titulo="'Descripción del producto'" :disabled="''" :maxlen="'100'" :change="''"/>
                        
                        <div class="col-12 col-md-6">
                            <p class="mb-0 font-weight-normal text-sm">
                                Imagen orientativa
                            </p>
                            @if(strlen($ficha->binario)>0)
                            <p class="mb-0 font-weight-normal text-sm puntero italica" wire:click="deleteimage">
                                Eliminar imagen
                            </p>
                            <img id="idfot1" src="
                        data:image/jpeg;base64,{{$ficha->binario}}" class="img-fluid shadow border-radius-xl" />
                            @endif
                            <x-filepond wire:model="files" maxsize="50MB" resize="false" width="1920" height="1080"
                                varname="fi1" w5sec="true" />
                        </div>

                        <div class="col-12 mt-4" id="eeeditor" style="">
                            <label>Descripción del producto</label>
                            <livewire:quill-text-editor wire:model.live="ficha.anotaciones" theme="snow" />
                        </div>

                        <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregal'"
                        :model="'ficha.precioproducto'" :titulo="'Precio del producto'" :disabled="''"
                        :maxlen="''" :change="''" />
                        <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'inumf'" :model="'ficha.numfotos'"
                        :titulo="'Número de fotografías del producto'" :disabled="''" :maxlen="''" :change="''" />
                        
                        <x-separador/>
                        <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'inumfsd'" :model="'ficha.numfotosadicionales'"
                        :titulo="'Fotografías adicionales que se pueden elegir'" :disabled="''" :maxlen="''" :change="''" />
                        <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'inumfaa'" :model="'ficha.preciofotoadicional'"
                        :titulo="'Precio de cada fotografía adicional'" :disabled="''" :maxlen="''" :change="''" />


                        <x-inputboolean :model="'ficha.permitecantidad'"
                        :titulo="'Permitir seleccionar cuantas unidades de este producto comprar'" :maxlen="''"
                        :idfor="'ipermitircanti'" :col="12" :colmd="12" :disabled="''" :change="''" />


                        <div class="form-group col-12 col-md-12 mt-3">
                            <label>Si el producto contiene fotografías, se escogen de:</label>
                            <div class="form-check">
                                <input wire:model.live="ficha.fotosdesde" class="form-check-input" type="radio"
                                    value='0' id="f0" wire:change="">
                                <label class="form-check-label" for="f0">
                                    este producto no necesita seleccionar fotografías
                                </label>
                            </div>
                            <div class="form-check">
                                <input wire:model.live="ficha.fotosdesde" class="form-check-input" type="radio"
                                    value='1' id="f1" wire:change="">
                                <label class="form-check-label" for="f1">
                                    cualquier fotografía de la galería
                                </label>
                            </div>
                            <div class="form-check">
                                <input wire:model.live="ficha.fotosdesde" class="form-check-input" type="radio"
                                    value='2' id="f2" wire:change="">
                                <label class="form-check-label" for="f2">
                                    fotografías seleccionadas en la galería
                                </label>
                            </div>
                            <div class="form-check">
                                <input wire:model.live="ficha.fotosdesde" class="form-check-input" type="radio"
                                    value='3' id="f3" wire:change="">
                                <label class="form-check-label" for="f3">
                                    fotografías no seleccionadas en la galería
                                </label>
                            </div>
                        </div>
                        <x-input :tipo="'name'" :col="9" :colmd="6" :idfor="'iop1'" :model="'ficha.txtopc1'" :titulo="'Opción adicional 1 (el cliente puede seleccionar o no la opción)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-input :tipo="'number'" :col="3" :colmd="4" :idfor="'ipre1'" :model="'ficha.precio1'" :titulo="'Precio'" :disabled="''" :maxlen="''" :change="''" />
                        <x-input :tipo="'name'" :col="9" :colmd="6" :idfor="'iop2'" :model="'ficha.txtopc2'" :titulo="'Opción adicional 2 (el cliente puede seleccionar o no la opción)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-input :tipo="'number'" :col="3" :colmd="4" :idfor="'ipre2'" :model="'ficha.precio2'" :titulo="'Precio'" :disabled="''" :maxlen="''" :change="''" />
                        <x-input :tipo="'name'" :col="9" :colmd="6" :idfor="'iop3'" :model="'ficha.txtopc3'" :titulo="'Opción adicional 3 (el cliente puede seleccionar o no la opción)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-input :tipo="'number'" :col="3" :colmd="4" :idfor="'ipre3'" :model="'ficha.precio3'" :titulo="'Precio'" :disabled="''" :maxlen="''" :change="''" />
                        <x-input :tipo="'name'" :col="9" :colmd="6" :idfor="'iop4'" :model="'ficha.txtopc4'" :titulo="'Opción adicional 4 (el cliente puede seleccionar o no la opción)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-input :tipo="'number'" :col="3" :colmd="4" :idfor="'ipre4'" :model="'ficha.precio4'" :titulo="'Precio'" :disabled="''" :maxlen="''" :change="''" />
                        <x-input :tipo="'name'" :col="9" :colmd="6" :idfor="'iop5'" :model="'ficha.txtopc5'" :titulo="'Opción adicional 5 (el cliente puede seleccionar o no la opción)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-input :tipo="'number'" :col="3" :colmd="4" :idfor="'ipre5'" :model="'ficha.precio5'" :titulo="'Precio'" :disabled="''" :maxlen="''" :change="''" />
                        
                        <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iques1'" :model="'ficha.pregunta1'" :titulo="'Pregunta para el cliente (1)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-inputboolean :model="'ficha.pre1obligatorio'" :titulo="'¿Es obligatorio responder la pregunta?'" :maxlen="''" :idfor="'ipregob1'" :col="12" :colmd="12" :disabled="''" :change="''" />
                        <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iques2'" :model="'ficha.pregunta2'" :titulo="'Pregunta para el cliente (2)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-inputboolean :model="'ficha.pre2obligatorio'" :titulo="'¿Es obligatorio responder la pregunta?'" :maxlen="''" :idfor="'ipregob2'" :col="12" :colmd="12" :disabled="''" :change="''" />
                        <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iques3'" :model="'ficha.pregunta3'" :titulo="'Pregunta para el cliente (3)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-inputboolean :model="'ficha.pre3obligatorio'" :titulo="'¿Es obligatorio responder la pregunta?'" :maxlen="''" :idfor="'ipregob3'" :col="12" :colmd="12" :disabled="''" :change="''" />
                        <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iques4'" :model="'ficha.pregunta4'" :titulo="'Pregunta para el cliente (4)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-inputboolean :model="'ficha.pre4obligatorio'" :titulo="'¿Es obligatorio responder la pregunta?'" :maxlen="''" :idfor="'ipregob4'" :col="12" :colmd="12" :disabled="''" :change="''" />
                        <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iques5'" :model="'ficha.pregunta5'" :titulo="'Pregunta para el cliente (5)'" :disabled="''" :maxlen="'100'" :change="''"/>
                        <x-inputboolean :model="'ficha.pre5obligatorio'" :titulo="'¿Es obligatorio responder la pregunta?'" :maxlen="''" :idfor="'ipregob5'" :col="12" :colmd="12" :disabled="''" :change="''" />
                        @if($errors->any())
                        <div class="row">
                        {!! implode('', $errors->all('<div class="col-12 text-center rojo">:message</div>')) !!}
                        </div>
                        @endif
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-dark mt-3 text-center">Actualizar</button>
                        </div>
                    </div>
                    </form>
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