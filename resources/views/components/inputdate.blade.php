<div class="form-group col-{{$col}} col-md-{{$colmd}}">
    <label for="{{$idfor}}">{{$titulo}}</label>
    <input 
        wire:model.blur="{{$model}}" 
        type="date" 
        class="form-control border border-2 p-2" 
        id="{{$idfor}}" 
        placeholder="{{$titulo}}"

        @if(strlen($maxlen)>0)
        maxlength="{{$maxlen}}"
        @endif
        {{$disabled}}

        >
    @error($model)
    <p class='text-danger inputerror'>{{ $message }} </p>
    @enderror
</div>
