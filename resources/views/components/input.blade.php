<div class="form-group col-{{$col}} col-md-{{$colmd}}">
    <label for="{{$idfor}}">{{$titulo}}</label>
    <input 
        wire:model.blur="{{$model}}" 
        type="{{$tipo}}" 
        class="form-control border border-2 p-2" 
        id="{{$idfor}}" 
        placeholder="{{$titulo}}"
        @if(strlen($change)>0)
        wire:change="{{$change}}"
        @endif
        @if($type="number")
        step="any"
        @endif

        @if(strlen($maxlen)>0)
        maxlength="{{$maxlen}}"
        @endif
        onfocus="this.select()"
        {{$disabled}}
        >
        @if(isset($subtitulo))
        <p style="font-size:small">{{$subtitulo}}</p>
        @endif
    @error($model)
    <p class='text-danger inputerror'>{{ $message }} </p>
    @enderror
</div>
