<div class="container-fluid py-4">
    <link href="{{ asset('assets/schedulex') }}/indexsc.css" rel="stylesheet" />
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <div class="row">
                        @if (Session::has('status'))
                            <div class="col-12">
                                <div class="alert alert-success-oh alert-dismissible text-white mx-4" role="alert">
                                    <span class="text-sm">{{ Session::get('status') }}</span>
                                    <button type="button" class="btn-close text-lg py-3 opacity-10"
                                        data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <!--<div class="col-8 text-start">-->
                        <!--<h5 class="mb-0">Calendario { { $ficha->nombre } }</h5>-->
                        <!--<p>Edit your tag</p>-->
                        <!--</div>-->
                        <!--<div class="col-4 text-end">
                            <a class="btn bg-gradient-dark mb-3 me-4"
                                href="{ { route('calendarios-management') } }">volver</a>
                        </div>-->

                        <div class="col-12 col-md-4 ">
                            <img src=
                            @if (strlen($ficha['binario']) == 0) 
                                "/oh/img/gallery-generic.jpg"
                            @else
                                @if(Session('soporteavif'))
                                    "data:image/jpeg;base64,{{ $ficha['binario'] }}"
                                @else
                                    "{{ Utils::inMacBase64($ficha['binario']) }}"
                                @endif
                            @endif
                            id="{{ $idfot }}" class="img-fluid shadow border-radius-xl">
                        </div>
                        <div class="col-12 col-md-6">
                            <h4 class="mb-0">Calendario {{ $ficha->nombre }}</h4>
                            <p class="mb-0">Total citas: {{ $totalcitas }}</p>
                            <p class="mb-0">Reservadas: {{ $totalcitasreservadas }}</p>
                            <p class="mb-0">Pendiente confirmación: {{ $pteconfirmar }}</p>
                            <p class="mb-0">No disponibles: {{ $nodisponible }}</p>
                            <p class="mb-0">Libres: {{ $libres }}</p>

                        <div class="">
                            <button class="btn btn-primary blanco mb-0 me-1 mt-1" wire:click="newevent()" title="pulse aquí para crear nuevas sesiones"><i
                                class="material-icons text-sm blanco">add</i>&nbsp;&nbsp;Crear citas</button>
                            <button class="btn btn-primary blanco mb-0 me-1 mt-1" data-bs-toggle="modal" data-bs-target="#pedircita"
                                title="enviar un enlace al cliente para reservar sesión en este calendario"><i
                                class="material-icons text-sm blanco">send</i>&nbsp;&nbsp;Enviar enlace</button>
                            <a href="{{$rutaaccesocliente}}" target="_blank" 
                                class="btn btn-primary blanco hoverblanco mb-0 me-1 mt-1" title="pulse aquí para abrir la ventana que verá el cliente"><i
                                class="material-icons text-sm blanco">visibility</i>&nbsp;&nbsp;Acceder como cliente</a>
                            </div>


                        </div>
                        <div class="col-12 col-md-2 text-end mt-0">
                            <a class="btn bg-gradient-dark mb-3 me-4"
                                href="{{ route('calendarios-management') }}">volver</a>
                        </div>
                    </div>
                </div>


                <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li></li>
                            <li class="nav-item" wire:click="vseccion(1)">
                                <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 1 ? 'naranjitobold' : '' }}"
                                    data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">settings</i>
                                    <span class="ms-1">Calendario</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion(2)">
                                <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 2 ? 'naranjitobold' : '' }}"
                                    data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">tune</i>
                                    <span class="ms-1">Listado de citas</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion(3)">
                                <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 3 ? 'naranjitobold' : '' }}"
                                    data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">list</i>
                                    <span class="ms-1">Configuración</span>
                                </a>
                            </li>
                            <li class="nav-item" wire:click="vseccion(4)">
                                <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 4 ? 'naranjitobold' : '' }}"
                                    data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                                    <i class="material-icons text-lg position-relative">photo_library</i>
                                    <span class="ms-1">Servicios disponibles</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="{{ $seccion == 1 ? 'visible' : 'oculto2' }}">
                        <div class="row mt-4">
                            <!--calendario-->
                            <div class="col-12 col-md-9" wire:ignore>
                                <div class="calendar" data-bs-toggle="calendar" id="calendar" wire:ignore></div>
                                <div class="mt-2">
                                <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: #bfe2c3;">disponible</span>
                                <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: #ec9880;">ocupado</span>
                                <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: #b6d0d1;">pendiente de confirmar</span>
                                <span style="border-radius: 5px; text-align: center; padding: 4px 8px; color: white; background-color: gray;">no disponible</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                               <!-- 
                                <div class="row">
                                    <div class="col-xl-12 col-md-6 mb-2 mt-2">
                                        <div class="card">
                                            <div class="card-header p-3 pb-0">
                                                <h6 class="mb-0">crear sesiones</h6>
                                            </div>
                                            <div class="card-body border-radius-lg p-3">
                                                <p wire : click="newevent()" NNdata-bs-toggle="modal" NNdata-bs-target="#evdfdfentomultiple"
                                                    class="mb-0 puntero">pulse aquí para crear nuevas sesiones</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-6 mb-2 mt-2">
                                        <div class="card">
                                            <div class="card-header p-3 pb-0">
                                                <h6 class="mb-0">enviar enlace a cliente</h6>
                                            </div>
                                            <div class="card-body border-radius-lg p-3">
                                                <p data-bs-toggle="modal" data-bs-target="#pedircita"
                                                    class="mb-0 puntero">
                                                    pulse aquí para
                                                    enviar un enlace al cliente para reservar sesión en este calendario
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-6 mb-2 mt-2">
                                        <div class="card">
                                            <div class="card-header p-3 pb-0">
                                                <h6 class="mb-0">acceder como cliente</h6>
                                            </div>
                                            <div class="card-body border-radius-lg p-3">
                                                <a href="{ {$rutaaccesocliente} }" target="_blank"><p class="mb-0 puntero">
                                                    pulse aquí para abrir la ventana que verá el cliente
                                                </p></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
                                <div class="row">
                                    <div class="col-xl-12 col-md-6 mb-2">
                                        <div class="card">
                                            <div class="card-header p-3 pb-0">
                                                <h6 class="mb-0">buscar sesiones</h6>
                                                <input id="caaab" wire:model="txbus" wire:key=""
                                                    wire:keyup="busqueda" onfocus="this.select()" type="text"
                                                    class="form-control border border-2 p-2 textnegro notext-end"
                                                    placeholder="" maxlength="20">
                                            </div>
                                            <div class="card-body border-radius-lg p-3">
                                                @foreach ($buscar as $event)
                                                    <div class="d-flex mt-4"
                                                        wire:click="openevent2({{ $event['id'] }})" role="button">
                                                        <div
                                                            class="icon icon-shape bg-gradient-dark shadow text-center">
                                                            <i class="material-icons opacity-10 pt-1">notifications</i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <div class="numbers">
                                                                <h6 class="mb-0 text-dark text-sm">
                                                                    {{ $event['title'] }}
                                                                </h6>
                                                                <span
                                                                    class="text-sm">{{ date('d/m/Y', strtotime($event['start'])) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-6 mb-2">
                                        <div class="card">
                                            <div class="card-header p-3 pb-0">
                                                <h6 class="mb-0">Próximas citas (7 días)</h6>
                                            </div>
                                            <div class="card-body border-radius-lg p-3">
                                                @foreach ($next as $event)
                                                    <div class="d-flex mt-4"
                                                        wire:click="openevent2({{ $event['id'] }})" role="button">
                                                        <div
                                                            class="icon icon-shape bg-gradient-{{ $event['cliente_id'] > 0 ? 'danger' : 'dark' }} shadow text-center">
                                                            <i class="material-icons opacity-10 pt-1">notifications</i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <div class="numbers">
                                                                <h6 class="mb-0 text-dark text-sm">
                                                                    {{ $event['title'] }}
                                                                </h6>
                                                                <span
                                                                    class="text-sm">{{ date('d/m/Y', strtotime($event['start'])) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 {{ $seccion == 2 ? 'visible' : 'oculto' }}">
                        <!--listado de citas-->



                        <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                            <div class="nav-wrapper position-relative" nowireignore>
                                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                    <li></li>
                                    <li class="nav-item" wire:click="mostrarsolo(1)">
                                        <a class="nav-link mb-0 px-0 py-1 active {{ $viscita == 1 ? 'naranjitobold' : '' }}"
                                            data-bs-toggle="tab" href="" role="tab" aria-selected="true">
                                            <i class="material-icons text-lg position-relative">apps</i>
                                            <span class="ms-1">Todos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="mostrarsolo(2)">
                                        <a class="nav-link mb-0 px-0 py-1 {{ $viscita == 2 ? 'naranjitobold' : '' }}"
                                            data-bs-toggle="tab" href="" role="tab"
                                            aria-selected="false">
                                            <i class="material-icons text-lg position-relative">close</i>
                                            <span class="ms-1">No reservado</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" wire:click="mostrarsolo(3)">
                                        <a class="nav-link mb-0 px-0 py-1  {{ $viscita == 3 ? 'naranjitobold' : '' }}"
                                            data-bs-toggle="tab" href="" role="tab"
                                            aria-selected="false">
                                            <i class="material-icons text-lg position-relative">favorite</i>
                                            <span class="ms-1">Reservado</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>



                        <x-table>
                            <x-slot name="head">
                                <x-table.heading>Título</x-table.heading>
                                <x-table.heading>Cliente</x-table.heading>
                                <x-table.heading>Inicio</x-table.heading>
                                <x-table.heading>Estado</x-table.heading>
                                <x-table.heading>Minutos</x-table.heading>
                                <x-table.heading>Acciones</x-table.heading>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($eventos as $tag)
                                    @if (($viscita == 3 && $tag['reservado']) || ($viscita == 2 && !$tag['reservado']) || $viscita == 1)
                                        <x-table.row wire:key="row-{{ $tag['id'] }}">
                                            <x-table.cell>
                                            <a wire:click="openevent2({{ $tag['id'] }})" title="Editar" class="puntero">{{ $tag['title2'] }}</a>
                                            </x-table.cell>
                                            <x-table.cell>{{ $tag['nombrecliente'] }}</x-table.cell>
                                            <x-table.cell>{{ Utils::datetime($tag['start']) }}</x-table.cell>
                                            <x-table.cell>
                                                <i class="material-icons {{ $tag['reservado'] ? 'negro' : 'gris' }}"
                                                    title="{{ $tag['reservado'] ? 'reservado' : 'no reservado' }}">{{ $tag['reservado'] ? 'today' : 'today' }}</i>
                                                <i class="material-icons {{ $tag['confirmado'] ? 'negro' : 'gris' }}"
                                                    title="{{ $tag['confirmado'] ? 'confirmado' : 'no confirmado' }}">{{ $tag['confirmado'] ? 'event_available' : 'event_available' }}</i>
                                                <i class="material-icons {{ $tag['pagado'] ? 'negro' : 'gris' }}"
                                                    title="{{ $tag['pagado'] ? 'pagado' : 'no pagado' }}">{{ $tag['pagado'] ? 'euro' : 'euro' }}</i>

                                                @if ($tag['reservado'])
                                                @else
                                                @endif
                                                @if ($tag['confirmado'])
                                                @else
                                                @endif
                                                @if ($tag['pagado'])
                                                @else
                                                @endif
                                            </x-table.cell>
                                            <x-table.cell>{{ $tag['minutos'] }}</x-table.cell>
                                            <x-table.cell>
                                                <a rel="tooltip" class="btn botonoh_negro"
                                                    wire:click="openevent2({{ $tag['id'] }})"
                                                    data-original-title="" title="Editar">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                <button type="button" class="btn botonoh_negro"
                                                    data-original-title="" title="Eliminar"
                                                    onclick="confirm('¿Eliminar esta sesión del calendario?.') || event.stopImmediatePropagation()"
                                                    wire:click="eliminarevento({{ $tag['id'] }})">
                                                    <i class="material-icons">delete_forever</i>
                                                </button>
                                            </x-table.cell>
                                        </x-table.row>
                                    @endif
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                    <div class="row mt-4 {{ $seccion == 3 ? 'visible' : 'oculto' }}">
                        <!--configuracion-->
                        <div class="col-12 col-md-4">
                            <p>Imagen del calendario</p>
                            <x-filepond wire:model="files" maxsize="27MB" resize="true" width="425"
                                height="283" w5sec="true" />
                        </div>
                        <form wire:submit="update" class='xxd-flex xxflex-column align-items-center'>
                            <div class="row">
                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'"
                                    :model="'ficha.nombre'" :titulo="'Nombre'" :disabled="''" :change="''"
                                    :maxlen="''" />
                                <x-input :tipo="'text'" :col="12" :colmd="12" :idfor="'idescri'"
                                    :model="'ficha.descripcion'" :titulo="'Descripción'" :disabled="''" :change="''"
                                    :maxlen="'200'" />
                                <x-inputboolean :model="'ficha.activo'" :titulo="'¿Calendario activo (visible)?'" :maxlen="''"
                                    :idfor="'ix'" :col="7" :colmd="7" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'ficha.permitereserva'" :titulo="'¿Reservas habilitadas? (permite bloquear temporalmente las reservas)'" :maxlen="''"
                                    :idfor="'ixdd'" :col="7" :colmd="7" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'ficha.mostrarreservadas'" :titulo="'En vista cliente, mostrar las citas reservadas'" :maxlen="''"
                                    :idfor="'ixddrs'" :col="7" :colmd="7" :disabled="''"
                                    :change="''" />

                                <x-inputboolean :model="'ficha.redsys'" :titulo="'Permite pagar reserva con Redsys'" :maxlen="''"
                                    :idfor="'irsys'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'ficha.paypal'" :titulo="'Permite pagar reserva con Paypal'" :maxlen="''"
                                    :idfor="'irsys'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'ficha.stripe'" :titulo="'Permite pagar reserva con Stripe'" :maxlen="''"
                                    :idfor="'irsys'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'ficha.efectivo'" :titulo="'Permite pagar reserva con efectivo (manual, quedará pendiente de confirmación por nuestra parte)'" :maxlen="''"
                                    :idfor="'iefe'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'ficha.transferencia'" :titulo="'Permite pagar reserva con transferencia (manual, quedará pendiente de confirmación por nuestra parte)'" :maxlen="''"
                                    :idfor="'itran'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                                <x-inputboolean :model="'ficha.bizum'" :titulo="'Permite pagar reserva con Bizum (manual, quedará pendiente de confirmación por nuestra parte)'" :maxlen="''"
                                    :idfor="'ibiz'" :col="12" :colmd="12" :disabled="''"
                                    :change="''" />
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-dark mt-3 text-center">Actualizar</button>
                                    <button wire:click="updateout" class="btn btn-dark mt-3 text-center">Actualizar y
                                        salir</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row mt-4 {{ $seccion == 4 ? 'visible' : 'oculto' }}">
                        <!--servicios-->

                        <h6 class="mb-0">Servicios elegibles en este calendario</h6>
                        <p class="mb-0">(cada servicio puede tener distintos packs con tiempo y precio distintos)</p>
                        <div class="form-group col-12 col-md-12 mt-4">
                            <div class="dropdown">
                                <a class="btn btn-secondary mb-4 me-4 float-start dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">Añadir servicios</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @foreach ($serviciosdisponibles as $key => $p)
                                        <li><a class="dropdown-item"
                                                wire:click="addservicio({{ $key }})">{{ $p['nombre'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            @if ($serviciosincluidos == 0)
                                <p>Aún no ha añadido ningún servicio</p>
                            @endif
                        </div>
                        @foreach ($servicios as $key => $tag)
                            @include('livewire.calendarios.calendario_servicio', ['origen' => ''])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.calendarios.modalpedircita')
    @include('livewire.calendarios.modalalta')
</div>
@push('js')
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/flatpickr.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/choices.min.js"></script>
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
        var calendar=null;
        const { createCalendar,viewWeek,viewMonthGrid } = window.SXCalendar;
        const { createDragAndDropPlugin } = window.SXDragAndDrop;
        const { createEventsServicePlugin } = window.SXEventsService;
        var config=null;
        var events=null;
        var plugins=null;
        document.addEventListener('livewire:initialized', function() {

        config = {
            views: [viewMonthGrid],
            //views: [viewMonthGrid,viewWeek],
            defaultView: viewMonthGrid,
            //defaultView: viewWeek,
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
                moverevento(updatedEvent);
                //console.log('onEventUpdate', updatedEvent)
                },
                onEventClick(calendarEvent) {
                    editarevento(calendarEvent);
                },
                onDoubleClickEvent(calendarEvent) {
                console.log('onDoubleClickEvent', calendarEvent)
                },
                onClickDate(date) {
                seleccionarfecha(date);
                //console.log('onClickDate', date) // e.g. 2024-01-01
                },
                onClickDateTime(dateTime) {
                seleccionarfecha(dateTime.substring(0,10));
                //console.log('onClickDateTime', dateTime) // e.g. 2024-01-01 12:37
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

        events= [
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
        plugins = [
          createDragAndDropPlugin(),
          createEventsServicePlugin(),
        ];
     
        calendar = createCalendar(config, plugins);
        calendar.render(document.querySelector('.calendar'));
        var data = @this.eventosjson;
        calendar.eventsService.set(JSON.parse(data));
        //calendar.eventsService.add({
        //    title: 'Event 1',
        //    start: '2024-11-28',
        //    end: '2024-11-28',
        //    id: 1
        //});
        //calendar.eventsService.set(events);
        });
        window.addEventListener('redrawallevents', event => {
            calendar.eventsService.set(JSON.parse(event.detail[0].eventos));
        });
        window.addEventListener('closemodal', event => {
            $('#evento').modal('hide');
            //Livewire.emit('cargardatos',event.detail.id,true,false,orden);
            //$('#modalficha').modal('show');
        });
        window.addEventListener('closemodalmulti', event => {
            $('#eventomultiple').modal('hide');
            //Livewire.emit('cargardatos',event.detail.id,true,false,orden);
            //$('#modalficha').modal('show');
        });
        window.addEventListener('showmodal', event => {
            $('#eventomultiple').modal('show');
        });
        function seleccionarfecha(objeto) {
            //console.log(objeto);
            //alert("selefecha "+objeto.dateStr);
            @this.newevent(objeto);
            //$('#ev entomultiple').modal('show');
        }
        function editarevento(objeto) {
            //alert(id);
            //alert("editamos "+id);
            @this.openevent(objeto);
            //$('#ev entomultiple').modal('show');
        }
        function moverevento(objeto) {
            //console.log(objeto);
            //alert("mover "+objeto.event.id+" a "+objeto.event.start);
            @this.moveevent(objeto);
        }
        window.addEventListener('removeevent', event => {
            calendar.eventsService.remove(event.detail[0].id);
            $('#eventomultiple').modal('hide');
        });
        window.addEventListener('xaxaxamodevent', event => {
            calendarold.getEventById(event.detail[0].ob.id).remove();
            calendarold.addEvent({
                title: event.detail[0].ob.title,
                start: event.detail[0].ob.inicio,
                end: event.detail[0].ob.fin,
                minutos: event.detail[0].ob.minutos,
                id: event.detail[0].ob.id,
                className: event.detail[0].ob.className,
            });
        });
    </script>
    <script>
        var clipboard = new ClipboardJS('.copiapega');
        clipboard.on('success', function (e) {
            //console.info('Action:', e.action);
            //console.info('Text:', e.text);
            //console.info('Trigger:', e.trigger);
            Swal.fire({
                position: "center",
                icon: 'success',
                iconColor:'#ff6f0e',
                title:"copiado al portapapeles",
                showConfirmButton: false,
                showCloseButton:true,
                timer: 4000
                });
        });
    </script>
@endpush
