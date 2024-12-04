        <div class="container-fluid py-4">
            <div class="row xxmt-4">
                <div class="col-12">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h5 class="mb-0">Editar {{$singular}}</h5>
                            <!--<p>Edit your tag</p>-->
                        </div>
                        <div class="col-12 text-end">
                            <a class="btn bg-gradient-dark mb-0 me-4" href="{{ route($singular.'-management') }}">volver</a>
                        </div>
                        <div class="card-body">
                            <form wire:submit="update" class='xxd-flex xxflex-column align-items-center'>
                            <div class="row">
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'" :model="'ficha.nombre'" :titulo="'Nombre'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iapellidos'" :model="'ficha.apellidos'" :titulo="'Apellidos'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'inif'" :model="'ficha.nif'" :titulo="'N.I.F.'" :disabled="''" :maxlen="''" :change="''"/>
                                
                                <x-inputboolean :model="'ficha.activo'" :titulo="$singular.' activo'"
                                :maxlen="''" :idfor="'iactive'" :col="12" :colmd="12" :disabled="''" :change="''" />

                                
                                <x-input :tipo="'text'" :col="12" :colmd="12" :idfor="'idomi'" :model="'ficha.domicilio'" :titulo="'Domicilio'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'ipos'" :model="'ficha.cpostal'" :titulo="'Código postal'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'ipob'" :model="'ficha.poblacion'" :titulo="'Población'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'iprov'" :model="'ficha.provincia'" :titulo="'Provincia'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'text'" :col="12" :colmd="6" :idfor="'itele'" :model="'ficha.telefono'" :titulo="'Teléfono'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'mail'" :col="12" :colmd="6" :idfor="'iemail'" :model="'ficha.email'" :titulo="'E-mail'" :disabled="''" :maxlen="''" :change="''"/>
                                
                                @if($singular=="cliente")
                                <x-separador5/>
                                <hr class="horizontal light mt-3">
                                <p>Datos adicionales (si procede)</p>
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre2'" :model="'ficha.nombrepareja'" :titulo="'Nombre pareja'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iapellidos2'" :model="'ficha.apellidospareja'" :titulo="'Apellidos pareja'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'inif2'" :model="'ficha.nifpareja'" :titulo="'N.I.F. pareja'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-separador5/>
                                <hr class="horizontal light mt-3">
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij1'" :model="'ficha.hijo1'" :titulo="'Primer hijo'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-inputdate :model="'ficha.edad1'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda1'" :col="5" :colmd="4" :disabled="''" :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij2'" :model="'ficha.hijo2'" :titulo="'Segundo hijo'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-inputdate :model="'ficha.edad2'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda2'" :col="5" :colmd="4" :disabled="''" :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij3'" :model="'ficha.hijo3'" :titulo="'Tercer hijo'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-inputdate :model="'ficha.edad3'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda3'" :col="5" :colmd="4" :disabled="''" :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij4'" :model="'ficha.hijo4'" :titulo="'Cuarto hijo'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-inputdate :model="'ficha.edad4'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda4'" :col="5" :colmd="4" :disabled="''" :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij5'" :model="'ficha.hijo5'" :titulo="'Quinto hijo'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-inputdate :model="'ficha.edad5'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda5'" :col="5" :colmd="4" :disabled="''" :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij6'" :model="'ficha.hijo6'" :titulo="'Sexto hijo'" :disabled="''" :maxlen="''" :change="''"/>
                                <x-inputdate :model="'ficha.edad6'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda6'" :col="5" :colmd="4" :disabled="''" :change="''" />

                                <x-inputboolean :model="'ficha.permiteimagenes'" :titulo="'El cliente permite publicar sus imágenes'"
                                :maxlen="''" :idfor="'iperima'" :col="12" :colmd="12" :disabled="''" :change="''" />
                                <x-inputboolean :model="'ficha.permitecomunicaciones'" :titulo="'El cliente permite comunicaciones comerciales'"
                                :maxlen="''" :idfor="'ipercomu'" :col="12" :colmd="12" :disabled="''" :change="''" />

                                @endif
                                
                                <div class="col-12 mt-4" id="eeeditor" style="">
                                    <label>Anotaciones internas</label>
                                    <livewire:quill-text-editor wire:model.live="ficha.notasinternas" theme="snow" />
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-dark mt-3 text-center">Actualizar</button>
                                </div>
                                

                            </div>
                            </form>
                                @if(($ficha->email||$ficha->telefono)&&$singular=="cliente")
                                <div class="col-12 text-center">
                                    @if($ficha->email)
                                    <button wire:click="sendclient" class="btn btn-dark mt-3 text-center">Email a cliente para rellenar ficha</button>
                                    @endif
                                    @if($ficha->telefono||1==1)
                                    <button wire:click="sendclientwhatsapp" class="btn btn-dark mt-3 text-center">Whatsapp a cliente para rellenar ficha</button>
                                    @endif
                                </div>
                                @endif
                                @if($errormail)
                                <div class="col-12 text-center">
                                    <p class="rojo">{{$errormail}}</p>
                                </div>
                                @endif
    

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
    @endpush

