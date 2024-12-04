<div class="row border aborder-light border-2 border-radius-md py-3 mt-4 text-center" id="fprod{{$tag['id']}}">
    <div class="col-12 col-md-3">
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

            <div class="row">
            <a rel="tooltip" class="mt-3" href="{{ route('edit-packs', $tag['id']) }}" data-original-title=""
            title="Editar">
            <i class="material-icons negro puntero">edit</i>
        </a>
        <br/>
        <button type="button" class="btn botonoh_negro mt-3" data-original-title="" title="Eliminar"
            onclick="confirm('¿Seguro que desea eliminar este pack?') || event.stopImmediatePropagation()"
            wire:click="deletepack({{ $tag['id'] }})">
            <i class="material-icons">delete_forever</i>
        </button>
    </div>
    </div>
    <div class="col-12 col-md-9 text-start">
        <h5>{{$tag['nombre']}}</h5>
        <h6>Precio pack: <strong>{{$tag['preciopack']}}</strong> &euro;</h6>
        <h6>Precio reserva: <strong>{{$tag['precioreserva']}}</strong> &euro;</h6>
        <h6>Duración: <strong>{{$tag['minutos']}}</strong> minutos</h6>
        <h6>reserva sin fecha: <strong>{{$tag['sinfecha']?'SI':'NO'}}</strong></h6>
        <div class=" ql-editor quillnomargen">
        <p>Notas cliente: {!!$tag['anotaciones']!!}</p>
        <p>Notas internas: {!!$tag['anotaciones2']!!}</p>
        </div>


    </div>
</div>