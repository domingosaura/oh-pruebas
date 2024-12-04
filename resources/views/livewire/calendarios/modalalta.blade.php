<div class="modal fade" id="eventomultiple" tabindex="9999" style="z-index:9999" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                @if ($multi['nuevo'])
                    <h5 class="modal-title">crear sesión / varias sesiones</h5>
                @endif
                @if (!$multi['nuevo'])
                    <h5 class="modal-title">editar sesión</h5>
                    <p class="text-end">localizador: <strong>{{$this->multi['localizador']}}</strong></p>
                @endif
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <div class="row">
                        <x-input :model="'multi.title'" :tipo="'text'" :titulo="'Título informativo (no se mostrará al cliente)'" :maxlen="50"
                            :idfor="'ititle2'" :col="12" :colmd="12" :disabled="''" :change="''" />
                        <x-separador5 />
                        <div class="row {{ $multi['nuevo'] ? 'oculto2' : 'visible' }}">
                            <div id="select-cli2" placeholder="Cliente" value="{{ $idclisesion }}" wire:ignore>
                            </div>
                        </div>

                        @if (!$multi['nuevo'])
                        <h6 class="mt-4">Fecha de la sesión</h6>
                        <x-inputdate :model="'multi.datestart'" :titulo="'Fecha'" :maxlen="''" :idfor="'dtsar'"
                            :col="4" :colmd="3" :disabled="''" :change="''" />
                            <div class="form-group col-3 col-md-2">
                                    <label for="dtsitss">Hora</label>
                                    <input wire:model.blur="horario.0" type="time"
                                    class="form-control border border-2 p-2"
                                    id="dtsitss" placeholder="" step="300">
                            </div>
                            <h6 class="mt-4"></h6>
                        @endif
    
                        @if (!$multi['nuevo']||$multi['nuevo'])
                        <x-separador5 />
                        <h6 class="">Servicios seleccionables</h6>
                        <x-multi-select model="servicios_seleccionados" class="select-custom mb-5"
                            placeholderValue="Seleccione servicios/packs disponibles para elegir por el cliente" :options="[]" />
                        @endif

                        @if ($multi['nuevo'])
                            <h6 class="">Fechas donde se crearán las sesiones</h6>
                            <x-inputdate :model="'multi.datestart'" :titulo="'Fecha inicio'" :maxlen="''" :idfor="'dtsar'"
                                :col="6" :colmd="4" :disabled="''" :change="''" />
                            <x-inputdate :model="'multi.dateend'" :titulo="'Fecha final'" :maxlen="''" :idfor="'dend'"
                                :col="6" :colmd="4" :disabled="''" :change="''" />
                            <x-separador />
                            <h6 class="mt-2">Días de la semana donde se crearán las sesiones</h6>

                            <div>
                                <button type="button" wire:click="setdia('l')"
                                    class="btn {{ $multi['l'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">lunes</button>
                                <button type="button" wire:click="setdia('m')"
                                    class="btn {{ $multi['m'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">martes</button>
                                <button type="button" wire:click="setdia('x')"
                                    class="btn {{ $multi['x'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">miércoles</button>
                                <button type="button" wire:click="setdia('j')"
                                    class="btn {{ $multi['j'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">jueves</button>
                                <button type="button" wire:click="setdia('v')"
                                    class="btn {{ $multi['v'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">viernes</button>
                                <button type="button" wire:click="setdia('s')"
                                    class="btn {{ $multi['s'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">sábado</button>
                                <button type="button" wire:click="setdia('d')"
                                    class="btn {{ $multi['d'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">domingo</button>
                            </div>



                            <hr class="horizontal dark my-1">



                            <div class="col">
                                <div class="row bordered">

                            <div>
                                <button type="button" wire:click="fijaintervalo(2)"
                                class="btn {{ $multi['horasintervalos'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">
                                    <i class="material-icons">add</i>&nbsp;crear citas por intervalos</button>
                                <button type="button" wire:click="fijaintervalo(1)"
                                class="btn {{ $multi['horasfijas'] ? 'btn-primary blanco' : 'btn-outline-primary naranjito' }}naranjito">
                                    <i class="material-icons">add</i>&nbsp;crear citas puntuales</button>

                            </div>



                            @if ($multi['horasfijas'])
                            @endif
                            @if ($multi['horasfijas'])
                                <h6 class="mt-2">Horario de las sesiones
                                    <p>Configure manualmente una ó varias horas en los que se crearán las sesiones</p>
                                </h6>
                                <div class="row">
                                    @foreach ($horario as $key => $hor)
                                        <div class="col-4 col-md-2">
                                            <div class="input-group">
                                                <input wire:model.blur="horario.{{ $key }}" type="time"
                                                class="form-control border border-2 p-2"
                                                id="dtsit{{ $key }}" placeholder="" step="300">
                                                @if (count($horario) > 1)
                                                    <span class="naranjito puntero text-center"
                                                        wire:click="removehora({{ $key }})"
                                                        title="eliminar horas"><i class="material-icons">close</i></span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-4 col-md-2">
                                    <div class="input-group">
                                        <button class="btn btn-icon btn-primary" type="button"
                                            title="añadir horas" wire:click="addhora">
                                            <span class="btn-inner--icon"><i class="material-icons">add</i></span>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                            @endif
                            @if ($multi['horasfijas'])
                            @endif

                            @if ($multi['horasintervalos'])
                            @endif
                            @if ($multi['horasintervalos'])
                                <h6 class="mt-2">Crear sesiones a intervalos de tiempo
                                    <p>entre las horas configuradas creará sesiones según los minutos entre sesiones. Puede configurar hasta dos tramos horarios</p>
                                </h6>
                                <div class="col-4 col-md-2">
                                    <div class="form-group">
                                        <label for="d1">desde</label>
                                        <input wire:model.blur="multi.int1desde" type="time"
                                        class="form-control border border-2 p-2"
                                        id="d1" placeholder="" step="300">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="form-group">
                                        <label for="h1">hasta</label>
                                        <input wire:model.blur="multi.int1hasta" type="time"
                                        class="form-control border border-2 p-2"
                                        id="h1" placeholder="" step="300">
                                    </div>
                                </div>
                                <x-input :model="'multi.int1minutos'" :tipo="'number'" :titulo="'Minutos entre sesiones'" :maxlen="''"
                                    :idfor="'interval2'" :col="3" :colmd="2" :disabled="''"
                                    :change="''" />
                                    <x-separador/>
                                <div class="col-4 col-md-2">
                                    <div class="form-group">
                                        <label for="d2">desde</label>
                                        <input wire:model.blur="multi.int2desde" type="time"
                                        class="form-control border border-2 p-2"
                                        id="d2" placeholder="" step="300">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="form-group">
                                        <label for="h2">hasta</label>
                                        <input wire:model.blur="multi.int2hasta" type="time"
                                        class="form-control border border-2 p-2"
                                        id="h2" placeholder="" step="300">
                                    </div>
                                </div>
                                <x-input :model="'multi.int2minutos'" :tipo="'number'" :titulo="'Minutos entre sesiones'" :maxlen="''"
                                    :idfor="'interval2b'" :col="3" :colmd="2" :disabled="''"
                                    :change="''" />
                            @endif
                            @if ($multi['horasintervalos'])
                            @endif
                        </div>
                        </div>

                            <h6 class="mt-2">Sesiones a crear por cada fecha/hora (ej.: 2, creará 2 sesiones que se podrán reservar en la misma fecha/hora)</h6>
                            <x-input :model="'multi.numerosesiones'" :tipo="'number'" :titulo="'Sesiones simultáneas'" :maxlen="''"
                                :idfor="'inume2'" :col="4" :colmd="3" :disabled="''"
                                :change="''" />

                            <x-separador5 />
                            <x-separador />
                        @endif



                        @if ($multi['pagado'])
                            <div class="col-6">
                                <h6>Duración de la sesión contratada: {{ $multi['minutos'] }} minutos</h6>
                            </div>
                        @endif
                        <x-separador />
                        @if ($multi['nuevo'])
                        @endif
                        <x-separador />
                    </div>
                </div>
                <div class="row">
                    @if (!$multi['nuevo'])
                        <x-inputboolean :model="'multi.reservado'" :titulo="'Sesión reservada'" :maxlen="''" :idfor="'isre'"
                            :col="4" :colmd="4" :disabled="''" :change="''" />
                        <x-inputboolean :model="'multi.confirmado'" :titulo="'Sesión confirmada'" :maxlen="''" :idfor="'isco'"
                            :col="4" :colmd="4" :disabled="''" :change="''" />
                        <x-inputboolean :model="'multi.pagado'" :titulo="'Reserva pagada' . $multi['txpago'] . ' ' . $multi['importepagado'] . '€'" :maxlen="''" :idfor="'ispa'"
                            :col="4" :colmd="4" :disabled="''" :change="''" />
                        <x-inputboolean :model="'multi.nodisponible'" :titulo="'Reserva desactivada por el fotógrafo'" :maxlen="''" :idfor="'ispand'"
                            :col="4" :colmd="4" :disabled="''" :change="''" />

                        @if ($multi['reservado'])
                            @if ($multi['pregunta1'])
                                <h6>Pregunta: {{ $multi['pregunta1'] }}</h6>
                                <h6>Respuesta: {{ $multi['respuesta1'] }}</h6>
                            @endif
                            @if ($multi['pregunta2'])
                                <h6>Pregunta: {{ $multi['pregunta2'] }}</h6>
                                <h6>Respuesta: {{ $multi['respuesta2'] }}</h6>
                            @endif
                            @if ($multi['pregunta3'])
                                <h6>Pregunta: {{ $multi['pregunta3'] }}</h6>
                                <h6>Respuesta: {{ $multi['respuesta3'] }}</h6>
                            @endif
                            @if ($multi['pregunta4'])
                                <h6>Pregunta: {{ $multi['pregunta4'] }}</h6>
                                <h6>Respuesta: {{ $multi['respuesta4'] }}</h6>
                            @endif
                            @if ($multi['pregunta5'])
                                <h6>Pregunta: {{ $multi['pregunta5'] }}</h6>
                                <h6>Respuesta: {{ $multi['respuesta5'] }}</h6>
                            @endif
                        @endif

                    @endif
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                @if ($multi['nuevo'])
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"
                        wire:click="cancelarcalcularsesiones()">Cancelar</button>
                    <button wire:click="calcularsesiones" type="button" class="btn btn-primary">Crear</button>
                @endif
                @if (!$multi['nuevo'])

                <div>
                    <button wire:click="eliminarevento()" type="button" class="btn btn-outline-dark"
                    onclick="confirm('Se eliminarán la cita y todos sus datos, ¿Continuar?.') || event.stopImmediatePropagation()">Eliminar cita</button>
                    @if ($multi['reservado'])
                    <button wire:click="liberarevento()" type="button" class="btn btn-outline-dark"
                    onclick="confirm('Se eliminarán los datos de la cita y volverá a quedar disponible, ¿Continuar?.') || event.stopImmediatePropagation()">Liberar cita</button>
                    @endif
                    @if (!$multi['nodisponible'])
                    <button wire:click="nodispoevento()" type="button" class="btn botonnd_gris"
                    onclick="confirm('Se marcará esta cita como no disponible, ¿Continuar?.') || event.stopImmediatePropagation()">Marcar no disponible</button>
                    @endif
                    @if ($multi['nodisponible'])
                    <button wire:click="sidispoevento()" type="button" class="btn botonnd_gris"
                    onclick="confirm('Se marcará esta cita como disponible, ¿Continuar?.') || event.stopImmediatePropagation()">Marcar como disponible</button>
                    @endif
                </div>
                
                
                <div>
                    <button type="button" class="btn btn-outline-dark text-start" data-bs-dismiss="modal"
                        wire:click="cancelarcalcularsesiones()">Cancelar</button>
                    <button wire:click="actualizarevento()" type="button" class="btn btn-primary">Actualizar</button>
                </div>



                @endif
            </div>
        </div>
    </div>
</div>


<script>
    var vselect2;
    var selectedUsers2;
    document.addEventListener('livewire:initialized', function() {
        var datac2 = @this.clientes;
        vselect2 = VirtualSelect.init({
            ele: '#select-cli2',
            search: true,
            searchNormalize:true,
            options: JSON.parse(datac2),
        });
        selectedUsers2 = document.querySelector('#select-cli2');
        selectedUsers2.addEventListener('change', () => {
            seleo = selectedUsers2.value;
            seleo = (seleo == '' ? 0 : seleo);
            @this.setidclientemulti(seleo);
        });
    });
    window.addEventListener('vselectsetvalue2', event => {
        //alert(event.detail[0].idcli);
        document.querySelector('#select-cli2').setValue(event.detail[0].idcli);
    });
</script>
