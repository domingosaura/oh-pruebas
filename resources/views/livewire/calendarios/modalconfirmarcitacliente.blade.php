<div class="modal fade" id="confirmarcitacliente" tabindex="9999" style="z-index:9999" data-bs-backdrop="static"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="row modal-header">
                <div class="col-12 text-center">
                    <h4 class="modal-title">Reserva de cita</h4>
                    <h6 class="modal-title">{{ $servicio['nombre'] }} - {{ $pack['nombre'] }}</h6>
                    </h5>
                </div>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="{{ $pagpago == 1 ? 'visible' : 'oculto2' }}">
                        <div class="row">
                            <div class="col-4 col-md-4">
                                    <img src=
                                    @if (strlen($pack['binario']) == 0)
                                        "/oh/img/gallery-generic.jpg"
                                    @else
                                        @if (Session('soporteavif'))
                                            "data:image/jpeg;base64,{{ $pack['binario'] }}"
                                        @else
                                            "{{ Utils::inMacBase64($pack['binario']) }}"
                                        @endif
                                    @endif
                                    class="img-fluid shadow-lg border-radius-xl">
                            </div>
                            <div class="col-8 col-md-8 text-start">
                                <h5>
                                        {{ $pack['nombre'] }}&nbsp;
                                        <h6>{!! Utils::left(Utils::datetime($multi['inicio']),10) !!} de {!! Utils::left(Utils::right(Utils::datetime($multi['inicio']),8),5) !!} a {!! Utils::left(Utils::right(Utils::datetime($multi['fin']),8),5) !!}</h6>
                                        <p>{!! $pack['anotaciones'] !!}</p>
                                        <p>El precio de este pack es de <strong>{{ $pack['preciopack'] }}</strong> &euro; con una reserva de <strong>{{ $pack['precioreserva'] }}</strong> &euro;</p>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="{{ $pagpago == 2 ? 'visible' : 'oculto2' }}">
                        <div class="row">
                            <!--identificate-->
                            @if ($idclientepago > 0)
                                <div class="col-12 text-center">
                                    <h6>Se ha identificado correctamente, puede continuar</h6>
                                </div>
                            @endif
                            @if ($idclientepago == 0)
                                <div class="col-12 text-center">
                                    <h6>Ya soy cliente:</h6>
                                    <p>si ya es cliente introduzca su NIF, teléfono ó email para continuar</p>
                                </div>

                                <div class="col-12 col-md-12 text-center">
                                    <div class="form-group">
                                        <label for="igua">NIF / Teléfono / Email</label>
                                        <input wire:model.blur="buscadorcliente" type="text"
                                        class="form-control form-inline border border-2 p-2" id="igua"
                                        placeholder="datos de registro" maxlength="200" onfocus="this.select()">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 text-center mt-1">
                                    <button wire:click="buscar_cliente" type="button"
                                    class="btn btn-success fondonaranjito">Buscar</button>
                                </div>

                                <div class="col-12 text-center mt-4">
                                    <h6>No soy cliente:</h6>
                                    <p>si aún no es cliente, introduzca sus datos para continuar</p>
                                </div>
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'"
                                    :model="'fichanuevocliente.nombre'" :titulo="'Nombre'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'iapellidos'"
                                    :model="'fichanuevocliente.apellidos'" :titulo="'Apellidos'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'inif'"
                                    :model="'fichanuevocliente.nif'" :titulo="'N.I.F.'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'text'" :col="12" :colmd="6" :idfor="'itele'"
                                    :model="'fichanuevocliente.telefono'" :titulo="'Teléfono'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <x-input :tipo="'mail'" :col="12" :colmd="6" :idfor="'iemail'"
                                    :model="'fichanuevocliente.email'" :titulo="'E-mail'" :disabled="''" :maxlen="''"
                                    :change="''" />
                                <div class="col-12 col-md-12 text-center mt-4">
                                    <button wire:click="nuevo_cliente" type="button"
                                        class="btn btn-success fondonaranjito">Guardar</button>
                                </div>
                            @endif


                        </div>
                    </div>
                    <div class="{{ $pagpago == 3 ? 'visible' : 'oculto2' }}">
                        <div class="row mt-4">
                            <!--confirmar reserva-->
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <div class="col-6 col-md-6 text-center mb-4">
                                        <div class="border border-light border-1 border-radius-md py-3">
                                            <h6 class="text-primary text-gradient mb-0">Importe de la sesión</h6>
                                            <h4 class="font-weight-bolder mb-0">
                                                <span id="state1"
                                                    countTo="">{{ $pack['preciopack'] }}</span>
                                                <span class="small"> &euro;</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6 text-center mb-4">
                                        <div class="border border-light border-1 border-radius-md py-3">
                                            <h6 class="text-primary text-gradient mb-0">Importe de la reserva</h6>
                                            <h4 class="font-weight-bolder mb-0">
                                                <span id="state1"
                                                countTo="">{{ $pack['precioreserva'] }}</span>
                                                <span class="small"> &euro;</span>
                                            </h4>
                                        </div>
                                    </div>


                                    @if($pack['precioreserva']==0)
                                    <div class="col1-12 col-md-12 text-center mt-4">
                                        <button wire:click="confirmar_reserva" class="btn btn-danger text-start">Finalizar reserva</button>
                                    </div>
                                    @endif
                                    @if(($multi['tipodepago']==1||$multi['tipodepago']==2||$multi['tipodepago']==6)&&$pack['precioreserva']>0)
                                    <div class="col1-12 col-md-12 text-center mt-4">
                                        <button wire:click="confirmar_reserva" type="button" class="btn btn-danger text-start">
                                            Confirmar{{$pack['precioreserva']>0?' y realizar el pago':''}}</button>
                                    </div>
                                    @endif
                                    @if($multi['tipodepago']==5&&$pack['precioreserva']>0)
                                    <div class="col1-12 col-md-12 text-center mt-4">
                                        <script src="https://js.stripe.com/v3/"></script>
                                        <button wire:click="confirmar_reserva" class="btn btn-danger text-start">Finalizar y
                                            realizar el pago con Stripe</button>
                                    </div>
                                    @endif
                                    @if($multi['tipodepago']==4&&$pack['precioreserva']>0)
                                    <div class="col1-12 col-md-12 text-center mt-4">
                                        <script src="https://js.stripe.com/v3/"></script>
                                        <button wire:click="confirmar_reserva" class="btn btn-danger text-start">Finalizar y
                                            realizar el pago con Paypal</button>
                                    </div>
                                    @endif
                                    @if($multi['tipodepago']==3&&$pack['precioreserva']>0)
                                    <div class="col1-12 col-md-12 text-center mt-4">
                                        <form name='formulariopago' action='{{ $redsys['rutaredsys'] }}' method='post'>
                                            <input type='hidden' name='Ds_SignatureVersion' value='HMAC_SHA256_V1' />
                                            <input type='hidden' name='Ds_MerchantParameters' value='{{ $redsys['tpvredsysMerchantParameters256'] }}' />
                                            <input type='hidden' name='Ds_Signature' value='{{ $redsys['tpvredsysSignature256'] }}' />
                                            <button wire:click="confirmar_reserva" type="button" class="btn btn-danger text-start" aria-label="Left Align">
                                                Finalizar y realizar el pago con tarjeta de crédito
                                            </button>
                                        </form>
                                    </div>
                                    @endif

                                    @if($pack['precioreserva']>0)
                                    <div class="form-group">
                                        <label>Seleccione la forma de pago:</label>
                                        @if($efectivo)
                                        <div class="form-check">
                                            <input wire:model.live="multi.tipodepago" class="form-check-input" type="radio"
                                                value='1' id="f1" wire:change="fpago()">
                                            <label class="form-check-label" for="f1">
                                                Efectivo
                                            </label>
                                        </div>
                                        @endif
                                        @if($transferencia)
                                        <div class="form-check">
                                            <input wire:model.live="multi.tipodepago" class="form-check-input" type="radio"
                                                value='2' id="f2" wire:change="fpago()">
                                            <label class="form-check-label" for="f2">
                                                Transferencia bancaria a cuenta {{$iban}}
                                            </label>
                                        </div>
                                        @endif
                                        @if($tfredsys)
                                        <div class="form-check">
                                            <input wire:model.live="multi.tipodepago" class="form-check-input" type="radio"
                                                value='3' id="f3" wire:change="fpago()">
                                            <label class="form-check-label" for="f3">
                                                Redsys (tarjeta de crédito)
                                            </label>
                                        </div>
                                        @endif
                                        @if($paypal)
                                        <div class="form-check">
                                            <input wire:model.live="multi.tipodepago" class="form-check-input" type="radio"
                                                value='4' id="f4" wire:change="fpago()">
                                            <label class="form-check-label" for="f4">
                                                Paypal {{$formaspago->ppalprc>0?'(incremento '.$formaspago->ppalprc.'%)':''}}
                                            </label>
                                        </div>
                                        @endif
                                        @if($stripe)
                                        <div class="form-check">
                                            <input wire:model.live="multi.tipodepago" class="form-check-input" type="radio"
                                                value='5' id="f5" wire:change="fpago()">
                                            <label class="form-check-label" for="f5">
                                                Stripe {{$formaspago->stripeprc>0?'(incremento
                                                '.$formaspago->stripeprc.'%)':''}}
                                            </label>
                                        </div>
                                        @endif
                                        @if($bizum)
                                        <div class="form-check">
                                            <input wire:model.live="multi.tipodepago" class="form-check-input" type="radio"
                                                value='6' id="f6" wire:change="fpago()">
                                            <label class="form-check-label" for="f6">
                                                Bizum (teléfono para pago: {{$formaspago->bizumtelefono}})
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                            </div>
                            <div class="col-12 col-md-6">
                                @if ($servicio['pregunta1'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr1e'" :model="'servicio.respuesta1'" :titulo="$servicio['pregunta1'] .
                                            ($servicio['obliga1'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta2'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr2e'" :model="'servicio.respuesta2'" :titulo="$servicio['pregunta2'] .
                                            ($servicio['obliga2'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta3'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr3e'" :model="'servicio.respuesta3'" :titulo="$servicio['pregunta3'] .
                                            ($servicio['obliga3'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta4'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr4e'" :model="'servicio.respuesta4'" :titulo="$servicio['pregunta4'] .
                                            ($servicio['obliga4'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta5'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr5e'" :model="'servicio.respuesta5'" :titulo="$servicio['pregunta5'] .
                                            ($servicio['obliga5'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                
                                
                                @if ($servicio['pregunta6'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr6e'" :model="'servicio.respuesta6'" :titulo="$servicio['pregunta6'] .
                                            ($servicio['obliga6'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta7'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr7e'" :model="'servicio.respuesta7'" :titulo="$servicio['pregunta7'] .
                                            ($servicio['obliga7'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta8'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr8e'" :model="'servicio.respuesta8'" :titulo="$servicio['pregunta8'] .
                                            ($servicio['obliga8'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta9'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr9e'" :model="'servicio.respuesta9'" :titulo="$servicio['pregunta9'] .
                                            ($servicio['obliga9'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif
                                @if ($servicio['pregunta10'])
                                    <x-input :tipo="'name'" :col="12" :colmd="12"
                                        :idfor="'inombr10e'" :model="'servicio.respuesta10'" :titulo="$servicio['pregunta10'] .
                                            ($servicio['obliga10'] ? ' (respuesta obligatoria)' : '')" :disabled="''"
                                        :maxlen="'250'" :change="''" />
                                    <div class="">&nbsp;</div>
                                @endif


                            </div>

                        </div>
                    </div>
                </div>




            </div>
            <div class="modal-footer justify-content-between">
                <button wire:click="anular_prereserved2" type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">Cancelar</button>
                <div>
                    @if($pagpago==2||$pagpago==30)
                        <button wire:click="paginareserva(-1)" type="button" class="btn botonoh_naranja"><i class="material-icons">arrow_back</i>&nbsp;Anterior</button>
                    @endif
                    @if($pagpago==1||$pagpago==2)
                        <button wire:click="paginareserva(1)" type="button" class="btn botonoh_naranja">Siguiente&nbsp;<i class="material-icons">arrow_forward</i></button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
<script></script>
