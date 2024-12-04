<div class="container-fluid px-2 px-md-2 bg-gray-200">

    <div class="page-header min-height-100 border-radius-xl mt-3" style="
            background-color:black;">
        <div class="col-12 text-center">
            <h4 class="text-center titulocontrato">Completar mis datos de cliente</h4>
            <h5 class="text-center titulogaleria">Por favor complete sus datos de cliente</h5>
        </div>
    </div>

    <div class="card card-body mt-1" style="display:block">
        <div class="row gx-4">
            <div class="col-auto">
                @if($logo)
                <div class="avatar avatar-xl position-relative">
                    <img id="idfot1" src="data:image/jpeg;base64,{{$logo}}" alt=""
                        class="img-fluid shadow border-radius-xl" />
                </div>
                @endif
            </div>


            <div class="col-auto my-auto">
                <div class="h-100">
                    <h6 class="mb-1">
                        {{$empresa}}
                    </h6>
                    <!--<p class="mb-0 font-weight-normal text-sm">
                        CEO / Co-Founder
                    </p>-->
                </div>
            </div>
        </div>



        <div class="">



            <h5 class="mb-0 text-center mt-4 mb-4">Por favor rellene los datos que estén incompletos en su ficha</h5>


            <form wire:submit="update" class='xxd-flex xxflex-column align-items-center'>
                <div class="row">
                    <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'" :model="'fichacli.nombre'"
                        :titulo="'Nombre (obligatorio)'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iapellidos'" :model="'fichacli.apellidos'"
                        :titulo="'Apellidos (obligatorio)'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'inif'" :model="'fichacli.nif'"
                        :titulo="'N.I.F. (obligatorio)'" :disabled="strlen($fichacli->nif)>0?'disabled':''" :maxlen="''"
                        :change="''" />
                    <x-input :tipo="'text'" :col="12" :colmd="12" :idfor="'idomi'" :model="'fichacli.domicilio'"
                        :titulo="'Domicilio'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'ipos'" :model="'fichacli.cpostal'"
                        :titulo="'Código postal'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'ipob'" :model="'fichacli.poblacion'"
                        :titulo="'Población'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'iprov'" :model="'fichacli.provincia'"
                        :titulo="'Provincia'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'text'" :col="12" :colmd="6" :idfor="'itele'" :model="'fichacli.telefono'"
                        :titulo="'Teléfono (obligatorio)'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'mail'" :col="12" :colmd="6" :idfor="'iemail'" :model="'fichacli.email'"
                        :titulo="'E-mail (obligatorio)'" :disabled="strlen($fichacli->email)>0?'disabled':''" :maxlen="''" :change="''" />

                    <x-separador5 />
                    <hr class="horizontal light mt-3">
                    <p>Datos adicionales (si procede)</p>
                    <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre2'" :model="'fichacli.nombrepareja'"
                        :titulo="'Nombre pareja'" :disabled="''" :maxlen="''" :change="''" />
                    <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iapellidos2'"
                        :model="'fichacli.apellidospareja'" :titulo="'Apellidos pareja'" :disabled="''" :maxlen="''"
                        :change="''" />
                    <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'inif2'" :model="'fichacli.nifpareja'"
                        :titulo="'N.I.F. pareja'" :disabled="''" :maxlen="''" :change="''" />
                    <x-separador5 />
                    <hr class="horizontal light mt-3">
                    <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij1'" :model="'fichacli.hijo1'"
                        :titulo="'Primer hijo'" :disabled="''" :maxlen="''" :change="''" />
                    <x-inputdate :model="'fichacli.edad1'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda1'" :col="5"
                        :colmd="4" :disabled="''" :change="''" />
                    <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij2'" :model="'fichacli.hijo2'"
                        :titulo="'Segundo hijo'" :disabled="''" :maxlen="''" :change="''" />
                    <x-inputdate :model="'fichacli.edad2'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda2'" :col="5"
                        :colmd="4" :disabled="''" :change="''" />
                    <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij3'" :model="'fichacli.hijo3'"
                        :titulo="'Tercer hijo'" :disabled="''" :maxlen="''" :change="''" />
                    <x-inputdate :model="'fichacli.edad3'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda3'" :col="5"
                        :colmd="4" :disabled="''" :change="''" />
                    <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij4'" :model="'fichacli.hijo4'"
                        :titulo="'Cuarto hijo'" :disabled="''" :maxlen="''" :change="''" />
                    <x-inputdate :model="'fichacli.edad4'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda4'" :col="5"
                        :colmd="4" :disabled="''" :change="''" />
                    <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij5'" :model="'fichacli.hijo5'"
                        :titulo="'Quinto hijo'" :disabled="''" :maxlen="''" :change="''" />
                    <x-inputdate :model="'fichacli.edad5'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda5'" :col="5"
                        :colmd="4" :disabled="''" :change="''" />
                    <x-input :tipo="'name'" :col="12" :colmd="8" :idfor="'hij6'" :model="'fichacli.hijo6'"
                        :titulo="'Sexto hijo'" :disabled="''" :maxlen="''" :change="''" />
                    <x-inputdate :model="'fichacli.edad6'" :titulo="'Fecha de nacimiento'" :maxlen="''" :idfor="'eda6'" :col="5"
                        :colmd="4" :disabled="''" :change="''" />

                    <div class="col-12 text-center">

                        @error('fichacli.nombre')
                        <h6 class='text-danger inputerror'>{{ $message }} </h6>
                        @enderror
                        @error('fichacli.apellidos')
                        <h6 class='text-danger inputerror'>{{ $message }} </h6>
                        @enderror
                        @error('fichacli.telefono')
                        <h6 class='text-danger inputerror'>{{ $message }} </h6>
                        @enderror
                        @error('fichacli.nif')
                        <h6 class='text-danger inputerror'>{{ $message }} </h6>
                        @enderror
                        @error('fichacli.email')
                        <h6 class='text-danger inputerror'>{{ $message }} </h6>
                        @enderror
                        <button type="submit" class="btn btn-dark mt-3 text-center">Actualizar mis datos</button>
                    </div>
                </div>
            </form>
        </div>


    </div>
</div>


@push('css')
@endpush

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script>
</script>

@endpush