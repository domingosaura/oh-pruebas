<div class="row border aborder-light border-2 border-radius-md py-3 mt-4 text-center {{ $productoseleccionid == $key ? 'fondoseleccion' : '' }}"
id="fprod{{ $key }}" wire:key="fprod{{ $key }}">
    <div class="col-12 col-md-3">

        @if ($origen == 'galeriacliente'||1==1)
            <img src="{{$tag['imagen']}}"
            class="img-fluid shadow border-radius-xl">
        @else
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
        @endif

        @if ($origen == 'galeria' || $origen == 'plantilla')
            <i class="material-icons negro puntero" title="mover para arriba"
                wire:click="moveprod({{ $tag['id'] }},{{ $tag['incluido']?1:0 }},{{ $tag['position'] }},1)">keyboard_arrow_up</i>
        <button type="button" class="btn botonoh_negro mt-3" data-original-title="" title="Eliminar"
                onclick="confirm('¿Seguro que desea eliminar este producto?') || event.stopImmediatePropagation()"
                wire:click="deleteproducto({{ $tag['id'] }})">
                <i class="material-icons">delete_forever</i>
            </button>
        <i class="material-icons negro puntero" title="mover para abajo"
            wire:click="moveprod({{ $tag['id'] }},{{ $tag['incluido']?1:0 }},{{ $tag['position'] }},2)">keyboard_arrow_down</i>
        @endif
        <div>&nbsp;</div>



        <img wire:click="marcarproducto({{ $key }})" style="width:25%"
            src="{{ asset('assets') }}/images/{{ $tag['seleccionada'] ? 'filled' : 'empty' }}.png" alt="Marcado"
            class="img-fluid puntero" />




        <div>&nbsp;</div>

        @if ($tag['numfotos'] > 0 && $tag['fotosdesde'] > 0 && ($origen == 'galeria' || $origen == 'galeriacliente'))
            <button type="button" class="btn btn-dark mt-3 text-center"
                wire:click="cargarselecciondefotosproducto({{ $key }})"
                data-bs-target="#seleccionfotos{{ $key }}" data-bs-toggle="modal"
                title="Ver las fotografías seleccionadas por el cliente">
                <i class="material-icons">visibility</i>&nbsp;Selección
            </button>
        @endif


    </div>
    <div class="col-12 col-md-6 text-start">
        <h6>{{ $tag['nombre'] }}</h6>
        @if ($tag['numfotos'] == 0 || $tag['fotosdesde'] == 0)
            <p>Este producto no necesita selección de fotos</p>
        @endif
        @if ($tag['numfotos'] > 0)
            <p>Haga una selección de {{ $tag['numfotos'] }} fotografías
                @if ($tag['fotosdesde'] == 1)
                    de cualquiera de las fotografías la galería
                @endif
                @if ($tag['fotosdesde'] == 2)
                    de las fotografías que ha seleccionado para la galería
                @endif
                @if ($tag['fotosdesde'] == 3)
                    de las fotografías que NO ha seleccionado para la galería
                @endif
            </p>

            @if ($tag['numfotosadicionales'] > 0)

            <p>Puede seleccionar hasta {{ $tag['numfotosadicionales'] }} fotografías adicionales por {{ $tag['preciofotoadicional'] }}&euro; cada una</p>

            @endif

            @endif

        @if ($tag['permitecantidad'] && $tag['incluido'] == false)
            
            <div class="d-flex col-6 col-md-4">
                @if(!$seleccionconfirmada && !$pagado && !$pagadomanual)
                    <button type="button" class="btn botonoh_negro text-center" data-original-title=""
                    title="" wire:click="marcarproductocantidad({{ $key }},1)">
                    <i class="material-icons">remove</i>
                    </button>
                    &nbsp;&nbsp;<h5>{{$productos[$key]['cantidad']}}</h5>&nbsp;&nbsp;
                    <button type="button" class="btn botonoh_negro text-center" data-original-title=""
                    title="" wire:click="marcarproductocantidad({{ $key }},2)">
                    <i class="material-icons">add</i>
                    </button>
                @else
                    <h5>Cantidad: {{$productos[$key]['cantidad']}}</h5>
                @endif
            </div>

            @if($tag['incluido']==false)
            <p>El precio de este producto es de <strong>{{ $tag['precioproducto'] }}</strong> &euro; por unidad</p>
            @endif
        @else
        @if($tag['incluido']==false)
            <p>El precio de este producto es de <strong>{{ $tag['precioproducto'] }}</strong> &euro;</p>
        @endif
        @endif

        @if ($origen == 'galeria' || $origen == 'plantilla')
            @if (strlen($tag['txtopc1']) > 0)
                <p>Opción adicional seleccionable {{ $tag['txtopc1'] }} @if($tag['incluido']==false)por un importe de {{ $tag['precio1'] }} &euro;@endif
                </p>
            @endif
            @if (strlen($tag['txtopc2']) > 0)
                <p>Opción adicional seleccionable {{ $tag['txtopc2'] }} @if($tag['incluido']==false)por un importe de {{ $tag['precio2'] }} &euro;@endif
                </p>
            @endif
            @if (strlen($tag['txtopc3']) > 0)
                <p>Opción adicional seleccionable {{ $tag['txtopc3'] }} @if($tag['incluido']==false)por un importe de {{ $tag['precio3'] }} &euro;@endif
                </p>
            @endif
            @if (strlen($tag['txtopc4']) > 0)
                <p>Opción adicional seleccionable {{ $tag['txtopc4'] }} @if($tag['incluido']==false)por un importe de {{ $tag['precio4'] }} &euro;@endif
                </p>
            @endif
            @if (strlen($tag['txtopc5']) > 0)
                <p>Opción adicional seleccionable {{ $tag['txtopc5'] }} @if($tag['incluido']==false)por un importe de {{ $tag['precio5'] }} &euro;@endif
                </p>
            @endif
            @if (strlen($tag['pregunta1']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg1_{{ $key }}">{{ $tag['pregunta1'] }}{!! $tag['pre1obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta1" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre1obligatorio'] && strlen($productos[$key]['respuesta1']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg1_{{ $key }}" placeholder="{{ $tag['pregunta1'] }}"
                        wire:change="grabarespuesta({{ $key }},1)" maxlength="250" onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta2']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg2_{{ $key }}">{{ $tag['pregunta2'] }}{!! $tag['pre2obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta2" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre2obligatorio'] && strlen($productos[$key]['respuesta2']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg2_{{ $key }}" placeholder="{{ $tag['pregunta2'] }}"
                        wire:change="grabarespuesta({{ $key }},2)" maxlength="250" onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta3']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg3_{{ $key }}">{{ $tag['pregunta3'] }}{!! $tag['pre3obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta3" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre3obligatorio'] && strlen($productos[$key]['respuesta3']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg3_{{ $key }}" placeholder="{{ $tag['pregunta3'] }}"
                        wire:change="grabarespuesta({{ $key }},3)" maxlength="250" onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta4']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg4_{{ $key }}">{{ $tag['pregunta4'] }}{!! $tag['pre4obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta4" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre4obligatorio'] && strlen($productos[$key]['respuesta4']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg4_{{ $key }}" placeholder="{{ $tag['pregunta4'] }}"
                        wire:change="grabarespuesta({{ $key }},4)" maxlength="250" onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta5']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg5_{{ $key }}">{{ $tag['pregunta5'] }}{!! $tag['pre5obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta5" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre5obligatorio'] && strlen($productos[$key]['respuesta5']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg5_{{ $key }}" placeholder="{{ $tag['pregunta5'] }}"
                        wire:change="grabarespuesta({{ $key }},5)" maxlength="250" onclick="this.select()">
                </div>
            @endif
        @endif


        @if ($origen == 'galeriacliente')
            <p wire:key="anota-{{$key}}">{!! $tag['anotaciones'] !!}</p>
            @if (strlen($tag['txtopc1']) > 0)
                <div class="form-check">
                    <label class="form-check-label" for="acepto">
                        {{ $tag['txtopc1'] }} @if($tag['incluido']==false)({{ $tag['precio1'] }}&euro;)@endif
                    </label>
                    <input class="form-check-input check-naranja"
                        wire:click="marcaropcionadicional({{ $key }},1)" type="checkbox"
                        wire:model="productos.{{ $key }}.selopc1" id="topcc1_{{ $key }}">
                </div>
            @endif
            @if (strlen($tag['txtopc2']) > 0)
                <div class="form-check">
                    <label class="form-check-label" for="acepto">
                        {{ $tag['txtopc2'] }} @if($tag['incluido']==false)({{ $tag['precio2'] }}&euro;)@endif
                    </label>
                    <input class="form-check-input check-naranja"
                        wire:click="marcaropcionadicional({{ $key }},2)" type="checkbox"
                        wire:model="productos.{{ $key }}.selopc2" id="topcc2_{{ $key }}">
                </div>
            @endif
            @if (strlen($tag['txtopc3']) > 0)
                <div class="form-check">
                    <label class="form-check-label" for="acepto">
                        {{ $tag['txtopc3'] }} @if($tag['incluido']==false)({{ $tag['precio3'] }}&euro;)@endif
                    </label>
                    <input class="form-check-input check-naranja"
                        wire:click="marcaropcionadicional({{ $key }},3)" type="checkbox"
                        wire:model="productos.{{ $key }}.selopc3" id="topcc3_{{ $key }}">
                </div>
            @endif
            @if (strlen($tag['txtopc4']) > 0)
                <div class="form-check">
                    <label class="form-check-label" for="acepto">
                        {{ $tag['txtopc4'] }} @if($tag['incluido']==false)({{ $tag['precio4'] }}&euro;)@endif
                    </label>
                    <input class="form-check-input check-naranja"
                        wire:click="marcaropcionadicional({{ $key }},4)" type="checkbox"
                        wire:model="productos.{{ $key }}.selopc4" id="topcc4_{{ $key }}">
                </div>
            @endif
            @if (strlen($tag['txtopc5']) > 0)
                <div class="form-check">
                    <label class="form-check-label" for="acepto">
                        {{ $tag['txtopc5'] }} @if($tag['incluido']==false)({{ $tag['precio5'] }}&euro;)@endif
                    </label>
                    <input class="form-check-input check-naranja"
                        wire:click="marcaropcionadicional({{ $key }},5)" type="checkbox"
                        wire:model="productos.{{ $key }}.selopc5" id="topcc5_{{ $key }}">
                </div>
            @endif

            @if (strlen($tag['pregunta1']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg1_{{ $key }}">{{ $tag['pregunta1'] }}{!! $tag['pre1obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta1" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre1obligatorio'] && strlen($productos[$key]['respuesta1']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg1_{{ $key }}" placeholder="{{ $tag['pregunta1'] }}"
                        wire:change="grabarespuesta({{ $key }},1)" maxlength="250" onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta2']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg2_{{ $key }}">{{ $tag['pregunta2'] }}{!! $tag['pre2obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta2" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre2obligatorio'] && strlen($productos[$key]['respuesta2']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg2_{{ $key }}" placeholder="{{ $tag['pregunta2'] }}"
                        wire:change="grabarespuesta({{ $key }},2)" maxlength="250"
                        onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta3']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg3_{{ $key }}">{{ $tag['pregunta3'] }}{!! $tag['pre3obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta3" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre3obligatorio'] && strlen($productos[$key]['respuesta3']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg3_{{ $key }}" placeholder="{{ $tag['pregunta3'] }}"
                        wire:change="grabarespuesta({{ $key }},3)" maxlength="250"
                        onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta4']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg4_{{ $key }}">{{ $tag['pregunta4'] }}{!! $tag['pre4obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta4" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre4obligatorio'] && strlen($productos[$key]['respuesta4']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg4_{{ $key }}" placeholder="{{ $tag['pregunta4'] }}"
                        wire:change="grabarespuesta({{ $key }},4)" maxlength="250"
                        onclick="this.select()">
                </div>
            @endif
            @if (strlen($tag['pregunta5']) > 0)
                <div class="form-group col-12 col-md-12">
                    <label for="preg5_{{ $key }}">{{ $tag['pregunta5'] }}{!! $tag['pre5obligatorio'] ? '<strong class="rojo">&nbsp;*</strong>' : '' !!}</label>
                    <input wire:model.blur="productos.{{ $key }}.respuesta5" type="text"
                        class="form-control border border-2 p-2 {{ $tag['pre5obligatorio'] && strlen($productos[$key]['respuesta5']) < 3 ? 'fondotxtobligatorio' : '' }}"
                        id="preg5_{{ $key }}" placeholder="{{ $tag['pregunta5'] }}"
                        wire:change="grabarespuesta({{ $key }},5)" maxlength="250"
                        onclick="this.select()">
                </div>
            @endif

            @if ($tag['permitecantidad'] && $tag['incluido'] == false && $tag['numfotos'] > 0 && $tag['fotosdesde'] > 0)
                <a class="nav-link mb-0 px-0 py-1 puntero" data-bs-toggle="tab"
                    wire:click="clonarproducto({{ $key }})">
                    <i class="material-icons text-lg position-relative">add</i>
                    <span class="ms-1">Añadir otra unidad de este producto con diferentes fotografías</span>
                </a>
            @endif

        @endif
    </div>
    <div class="col-12 col-md-3 mt-3 text-start">

        @if ($origen == 'galeria' || $origen == 'plantilla')
            @if (strlen($tag['txtopc1']) > 0)
                <p><input type="checkbox" wire:click="marcaropcionadicional({{ $key }},1)"
                        class="check-naranja" wire:model="productos.{{ $key }}.selopc1">&nbsp;¿opción 1
                    marcada?</input></p>
            @endif
            @if (strlen($tag['txtopc2']) > 0)
                <p><input type="checkbox" wire:click="marcaropcionadicional({{ $key }},2)"
                        class="check-naranja" wire:model="productos.{{ $key }}.selopc2">&nbsp;¿opción 2
                    marcada?</input></p>
            @endif
            @if (strlen($tag['txtopc3']) > 0)
                <p><input type="checkbox" wire:click="marcaropcionadicional({{ $key }},3)"
                        class="check-naranja" wire:model="productos.{{ $key }}.selopc3">&nbsp;¿opción 3
                    marcada?</input></p>
            @endif
            @if (strlen($tag['txtopc4']) > 0)
                <p><input type="checkbox" wire:click="marcaropcionadicional({{ $key }},4)"
                        class="check-naranja" wire:model="productos.{{ $key }}.selopc4">&nbsp;¿opción 4
                    marcada?</input></p>
            @endif
            @if (strlen($tag['txtopc5']) > 0)
                <p><input type="checkbox" wire:click="marcaropcionadicional({{ $key }},5)"
                        class="check-naranja" wire:model="productos.{{ $key }}.selopc5">&nbsp;¿opción 5
                    marcada?</input></p>
            @endif
        @endif
    </div>
    @include('livewire.galeria.galeria_producto_modal', ['origen' => $origen])
</div>
