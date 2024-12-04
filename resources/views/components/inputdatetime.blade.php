<div class="form-group col-{{$col}} col-md-{{$colmd}}" >
    <label for="{{$idfor}}">{{$titulo}}</label>
    <input 
        wire:model.blur="{{$model}}" 
        wireignore
        type="datetime" 
        class="form-control border border-2 p-2" 
        id="{{$idfor}}" 
        placeholder="{{$titulo}}"
        @if(strlen($change)>0)
        wire:change="{{$change}}"
        @endif

        @if(strlen($maxlen)>0)
        onclick="flatp.setDate('{{$maxlen}}')"
        maxlength="{{$maxlen}}"
        @endif
        {{$disabled}}

        >
    @error($model)
    <p class='text-danger inputerror'>{{ $message }} </p>
    @enderror
    <script>
var flatp;
document.addEventListener('livewire:initialized', function() {
    flatp=flatpickr("#{{$idfor}}", {
                //defaultDate: [{{date('Y-m-d')}}],
                enableTime: true,
                time_24hr: true,
                disableMobile: true,
                //defaultDate: "2024-10-25 09:20",
                dateFormat: "d/m/Y H:i",
                "locale": {
                    "firstDayOfWeek": 1 // start week on Monday
                },
            });
});
    </script>
</div>
