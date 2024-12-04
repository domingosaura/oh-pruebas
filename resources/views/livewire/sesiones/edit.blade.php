<div class="container-fluid py-4" id="master">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar servicio</h5>
                    <p>{{ $ficha->nombre }}</p>
                    <div class="col-6 float-start">
                    </div>
                    <div class="col-6 float-end">
                        <a class="btn bg-gradient-dark mb-0 me-4 float-end" wire:click="update(1)"
                            href="{{ route('sesiones-management') }}">volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">


                        <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                            <div class="nav-wrapper position-relative" nowireignore>
                                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                    <li></li>
                                    <li class="nav-item" wire:click="vseccion(1)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==1?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">settings</i>
                                            <span class="ms-1">Básico</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="vseccion(2)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==2?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">list</i>
                                            <span class="ms-1">Packs</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="vseccion(3)">
                                        <a class="nav-link mb-0 px-0 py-1 {{$seccion==3?'naranjitobold':''}}" data-bs-toggle="tab"
                                            href="" role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">email</i>
                                            <span class="ms-1">Correo</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-4 {{ $seccion == 1 ? 'visible' : 'oculto' }}">
                            <!--basico-->
                            <div class="col-12 col-md-4">
                                <img id="imabase"
                                    src="
                                @if (strlen($ficha['binario']) == 0) /oh/img/gallery-generic.jpg
                                @else
                                data:image/jpeg;base64,{{ $ficha['binario'] }} @endif
                                "
                                   class="img-fluid shadow border-radius-xl">
                            </div>
                            <div class="col-12 col-md-4">
                                <x-filepond wire:model="files" maxsize="27MB" resize="true" width="425"
                                    height="283" w5sec="true"/>
                            </div>

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'idoc'"
                                :model="'ficha.nombre'" :titulo="'Nombre del servicio'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-inputboolean :model="'ficha.activa'" :titulo="'Servicio activo (disponible para usar)'" :maxlen="''" :idfor="'iactivva'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <div class="col-12 mt-4" id="eeeditor" style="">
                                <label>Descripción del servicio</label>
                                <livewire:quilloh wire:model.live="ficha.anotaciones" theme="snow"
                                    idid="fichaanotaciones" />
                            </div>
                            <div class="col-12 mt-4" id="eeeditor2" style="">
                                <label>Anotaciones internas</label>
                                <livewire:quilloh wire:model.live="ficha.anotaciones2" theme="snow"
                                    idid="fichaanotaciones2" />
                            </div>


                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg1'"
                                :model="'ficha.pregunta1'" :titulo="'Pregunta para el cliente (1)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga1'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob1'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg2'"
                                :model="'ficha.pregunta2'" :titulo="'Pregunta para el cliente (2)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga2'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob2'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg3'"
                                :model="'ficha.pregunta3'" :titulo="'Pregunta para el cliente (3)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga3'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob3'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg4'"
                                :model="'ficha.pregunta4'" :titulo="'Pregunta para el cliente (4)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga4'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob4'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg5'"
                                :model="'ficha.pregunta5'" :titulo="'Pregunta para el cliente (5)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga5'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob5'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg6'"
                                :model="'ficha.pregunta6'" :titulo="'Pregunta para el cliente (6)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga6'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob6'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg7'"
                                :model="'ficha.pregunta7'" :titulo="'Pregunta para el cliente (7)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga7'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob7'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg8'"
                                :model="'ficha.pregunta8'" :titulo="'Pregunta para el cliente (8)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga8'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob8'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg9'"
                                :model="'ficha.pregunta9'" :titulo="'Pregunta para el cliente (9)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga9'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob9'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="9" :colmd="12" :idfor="'ipreg10'"
                                :model="'ficha.pregunta10'" :titulo="'Pregunta para el cliente (10)'" :disabled="''" :maxlen="'250'"
                                :change="''" />
                            <x-inputboolean :model="'ficha.obliga10'" :titulo="'¿Respuesta obligatoria?'" :maxlen="''" :idfor="'iresob10'"
                                :col="12" :colmd="12" :disabled="''" :change="''" />

                                <!--
                                < x -input : tipo="'number'" : col="4" : colmd="4" : idfor="'iantel'"
                                    : model="'ficha.antelacion'" : titulo="'Días de antelación para reservar'" : disabled="''"
                                    : maxlen="''" : change="''" / >
                                -->

                        </div>
                        <div class="{{ $seccion == 3 ? 'visible' : 'oculto' }}">
                            <!--email-->
                            <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'ienvasu'"
                                :model="'ficha.emailconfirmaasunto'" :titulo="'Envío de email confirmación de reserva: asunto'" :disabled="''" :maxlen="''"
                                :change="''" />


                            <div class="col-12 mt-4" id="eeeditor3" style="">
                                <label>Envío de email confirmación de reserva: cuerpo</label>
                                <livewire:quilloh wire:model.live="ficha.emailconfirmacuerpo" theme="snow"
                                    idid="fichaemailconfirmacuerpo" />
                            </div>
                            <div class="col-12 text-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                        data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                    <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(102,1)">nombre empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(103,1)">nombre propio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(104,1)">domicilio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(105,1)">código postal empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(106,1)">población empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(107,1)">provincia empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(108,1)">teléfono empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(109,1)">email empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(110,1)">nombre/apellidos cliente</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(113,1)">nombre del servicio</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(115,1)">nombre del pack</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(117,1)">fecha servicio</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(118,1)">localizador</a></li>
                                    </ul>
                                </div>
                            </div>









                            
                            <div class="col-12 mt-4" id="eeeditor4" style="">
                                <label>Envío de email confirmación de reserva CUANDO IMPORTE RESERVA SEA 0: cuerpo</label>
                                <livewire:quilloh wire:model.live="ficha.emailconfirmacuerpo0" theme="snow"
                                    idid="fichaemailconfirmacuerpo0" />
                            </div>
                            <div class="col-12 text-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                        data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                    <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(102,3)">nombre empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(103,3)">nombre propio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(104,3)">domicilio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(105,3)">código postal empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(106,3)">población empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(107,3)">provincia empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(108,3)">teléfono empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(109,3)">email empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(110,3)">nombre/apellidos cliente</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(113,3)">nombre del servicio</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(115,3)">nombre del pack</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(117,3)">fecha servicio</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(118,3)">localizador</a></li>
                                    </ul>
                                </div>
                            </div>










                            <x-input :tipo="'text'" :col="9" :colmd="8" :idfor="'irecasu'"
                                :model="'ficha.emailrecuerdaasunto'" :titulo="'Envío de email recordatorio de reserva: asunto'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <div class="col-12 mt-4" id="eeeditor2" style="">
                                <label>Envío de email recordatorio de reserva: cuerpo</label>
                                <p>Los emails de recordatorio de reserva se envían automáticamente cuando faltan 7 y 1 días para la fecha</p>
                                <livewire:quilloh wire:model.live="ficha.emailrecuerdacuerpo" theme="snow"
                                    idid="fichaemailrecuerdacuerpo" />
                            </div>
                            <div class="col-12 text-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                        data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                    <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(102,2)">nombre empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(103,2)">nombre propio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(104,2)">domicilio empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(105,2)">código postal empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(106,2)">población empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(107,2)">provincia empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(108,2)">teléfono empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(109,2)">email empresa</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(110,2)">nombre/apellidos cliente</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(113,2)">nombre del servicio</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(115,2)">nombre del pack</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(117,2)">fecha servicio</a></li>
                                        <li><a class="mb-0 me-4 float-start puntero italica"
                                                wire:click="variable(118,2)">localizador</a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="{{ $seccion == 2 ? 'visible' : 'oculto' }}">
                            <div class="row mt-4">
                                <!--packs-->

                                <h6 class="mb-0">Packs elegibles en este servicio</h6>
                                <div class="form-group col-12 col-md-12 mt-4">
                                    <div class="dropdown">
                                        <a class="btn btn-secondary mb-0 me-4 float-start dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">Añadir packs</a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="{{ route('packs-management') }}?nfservice={{$idsesion}}"><strong>+ nuevo pack</strong></a>
                                            </li>
                                            @foreach ($packsdisponibles as $key => $p)
                                                <li><a class="dropdown-item"
                                                        wire:click="addpack({{ $key }})">{{ $p['nombre'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12">
                                    @if ($packsincluidos == 0)
                                        <p>Aún no ha añadido ningún pack</p>
                                    @endif
                                </div>
                                @foreach ($packs as $key => $tag)
                                    @include('livewire.sesiones.sesiones_packs', ['origen' => 'sesiones'])
                                @endforeach
                            </div>
                        </div>
                        <hr class="horizontal light mt-4">
                        @if ($errors->any())
                            <div class="row">
                                {!! implode('', $errors->all('<div class="col-12 text-center rojo">:message</div>')) !!}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12 col-md-12 text-center">
                                <button wire:click="update" class="btn btn-dark mt-3 text-center">Guardar
                                    servicio</button>
                                <button wire:click="updateout" class="btn btn-dark mt-3 text-center">Guardar servicio y
                                    salir</button>
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
        window.addEventListener('focusonpack', event => {
            document.getElementById('fprod' + event.detail[0].key).scrollIntoView();
        });

        var clipboard = new ClipboardJS('.copiapega');
        clipboard.on('success', function(e) {
            //console.info('Action:', e.action);
            console.info('Text:', e.text);
            //console.info('Trigger:', e.trigger);
        });
        clipboard.on('error', function(e) {
            console.log(e);
        });
    </script>
@endpush
