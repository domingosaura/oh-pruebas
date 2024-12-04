<div class="card mt-5 {{$tag['selected']?'fondoseleccion':''}}" xxdata-animation="true" style="">
    <div class="card-header p-0 position-relative mt-n4 mx-3 xz-index-2" xxoncontextmenu="return false;">
        <a class="d-block blur-shadow-image spotlight" data-caption="carat"
            href="{{ Storage::url('tmpgallery/'.$tag['file']) }}" 
            target="_blank"
            id="cx{{ $tag['nombre'] }}"
            data-title="false"
            data-button="{{$galeria[$keyy]['selected']?'desmarcar':'marcar'}}" 
            data-button-href="javascript:marcarimagenjs({{$keyy}},this)">
            <img src=
                @if(Session('soporteavif'))
                "{{ Storage::url('tmpgallery/'.$tag['file']) }}"
                @else
                "{{ Utils::inMacGallery($tag['file']) }}"
                @endif
                class="img-fluid shadow border-radius-xl"
                alt="{{$tag['file']}}"
                title="{{ $tag['nombre'] }}"
                style="pointer-events:auto !important" id="c{{ $tag['nombre'] }}" 
                wire:key="c{{ $tag['nombre'] }}" oncontextmenu="return false;">
        </a>
    </div>
    <div class="card-body text-center">
        <!--
            <div class="mt-n6 mx-auto oculto">
                <button class="btn bg-gradient-primary btn-sm mb-0 me-2" type="button"
                    name="button">Edit</button>
                <button class="btn btn-outline-dark btn-sm mb-0" type="button"
                    name="button">Remove</button>
            </div>
            <h5 class="font-weight-normal mt-0 puntero" wire : click="marcarimagen({ { $keyy } })">
                <i class="material-icons text-lg"> { {$tag['selected']?'check_box':'check_box_outline_blank'} } </i>
            </h5>
        -->
        <div class="row">
            @if(1==2)
            <div class="col-12 text-center">
                <input type="checkbox" wire:model="galeria.{{$keyy}}.selected" id="galeria.{{$keyy}}.id"
                    wire:change="marcarimagen({{$keyy}})" class="aform-check-input" style="width:30px;height:30px;"
                    {{$pagado||$seleccionconfirmada?'disabled':''}}>
            </div>
            @endif
            @if($ficha->nombresfotos)
            <div class="col-1 text-center"></div>
            <div class="col-10 text-center mb-2">{{$tag['nombre']}}</div>
            <div class="col-1 text-center"></div>
            @endif
            <div class="col-5 text-center"></div>
            <div class="col-2 text-center">
                @if($pagado||$seleccionconfirmada)
                <img wire:click="marcarimagennocheck({{$keyy}})" src="{{ asset('assets') }}/images/{{$galeria[$keyy]['selected']?'filled':'empty'}}.png"
                    alt="Marcado" class="img-fluid" />
                @else
                <img wire:click="marcarimagennocheck({{$keyy}})"
                    src="{{ asset('assets') }}/images/{{$galeria[$keyy]['selected']?'filled':'empty'}}.png"
                    alt="Marcado" class="img-fluid puntero" />
                @endif
            </div>
            <div class="col-5 text-center"></div>
        </div>


        <p class="mb-0 xxpuntero mt-2">
            @if($permitircomentarios==1)
            <!--siempre-->
            <i class="material-icons text-lg">edit_note</i>
            <input type="text" placeholder="notas" title="{{$tag['anotaciones']}}" class="inputnosquare" style="width:90%"
                wire:model="galeria.{{$keyy}}.anotaciones" wire:change="notas({{$keyy}})">
            @endif
            @if($permitircomentarios==2&&$galeria[$keyy]['selected'])
            <!--seleccionadas-->
            <i class="material-icons text-lg">edit_note</i>
            <input type="text" placeholder="notas" title="{{$tag['anotaciones']}}" class="inputnosquare" style="width:90%"
                wire:model="galeria.{{$keyy}}.anotaciones" wire:change="notas({{$keyy}})">
            @endif
            @if($permitircomentarios==3&&!$galeria[$keyy]['selected'])
            <!--no seleccionadas-->
            <i class="material-icons text-lg">edit_note</i>
            <input type="text" placeholder="notas" title="{{$tag['anotaciones']}}" class="inputnosquare" style="width:90%"
                wire:model="galeria.{{$keyy}}.anotaciones" wire:change="notas({{$keyy}})">
            @endif
            @if($permitircomentarios==4)
            <!--nunca-->
            @endif





            @if(($ficha->pagado && $ficha->permitirdescarga==1)||$ficha->permitirdescarga==3)
                <div class="xd-flex align-items-center float-end">
                    @if($tag['selected']||$ficha->permitirdescarga==3)
                    <i class="material-icons negro puntero" title="descargar imagen"
                        wire:click="descargarimagenporcliente({{$keyy}})">vertical_align_bottom</i>
                    @else
                    <i class="material-icons gris puntero" title="imagen no seleccionada no se puede descargar">vertical_align_bottom</i>
                    @endif
                </div>
            @endif








        </p>






    </div>
</div>