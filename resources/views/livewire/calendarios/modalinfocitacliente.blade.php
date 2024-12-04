<div class="modal fade" id="infocitacliente" tabindex="9999" style="z-index:9999" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="row modal-header">
                <div class="col-12 text-center">
                    <h4 class="modal-title">{{ $multi['title'] }}</h4>
                    <h5 class="modal-title">{{ $multi['descripcion'] }}</h5>
                </div>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <div class="row text-start">
                        <h6 class="modal-title">Opciones a elegir en la reserva para {{$multi['inicio']}}:</h6>
                    </div>




                    @foreach ($servicios as $key => $tag)
                        <div class="row border aborder-light border-2 border-radius-md py-3 mt-4 text-center"
                            id="fprod{{ $tag['id'] }}">
                            <div class="col-12 col-md-3">
                                <h5>{{ $tag['nombre'] }}</h5>
                                    <img src=
                                    @if (strlen($tag['binario']) == 0) 
                                        "/oh/img/gallery-generic.jpg"
                                    @else
                                        @if(Session('soporteavif'))
                                            "data:image/jpeg;base64,{{ $tag['binario'] }}"
                                        @else
                                            "{{ Utils::inMacBase64($tag['binario']) }}"
                                        @endif
                                    @endif
                                    class="img-fluid shadow border-radius-xl">


                                   {!! $tag['anotaciones'] !!}
                            </div>
                            <div class="col-12 col-md-9 text-start">

                                @foreach ($tag['packs'] as $key2 => $tag2)

                                    <div class="row border aborder-light border-2 border-radius-md py-3 mb-2 text-center">
                                    <div class="col-6 col-md-3">
                                            <img src=
                                            @if (strlen($tag2['binario']) == 0) 
                                                "/oh/img/gallery-generic.jpg"
                                            @else
                                                @if(Session('soporteavif'))
                                                    "data:image/jpeg;base64,{{ $tag2['binario'] }}"
                                                @else
                                                    "{{ Utils::inMacBase64($tag2['binario']) }}"
                                                @endif
                                            @endif
                                            class="img-fluid shadow border-radius-xl">

                                    </div>
                                    <div class="col-12 col-md-9 mt-2 text-start">
                                        <h5>{{ $tag2['nombre'] }}</h5>
                                        @if(!$tag2['disponible'])
                                        <p class="rojo">lo sentimos, este servicio ya no está disponible en esta fecha</p>
                                        @endif
                                        {!! $tag2['anotaciones'] !!}
                                        <p>duración de la sesión: {{ $tag2['minutos'] }} minutos</p>
                                        <p>precio de la sesión: {{ $tag2['preciopack'] }} &euro;</p>
                                        <p>precio de la reserva: {{ $tag2['precioreserva'] }} &euro;</p>
                                        @if($tag2['sinfecha']==1)
                                        <p>permite reservar sin fecha</p>
                                        @endif
                                    </div>
                                </div>
    

                                @endforeach



                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-start" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="comenzar_reserva" type="button" class="btn btn-success fondonaranjito">Hacer
                    reserva</button>
            </div>
        </div>
    </div>
</div>
<script></script>
