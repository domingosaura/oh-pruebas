<div class="container-fluid py-4" id="master">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar pack</h5>
                    <p>{{$ficha->nombre}}</p>
                    <div class="col-6 float-start">
                    </div>
                    <div class="col-6 float-end">
                        <a class="btn bg-gradient-dark mb-0 me-4 float-end" wire:click="update(1)"
                            href="{{ route('packs-management') }}">volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">



                        <div class="row mt-4">
                            <!--basico-->
                            <div class="col-12 col-md-4">
                                <img id="imabase" src="
                                @if(strlen($ficha['binario'])==0)
                                /oh/img/gallery-generic.jpg
                                @else
                                data:image/jpeg;base64,{{$ficha['binario']}}
                                @endif
                                "
                                class="img-fluid shadow border-radius-xl">
                            </div>
                            <x-separador/>
                            <div class="col-12 col-md-4">
                                <x-filepond wire:model="files" maxsize="27MB" resize="true" width="425" height="283" w5sec="true"/>
                            </div>
        

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'idoci'" :model="'ficha.nombre'"
                                :titulo="'Nombre del pack'" :disabled="''"
                                :maxlen="''" :change="''" />

                                <div class="col-12 mt-4" id="eeeditor" style="">
                                    <label>Descripción del pack</label>
                                    <livewire:quilloh wire:model.live="ficha.anotaciones" theme="snow"
                                        idid="fichaanotaciones" />
                                </div>
                                <div class="col-12 mt-4" id="eeeditor2" style="">
                                    <label>Anotaciones internas</label>
                                    <livewire:quilloh wire:model.live="ficha.anotaciones2" theme="snow"
                                        idid="fichaanotaciones2" />
                                </div>
    
                            <x-inputboolean :model="'ficha.activa'" :titulo="'Pack activo (disponible para usar)'"
                                :maxlen="''" :idfor="'iactivva'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            <x-inputboolean :model="'ficha.sinfecha'" :titulo="'Permitir reserva sin fecha'"
                                :maxlen="''" :idfor="'isinfe'" :col="12" :colmd="12" :disabled="''" :change="''" />

                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregal'"
                                    :model="'ficha.minutos'" :titulo="'Duración en minutos'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                    <x-separador/>
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregal'"
                                    :model="'ficha.preciopack'" :titulo="'Precio del pack'" :disabled="''"
                                    :maxlen="''" :change="''" />
                                <x-input :tipo="'number'" :col="4" :colmd="4" :idfor="'ipregalc'"
                                    :model="'ficha.precioreserva'" :titulo="'Importe de la reserva'"
                                    :disabled="''" :maxlen="''" :change="''" />




                        </div>
                        <hr class="horizontal light mt-4">
                        @if($errors->any())
                        <div class="row">
                        {!! implode('', $errors->all('<div class="col-12 text-center rojo">:message</div>')) !!}
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12 col-md-12 text-center">
                                <button wire:click="update" class="btn btn-dark mt-3 text-center">Guardar pack</button>
                                <button wire:click="updateout" class="btn btn-dark mt-3 text-center">Guardar pack y salir</button>
                            </div>
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
<script src="{{ asset('assets') }}/js/plugins/flatpickr.min.js"></script>
<script>

window.addEventListener('focusonproducto', event => {
    document.getElementById('fprod'+event.detail[0].key).scrollIntoView();
});

window.addEventListener('focusonproductosel', event => {
    document.getElementById('fprodsel'+event.detail[0].key).scrollIntoView();
});

window.addEventListener('closemodalproducto', event => { 
    $('.modalproducto').modal('hide');
});

window.addEventListener('closemodal', event => { 
    $('#createcliente').modal('hide');
});
window.addEventListener('asignarcliente', event => {
    vselect.setOptions(JSON.parse(@this.clientes));
    document.querySelector('#select-cli').setValue(event.detail[0].id);
});


var clipboard = new ClipboardJS('.copiapega');
clipboard.on('success', function (e) {
    //console.info('Action:', e.action);
    console.info('Text:', e.text);
    //console.info('Trigger:', e.trigger);
});
clipboard.on('error', function (e) {
  console.log(e);
});






</script>
@endpush