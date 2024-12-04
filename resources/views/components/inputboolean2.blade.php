<div class="col-{{$col}} col-md-{{$colmd}} mt-4">
      <div class="form-group form-check">
        <input 
        wire:model.blur="{{$model}}" 
        type="checkbox" 
        class="form-check-input " 
        id="{{$idfor}}" 
        placeholder="{{$titulo}}"

        @if(strlen($change)>0)
        wire:change="{{$change}}"
        @endif

        {{$disabled}}
        >
        <label class="form-check-label" for="{{$idfor}}">
            {{$titulo}}
        </label>
    @error($model)
    <p class='text-danger inputerror'>{{ $message }} </p>
    @enderror
</div>
</div>
