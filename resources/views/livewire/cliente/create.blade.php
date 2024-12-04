<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Crear {{ $singular }}</h5>
                    <!--<p>Nuevo cliente</p>-->
                </div>
                <div class="col-12 text-end">
                    <a class="btn bg-gradient-dark mb-0 me-4" href="{{ route($singular . '-management') }}">Volver a
                        listado</a>
                </div>

                <div class="card-body">
                    <form wire:submit="store" class='xxd-flex xxflex-column align-items-center'>
                        <div class="row">

                            <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'"
                                :model="'nombre'" :titulo="'Nombre'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iapellidos'"
                                :model="'apellidos'" :titulo="'Apellidos'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'inif'"
                                :model="'nif'" :titulo="'N.I.F.'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'text'" :col="12" :colmd="12" :idfor="'idomi'"
                                :model="'domicilio'" :titulo="'Domicilio'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'ipos'"
                                :model="'cpostal'" :titulo="'Código postal'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'ipob'"
                                :model="'poblacion'" :titulo="'Población'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'iprov'"
                                :model="'provincia'" :titulo="'Provincia'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'text'" :col="12" :colmd="6" :idfor="'itele'"
                                :model="'telefono'" :titulo="'Teléfono'" :disabled="''" :maxlen="''"
                                :change="''" />
                            <x-input :tipo="'mail'" :col="12" :colmd="6" :idfor="'iemail'"
                                :model="'email'" :titulo="'E-mail'" :disabled="''" :maxlen="''"
                                :change="''" />

                            @if ($singular == 'cliente')
                                <p>Datos adicionales (si procede)</p>
                                <x-separador5 />
                                <hr class="horizontal light mt-3">
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre2'"
                                    :model="'nombrepareja'" :titulo="'Nombre pareja'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iapellidos2'"
                                    :model="'apellidospareja'" :titulo="'Apellidos pareja'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'inif2'"
                                    :model="'nifpareja'" :titulo="'N.I.F. pareja'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-separador5 />
                                <hr class="horizontal light mt-3">
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij1'"
                                    :model="'hijo1'" :titulo="'Primer hijo'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-inputdate :model="'edad1'" :titulo="'Fecha de nacimiento'" :maxlen="''"
                                    :idfor="'eda1'" :col="5" :colmd="4" :disabled="''"
                                    :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij2'"
                                    :model="'hijo2'" :titulo="'Segundo hijo'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-inputdate :model="'edad2'" :titulo="'Fecha de nacimiento'" :maxlen="''"
                                    :idfor="'eda2'" :col="5" :colmd="4" :disabled="''"
                                    :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij3'"
                                    :model="'hijo3'" :titulo="'Tercer hijo'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-inputdate :model="'edad3'" :titulo="'Fecha de nacimiento'" :maxlen="''"
                                    :idfor="'eda3'" :col="5" :colmd="4" :disabled="''"
                                    :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij4'"
                                    :model="'hijo4'" :titulo="'Cuarto hijo'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-inputdate :model="'edad4'" :titulo="'Fecha de nacimiento'" :maxlen="''"
                                    :idfor="'eda4'" :col="5" :colmd="4" :disabled="''"
                                    :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij5'"
                                    :model="'hijo5'" :titulo="'Quinto hijo'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-inputdate :model="'edad5'" :titulo="'Fecha de nacimiento'" :maxlen="''"
                                    :idfor="'eda5'" :col="5" :colmd="4" :disabled="''"
                                    :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij6'"
                                    :model="'hijo6'" :titulo="'Sexto hijo'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-inputdate :model="'edad6'" :titulo="'Fecha de nacimiento'" :maxlen="''"
                                    :idfor="'eda6'" :col="5" :colmd="4" :disabled="''"
                                    :change="''" />

                                <x-inputboolean :model="'permiteimagenes'" :titulo="'El cliente permite publicar sus imágenes'" :maxlen="''"
                                    :idfor="'iperima'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'permitecomunicaciones'" :titulo="'El cliente permite comunicaciones comerciales'" :maxlen="''"
                                    :idfor="'ipercomu'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                            @endif

                            <div class="col-12 mt-4" id="eeeditor" style="">
                                <label>Anotaciones internas</label>
                                <livewire:quill-text-editor wire:model.live="notasinternas" theme="snow" />
                            </div>

                            @if ($singular == 'cliente')
                            <div class="col-12 text-center">
                                <button type="submit" wire:click="saveandgoto" class="btn btn-dark mt-3 text-center">Crear y abrir ficha
                                    para enviar mail/Whatsapp para rellenar ficha</button>
                                </div>
                                @else
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-dark mt-3 text-center">Crear
                                        {{ $singular }}</button>
                                </div>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/flatpickr.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/quilloh.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/quill-resize-module.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/quill.imageCompressor.min.js"></script>
@endpush
