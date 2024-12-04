<div class="row border aborder-light border-2 border-radius-md py-3 mt-4 text-center" id="fserv{{ $tag['id'] }}">
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
            <a rel="tooltip" class="mt-3" href="{{ route('edit-sesiones', $tag['id']) }}" data-original-title=""
            title="Editar">
            <i class="material-icons negro puntero">edit</i>
        </a>
        <br/>
        <button type="button" class="btn botonoh_negro mt-2" data-original-title="" title="Eliminar"
            onclick="confirm('¿Seguro que desea eliminar este servicio?') || event.stopImmediatePropagation()"
            wire:click="deleteservicio({{ $tag['id'] }})">
            <i class="material-icons">delete_forever</i>
        </button>
        </div>
    </div>
    <div class="col-12 col-md-9 text-start ql-editor quillnomargen">
        <h5>{{ $tag['nombre'] }} <p>{{ $tag['nombre'] }}</p>
        </h5>
        <p>Descripción: {!! $tag['anotaciones'] !!}</p>
        <p>Notas internas: {!! $tag['anotaciones2'] !!}</p>


    </div>
</div>
