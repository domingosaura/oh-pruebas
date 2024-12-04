<div class="container-fluid py-4 bg-gray-200">
    @if (Session::has('status'))
    <div class="alert alert-success-oh alert-dismissible text-white mx-4" role="alert">
        <span class="text-sm">{{ Session::get('status') }}</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="row mt-0">
        <div class="col-xl-8 col-lg-7">
            <div class="row">

                <div class="col-2 col-md-3 text-center">
                </div>
                <div class="col-8 col-md-6 text-center">
                    <img src="/oh/oh_dashboard.png" class="img-fluid" alt="main_logo">
                </div>
                <div class="col-2 col-md-3 text-center">
                </div>


                <div class="col-sm-6 mt-md-5 mt-5">
                    <div class="card">
                        <a href="{{route('cliente-management')}}">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div
                                    class="oh_fondoazul shadow-dark border-radius-lg py-2 pe-1 align-items-center text-center">
                                    <div class="">
                                        <span
                                            class="material-symbols-outlined text-white text-xl mb-1 font60">group</span>
                                        <h6 class="text-white font-weight-normal"> Clientes </h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="card-body py-3">
                            <p class="text-sm mb-0">Clientes totales:</p>
                            <h5 class="font-weight-bolder mb-0 oh_text-dark">
                                {{$totalclientes}}
                            </h5>
                            <p class="float-start">&nbsp;</p>
                        </div>
                    </div>
                </div>


                @if(!env('TESTMODE'))
                <div class="col-sm-6 mt-md-5 mt-5">
                    <div class="card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="oh_fondoazul shadow-dark border-radius-lg py-2 pe-1 align-items-center text-center">
                                <div class="">
                                    <span
                                        class="material-symbols-outlined text-white text-xl mb-1 font60">calendar_month</span>
                                    <h6 class="text-white font-weight-normal"> Calendario reservas&nbsp;<span
                                            class="badge badge-danger ms-auto mb-auto naranjito">¡pronto!</span></h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-3">
                            <p class="text-sm mb-0">Calendarios activos:</p>
                            <h5 class="font-weight-bolder mb-0 oh_text-dark">
                                {{$totalcalendarios}}
                            </h5>
                            <p class="float-start">&nbsp;</p>
                        </div>
                    </div>
                </div>
                @endif
                @if(env('TESTMODE'))
                <div class="col-sm-6 mt-md-5 mt-5">
                    <div class="card">
                        <a href="{{route('calendarios-management')}}">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div
                                    class="oh_fondoazul shadow-dark border-radius-lg py-2 pe-1 align-items-center text-center">
                                    <div class="">
                                        <span
                                            class="material-symbols-outlined text-white text-xl mb-1 font60">calendar_month</span>
                                        <h6 class="text-white font-weight-normal"> Calendario reservas </h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="card-body py-3">
                            <p class="text-sm mb-0">Calendarios activos:</p>
                            <h5 class="font-weight-bolder mb-0 oh_text-dark">
                                {{$totalcalendarios}}
                            </h5>
                            <p class="float-start">&nbsp;</p>
                        </div>
                    </div>
                </div>
                @endif




                <div class="col-sm-6 mt-md-5 mt-5">
                    <div class="card">
                        <a href="{{route('clientecontrato-management')}}">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div
                                    class="oh_fondoazul shadow-dark border-radius-lg py-2 pe-1 align-items-center text-center">
                                    <div class="">
                                        <span
                                            class="material-symbols-outlined text-white text-xl mb-1 font60">contract_edit</span>
                                        <h6 class="text-white font-weight-normal"> Contratos </h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="card-body py-3">
                            <p class="text-sm mb-0">Firmados/No firmados:</p>
                            <h5 class="font-weight-bolder mb-0 oh_text-dark">
                                {{$firmados}}&nbsp;/&nbsp;{{$nofirmados}}
                            </h5>
                            <p class="float-start">&nbsp;</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mt-md-5 mt-5">
                    <div class="card">
                        <a href="{{route('galeria-management')}}">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div
                                    class="oh_fondoazul shadow-dark border-radius-lg py-2 pe-1 align-items-center text-center">
                                    <div class="">
                                        <span
                                            class="material-symbols-outlined text-white text-xl mb-1 font60">gallery_thumbnail</span>
                                        <h6 class="text-white font-weight-normal"> Galerías </h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="card-body py-3">
                            <p class="text-sm mb-0 oh_text-dark">Galerías activas:</p>
                            <h5 class="font-weight-bolder mb-0 oh_text-dark">
                                {{$totalgalerias}}&nbsp;
                            </h5>
                            <p class="float-start oh_text-dark">({{$totalgigas}} Gb. de almacenamiento en uso)</p>
                            @if($totalgigastotal>0)
                            <p class="float-start oh_text-dark">({{$totalgigastotal}} Gb. de todos los clientes)</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-1 col-md-1 text-center">
                </div>
                <div class="col-10 col-md-10 text-center">
                    <img src="/oh/nuevaimagen.png" class="img-fluid" alt="main_logo">
                </div>
                <div class="col-1 col-md-1 text-center">
                </div>


            </div>
        </div>
        <div class="col-xl-4 col-lg-5 mt-lg-0 mt-2">
            <div class="row">
                <div class="col-lg-12 col-sm-6">
                    <div class="card mt-2">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0 oh_text-dark">Próximas citas de clientes</h6>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-group">
                                @foreach($next as $event)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <a href="{{ route('edit-calendario', $event['basecalendario_id'])}}">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="material-icons opacity-10 pt-2">notifications</i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 oh_text-dark text-sm">{{$event['title']}}</h6>
                                                <span class="text-sm">{{date('d/m/Y',
                                                    strtotime($event['start']))}}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12 col-sm-6">
                    <div class="card mt-2">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Notificaciones</h6>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-group">
                                @foreach($avisos as $event)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex xxalign-items-center">
                                        <div class="d-flex flex-column puntero" title="Descartar aviso"
                                        wire:click="removeaviso({{ $event['id'] }})">
                                        <i class="material-icons ">close</i>
                                    </div>
                                    <div class="d-flex flex-column puntero">&nbsp;
                                    </div>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('edit-galeria', $event['galeria_id'])}}">
                                                <h6 class="mb-1 oh_text-dark text-sm">{{$event['notas']}}</h6>
                                                </a>
                                                <span class="text-sm">{{$event['nombre'].'
                                                    '.$event['apellidos']}}</span>
                                                <span class="text-sm">en fecha
                                                    {{Utils::datetime($event['created_at'])}}</span>
                                        </div>



                                    </div>
                                </li>
                                @endforeach
                                <!--
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <a href="#">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="material-icons opacity-10 pt-2">mark_unread_chat_alt</i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 oh_text-dark text-sm">$event['notas']</h6>
                                                <span class="text-sm">fecha</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--
<x-whatsapp : phone="'34630355905'" : text="'test texto'"/>
-->


</div>
<!--   Core JS Files   -->
@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>

<script>
</script>
@endpush