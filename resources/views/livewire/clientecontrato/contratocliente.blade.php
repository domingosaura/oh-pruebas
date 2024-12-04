<div class="container-fluid px-2 px-md-2 bg-gray-200">


    <style>
        body {
            background: rgb(204, 204, 204);
        }

        page[size="A4"] {
            background: white;
            width: 21cm;
            /*height: 29.7cm;*/
            display: block;
            margin: 0 auto;
            padding: 10px;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        }

        @media print {

            body,
            page[size="A4"] {
                margin: 0;
                box-shadow: 0;
            }
        }


        input {
            -webkit-appearance: none !important;
        }

        input[type=checkbox]:checked {
            -webkit-appearance: checkbox !important;
        }

        .check-fijo {
            cursor: pointer;
            accent-color: red !important;
            background-color: lightsalmon !important;
            border-color: red !important;
            border-style: solid;
            border-width: 2px;
        }

        .check-opcional {
            cursor: pointer;
            accent-color: red !important;
            background-color: lightyellow !important;
            border-color: navy !important;
            border-style: solid;
            border-width: 2px;
        }

        ainput[type=checkbox] {
            cursor: pointer;
            accent-color: red !important;
            background-color: lightyellow !important;
            border-color: navy !important;
            border-style: solid;
            border-width: 2px;
        }

    </style>

    <div class="page-header min-height-100 border-radius-xl mt-3" style="
            background-color:black;">
        <div class="col-12 text-center">
            <h4 class="text-center titulocontrato">Contrato</h4>
            <!--<h5 class="text-center titulogaleria">{ {$ficha->nombre} }</h5>-->
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
            <div class="col-6 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                        <li></li>
                        <li class="nav-item" wire:click="vseccion(1)">
                            <a class="nav-link mb-0 px-0 py-1 active {{ $seccion == 1 ? 'naranjitobold' : '' }}" data-bs-toggle="tab" href=""
                                role="tab" aria-selected="true">
                                <i class="material-icons text-lg position-relative">photo_library</i>
                                <span class="ms-1">Datos del cliente</span>
                            </a>
                        </li>
                        <li class="nav-item" wire:click="vseccion(2)">
                            <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 2 ? 'naranjitobold' : '' }}" data-bs-toggle="tab" href="" role="tab"
                                aria-selected="false">
                                <i class="material-icons text-lg position-relative">list</i>
                                <span class="ms-1">Contrato</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="{{$seccion==1?'visible':'oculto'}}">



            <h5 class="mb-0 text-center mt-4 mb-4">Por favor rellene los datos que estén incompletos en su ficha</h5>


            <form wire:submit="updatecli" class='xxd-flex xxflex-column align-items-center'>
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
                        :titulo="'E-mail (obligatorio)'" :disabled="strlen($fichacli->email)>0&&!$esclientenuevo?'disabled':''" :maxlen="''" :change="''" />

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
                        @if($ficha->firmado==false)
                        <button type="submit" class="btn btn-dark mt-3 text-center">Actualizar mis datos</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="{{$seccion==2?'visible':'oculto'}} ql-editor">
            <p>{!!$contrato!!}</p>
        </div>

        <div class="row {{$seccion==2?'visible2':'oculto2'}}">
            <div class="col-xs-12">
                <form method="POST" action="/recibirfirmaalbaran">
                    <label class="" for="">Firma del contrato:</label>
                    <div id="sig" wire:ignore></div>
                    <input name="documento" id="documento" type="hidden" value="" />
                    <textarea wire:model="firma" id="signature64" name="signed" style="display: none;"></textarea>
                </form>
            </div>
            <div class="col-xs-12 text-center">
                <!--<button id="clear" class="btn btn-dark mt-3 text-center">limpiar imagen</button>
                    <button id="saveform" class="btn btn-dark mt-3 text-center">guardar firma</button>-->
            </div>
            <div class="col-12 text-center">
                @if($fallofirma)
                <h5 class="rojo">{{$fallofirma}}</h5>
                @endif
                @if($ficha->firmado==false)
                <button id="clear" class="btn btn-dark mt-2 text-center">limpiar imagen</button>
                <button id="firmar" class="btn btn-dark mt-2">Firmar el contrato</button>
                @endif
            </div>
        </div>
    </div>
</div>


@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
@endpush

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery-ui.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.ui.touch-punch.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.signature.min.js"></script>
<script>
    $(document).ready(function() {
        var sig;
        var image;
        sig = $('#sig').signature({
            syncField: '#signature64',
            syncFormat: 'PNG',
        });
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });
        $('#saveform').click(function(e) {
            e.preventDefault();
            image = $('#signature64').val();
            @this.savesign(image);
        });
        $('#firmar').click(function(e) {
            e.preventDefault();
            var cbs = document.getElementsByClassName('check-fijo');
            for (var i = 0; i < cbs.length; i++) {
                if (cbs[i].type == 'checkbox') {
                    if(!cbs[i].checked){
                        !cbs[i].scrollIntoView();
                        alert("Por favor marque todas las casillas obligatorias.");
                        break;
                    }
                }
            }
            image = $('#signature64').val();
            @this.firmar(image);
        });
    });
</script>

@endpush