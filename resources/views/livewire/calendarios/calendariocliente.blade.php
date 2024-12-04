<div class="container-fluid py-2 bg-gray-200">
    <link href="{{ asset('assets/schedulex') }}/indexsc.css" rel="stylesheet" />

    <div class="row gx-4 ">
        <div class="col-2 mb-3">
            <div class="xxavatar xxavatar-xl position-relative">
                <a href="{{ route('reservas',[$userid,$calendarid,$md5]) }}">
                <img src="
                    @if (strlen($logo) == 0) /oh/img/micuentacirculo.jpg
                    @else
                    data:image/jpeg;base64,{{ $logo }} @endif
                "
                    alt="logo" class="w-100 xxrounded-circle xxshadow-sm" />
                </a>
            </div>
        </div>
        <div class="col-auto my-auto">
            <h5 class="mb-1">
                {{ $empresa == '' ? '' : $empresa }}
            </h5>
        </div>
        <div class="col my-auto text-end">
            <p class="mb-0 font-weight-normal text-sm text-end">
                {{ $telefonoempresa }}<br />
                {{ $emailempresa }}
            </p>
        </div>
    </div>

    @if ($stage == 1)
        <div class="row text-center">
            <div class="col-12 col-md-12 mt-0">
                <img src=@if (strlen($calendarbin) == 0) "/oh/img/gallery-generic.jpg"
        @else
            @if (Session('soporteavif'))
                "data:image/jpeg;base64,{{ $calendarbin }}"
            @else
                "{{ Utils::inMacBase64($calendarbin) }}" @endif
                    @endif
                class="img-fluid shadow-lg border-radius-xl">

            </div>
            <div class="col-12 col-md-12 mt-2">
                <h4 class="mb-0">{{ $calendartitle }}</h4>
            </div>
            <div class="col-12 col-md-12 mt-2">
                @if ($permitereserva == 1)
                    <button wire:click="comenzarReserva" class="btn xbtn-dark fondonaranjito blanco text-center">Hacer
                        una reserva</button>
                @endif
                @if ($permitereserva == -1)
                    <h4>Lo sentimos, las reservas están deshabilitadas temporalmente</h4>
                    <h6>Reintente en unos minutos ó consúltenos</h6>
                @endif
                @if ($permitereserva == -2)
                    <h4>Lo sentimos, este calendario está cancelado</h4>
                    <h6>Consúltenos para solicitar su cita</h6>
                @endif
                @if ($permitereserva == -3)
                    <h4>Lo sentimos, este calendario no tiene ningún servicio disponible</h4>
                    <h6>Consúltenos para solicitar su cita</h6>
                @endif
            </div>
        </div>
    @endif

    @if ($stage == 2)
        <div class="row">
            <div class="col-12 text-center">
                <h5>Seleccione el tipo de sesión que quiere reservar:</h5>
            </div>
            @foreach ($servicios as $key => $tag)
                <div class="col-xl-3 col-md-6 mb-2 mt-1">
                    <div class="card Xcard-blog Xcard-plain puntero" wire:click="seleccionarservicio({{$key}})">
                        <div class="card-header p-0 mt-2 mx-3">
                                <img src=
                                @if (strlen($tag['binario']) == 0)
                                    "/oh/img/gallery-generic.jpg"
                                @else
                                    @if (Session('soporteavif'))
                                        "data:image/jpeg;base64,{{ $tag['binario'] }}"
                                    @else
                                        "{{ Utils::inMacBase64($tag['binario']) }}"
                                    @endif
                                @endif
                                class="img-fluid shadow-lg border-radius-xl">
                        </div>
                        <div class="card-body p-3">
                            <h5>
                                    {{ $tag['nombre'] }}&nbsp;
                                    <p>{{ $tag['txminutos'] }}</p>
                                    <p>{!! $tag['anotaciones'] !!}</p>
                            </h5>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button wire:click="volver" class="btn xbtn-dark fondonaranjito blanco text-center bottomleft mb-6">
            <i class="material-icons">arrow_back</i>atrás</button>
    @endif

    @if ($stage == 3)
        <div class="row">
            <div class="col-1 text-center">
            </div>
            <div class="col-3 text-center">
                <img src=
                @if (strlen($servicio['binario']) == 0)
                    "/oh/img/gallery-generic.jpg"
                @else
                    @if (Session('soporteavif'))
                        "data:image/jpeg;base64,{{ $servicio['binario'] }}"
                    @else
                        "{{ Utils::inMacBase64($servicio['binario']) }}"
                    @endif
                @endif
                class="img-fluid shadow-lg border-radius-xl">
            </div>
            <div class="col-7 text-start">
                <h5>{{$servicio['nombre']}}</h5>
                <h6>{{$servicio['txminutos']}}</h6>
                <h6>{!!$servicio['anotaciones']!!}</h6>
            </div>
            <div class="col-12 text-center mt-1">
                <h5>Seleccione el pack que quiere reservar:</h5>
            </div>
            @foreach ($servicio['packs'] as $key => $tag)
                <div class="col-xl-3 col-md-6 mb-2 mt-1">
                    <div class="card Xcard-blog Xcard-plain puntero" wire:click="seleccionarpack({{$key}})">
                        <div class="card-header p-0 mt-2 mx-3">
                                <img src=
                                @if (strlen($tag['binario']) == 0)
                                    "/oh/img/gallery-generic.jpg"
                                @else
                                    @if (Session('soporteavif'))
                                        "data:image/jpeg;base64,{{ $tag['binario'] }}"
                                    @else
                                        "{{ Utils::inMacBase64($tag['binario']) }}"
                                    @endif
                                @endif
                                class="img-fluid shadow-lg border-radius-xl">
                        </div>
                        <div class="card-body p-3">
                            <h5>
                                    {{ $tag['nombre'] }}&nbsp;
                                    <p>Duración: {{ $tag['minutos'] }} minutos</p>
                                    <p>{!! $tag['anotaciones'] !!}</p>
                            </h5>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button wire:click="volver" class="btn xbtn-dark fondonaranjito blanco text-center bottomleft mb-6">
            <i class="material-icons">arrow_back</i>atrás</button>
    @endif

    <div class="{{ $stage == 4 ? 'visible' : 'oculto2' }}">
        <div class="col-12 text-center mt-1">
            <h5>Fechas disponibles para {{$packtitle}}:</h5>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="card card-calendar">
                    <div class="card-body p-3">
                        <div class="calendar" data-bs-toggle="calendar" id="calendar" wire:ignore></div>
                    </div>
                </div>
                <button wire:click="volver" class="btn xbtn-dark fondonaranjito blanco text-center xxbottomleft mt-3">
                    <i class="material-icons">arrow_back</i>atrás</button>
            </div>
        </div>
    </div>
    @if ($stage == 4)
        @include('livewire.calendarios.modalconfirmarcitacliente')
    @endif




    @if(env('TESTMODE')&&$userid==-6)
    <p>{{ $browserid }}</p>
    @endif


    @if ($permitereserva == 1 && 1 == 2)
        @include('livewire.calendarios.modalpedircitacliente')
        @include('livewire.calendarios.modalinfocitacliente')
        @include('livewire.calendarios.modalconfirmarcitacliente')
    @endif
</div>










@push('js')
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/flatpickr.min.js"></script>

    <!--https://github.com/schedule-x/schedule-x-->
  <script src="{{ asset('assets/schedulex') }}/preact.min.js"></script>
  <script src="{{ asset('assets/schedulex') }}/hooks.umd.js"></script>
  <script src="{{ asset('assets/schedulex') }}/signals-core.min.js"></script>
  <script src="{{ asset('assets/schedulex') }}/signals.min.js"></script>
  <script src="{{ asset('assets/schedulex') }}/jsxRuntime.umd.js"></script>
  <script src="{{ asset('assets/schedulex') }}/compat.umd.js"></script>
  <script src="{{ asset('assets/schedulex') }}/core.umd.js"></script>
  <script src="{{ asset('assets/schedulex') }}/coredd.umd.js"></script>
  <script src="{{ asset('assets/schedulex') }}/corees.umd.js"></script>

    <script type="module">
        const { createCalendar, viewWeek,viewMonthGrid } = window.SXCalendar;
        const { createDragAndDropPlugin } = window.SXDragAndDrop;
        const { createEventsServicePlugin } = window.SXEventsService;

        const config = {
            views: [viewMonthGrid],
            defaultView: viewMonthGrid,
            locale: 'es-ES',
            firstDayOfWeek: 1,
            isResponsive: true,
            skipValidation: true,
            monthGridOptions: {
                /**
                 * Number of events to display in a day cell before the "+ N events" button is shown
                 * */
                nEventsPerDay: 800,
            },

            calendars: {
                disponible: {
                    colorName: 'disponible',
                    lightColors: {
                        main: '#9db6a0',
                        container: '#bfe2c3',
                        onContainer: 'black',
                    },
                    darkColors: {
                        main: '#fff5c0',
                        onContainer: '#fff5de',
                        container: '#a29742',
                    },
                },
                ocupado: {
                    colorName: 'ocupado',
                    lightColors: {
                        main: '#e3603b',
                        container: '#ec9880',
                        onContainer: 'black',
                    },
                    darkColors: {
                        main: '#fff5c0',
                        onContainer: '#fff5de',
                        container: '#a29742',
                    },
                },
                pteconfirmar: {
                    colorName: 'pteconfirmar',
                    lightColors: {
                        main: '#4fb2b6',
                        container: '#b6d0d1',
                        onContainer: 'black',
                    },
                    darkColors: {
                        main: '#fff5c0',
                        onContainer: '#fff5de',
                        container: '#a29742',
                    },
                },
                nodisponible: {
                    colorName: 'nodisponible',
                    lightColors: {
                        main: 'gray',
                        container: 'lightgray',
                        onContainer: 'black',
                    },
                    darkColors: {
                        main: '#fff5c0',
                        onContainer: '#fff5de',
                        container: '#a29742',
                    },
                },
            },

            callbacks: {
                onRangeUpdate(range) {
                console.log('new calendar range start date', range.start)
                console.log('new calendar range end date', range.end)
                },
                onEventUpdate(updatedEvent) {
                console.log('onEventUpdate', updatedEvent)
                },
                onEventClick(calendarEvent) {
                //console.log('onEventClick', calendarEvent)
                //alert(calendarEvent.id);
                @this.openeventclient(calendarEvent.id);
                //editarevento(calendarEvent.id);
                },
                onDoubleClickEvent(calendarEvent) {
                console.log('onDoubleClickEvent', calendarEvent)
                },
                onClickDate(date) {
                console.log('onClickDate', date) // e.g. 2024-01-01
                },
                onClickDateTime(dateTime) {
                console.log('onClickDateTime', dateTime) // e.g. 2024-01-01 12:37
                },
                onClickAgendaDate(date) {
                console.log('onClickAgendaDate', date) // e.g. 2024-01-01
                },
                onDoubleClickAgendaDate(date) {
                console.log('onDoubleClickAgendaDate', date) // e.g. 2024-01-01
                },
                onDoubleClickDate(date) {
                console.log('onClickDate', date) // e.g. 2024-01-01
                },
                onDoubleClickDateTime(dateTime) {
                console.log('onDoubleClickDateTime', dateTime) // e.g. 2024-01-01 12:37
                },
                onClickPlusEvents(date) {
                console.log('onClickPlusEvents', date) // e.g. 2024-01-01
                },
                onSelectedDateUpdate(date) {
                console.log('onSelectedDateUpdate', date)
                },
            },
        };

        var events= [
                {
                id: '1',
                title: 'Event 1',
                start: '2024-11-27 09:00',
                end: '2024-11-27 10:00',
                calendarId: "disponible",
                },
                {
                id: '2',
                title: 'Event 1',
                start: '2024-11-27 09:00',
                end: '2024-11-27 10:00',
                calendarId: "ocupado",
                },
            ];
          
        //const eventsServicePlugin = createEventsServicePlugin();
        const plugins = [
          //createDragAndDropPlugin(),
          createEventsServicePlugin(),
        ];
     
        const calendar = createCalendar(config, plugins);
        calendar.render(document.querySelector('.calendar'));
        //calendar.eventsService.add({
        //    title: 'Event 1',
        //    start: '2024-11-28',
        //    end: '2024-11-28',
        //    id: 1
        //});
        //calendar.eventsService.set(events);

        window.addEventListener('redrawallevents', event => {
            calendar.eventsService.set(JSON.parse(event.detail[0].eventos));
        });
        window.addEventListener('removeevent', event => {
            calendar.eventsService.remove(event.detail[0].id);
        });
        window.addEventListener('showmodalcita', event => {
            $('#confirmarcitacliente').modal('show');
        });
        window.addEventListener('closemodalconfirmarcitacliente', event => {
            $('#confirmarcitacliente').modal('hide');
        });
        window.addEventListener('submitredsys', event => {
            document.formulariopago.submit();
        });


    </script>
    


@endpush
