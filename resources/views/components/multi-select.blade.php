@props(['options' => [], 'placeholderValue' => '', 'model'])

@php
    $uniqId = 'select' . uniqid();
@endphp

<div wire:ignore>
    <select class="multipleselector"
        tabindex="10000" style="z-index:10000"
        x-ref="{{ $uniqId }}"
        wire:change="$set('{{ $model }}', choices.getValue(true))"
        {{ $attributes }}
        multiple>
    </select>

    <script>
        // choices
        var element;
        var choices;
        document.addEventListener('livewire:initialized', function() {
            cargacho();
        });

        window.addEventListener('populatechoices', event => {
            //console.log("clear set");
            choices.clearChoices();
            choices.clearStore();
            choices.setChoices(JSON.parse(event.detail[0].choices));
            /*
            choices.setChoices(
                [
                    { value: 'One', label: 'Label One', disabled: true },
                    { value: 'Two', label: 'Label Two', selected: true },
                    { value: 'Three', label: 'Label Three' },
                ],
                'value',
                'label',
                false,
                );
            */
            //calendar.getEventById(event.detail[0].id).remove();
        });



        function cargacho() {
            element = document.querySelector('.multipleselector');
            choices = new Choices(element,{
                    itemSelectText: '',
                    searchEnabled: false,
                    searchChoices: false,
                    removeItems: true,
                    allowHTML: true,
                    removeItemButton: true,
                    noChoicesText: 'no hay opciones para seleccionar',
                    placeholderValue: '{{ $placeholderValue }}',
                    //choices:['foo', 'bar','caracter']
            });
            return;
            choices.clearChoices();
            choices.setChoices(
                [
                    { value: 'One', label: 'Label One', disabled: true },
                    { value: 'Two', label: 'Label Two', selected: true },
                    { value: 'Three', label: 'Label Three' },
                ],
                'value',
                'label',
                false,
                );

        };
    </script>


</div>