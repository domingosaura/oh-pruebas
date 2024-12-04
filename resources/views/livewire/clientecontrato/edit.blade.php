<div class="container-fluid py-4">
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Editar contrato</h5>
                    <!--<p>ATENTO, TODOS LOS MOVIMIENTOS SE GRABAN AUTOMÁTICAMENTE</p>-->
                    <div class="col-12 text-end">
                        <a class="btn bg-gradient-dark mb-0 me-4 float-end" wire:click="update(1)"
                            href="{{ route('clientecontrato-management') }}">volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($ficha['firmado'])
                        <div class="col-12 mt-4 navy">
                            <strong>Este contrato fué firmado por el cliente en {{date('d/m/Y
                                H:i:s',strtotime($ficha['dtfirma']))}} desde la ip
                                {{$ficha['ipfirma']}}</strong>&nbsp;

                            <button wire:click="seepdf" class="btn btn-dark mt-3 text-center">
                                <i class="material-icons">picture_as_pdf</i>
                                Ver pdf</button>

                        </div>
                        @endif
                        @if($ficha['enviado'])
                        <div class="col-12 mt-4 navy">
                            <strong>Este contrato fué enviado al cliente en {{date('d/m/Y
                                H:i:s',strtotime($ficha['dtenvio']))}}</strong>
                        </div>
                        @endif


                        <x-input :tipo="'name'" :col="9" :colmd="8" :idfor="'idoc'" :model="'ficha.nombre'"
                            :titulo="'Descripción contrato'" :disabled="$ficha->firmado?'disabled':'250'" :maxlen="''"
                            :change="''" />


                        <div style="z-index: 999;" class="form-group col-9 col-md-9 mb-4">
                            <div class="row">
                                <label>Cliente del contrato&nbsp;
                                    @if($ficha['firmado']==false)
                                <i class="material-icons negro puntero" title="nuevo cliente"
                                    data-bs-target="#createcliente" data-bs-toggle="modal">person_add</i>
                                    @endif
                                @if($ficha['cliente_id']>0)
                                &nbsp;
                                <a href="{{ route('edit-cliente', $ficha['cliente_id'])}}">
                                <i class="material-icons negro puntero" title="ficha cliente">border_color</i>
                                </a>
                                @endif
                            </label>
                                <div {{$ficha['firmado']?'disabled':''}} style="z-index: 999;" class="aform-control"
                                    id="select-cli" placeholder="cliente" value="{{$ficha['cliente_id']}}" wire:ignore>
                                </div>
                            </div>
                        </div>


                        @if($ficha['cliente_id']>=0 && $ficha['firmado']==false)
                        <div class="form-group col-6 col-md-7">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Click para aplicar una plantilla
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @foreach($plantillas as $key=>$p)
                                    <li><a class="dropdown-item"
                                            wire:click="selectplantilla({{$key}})">{{$p['nombre']}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        <div class="col-12 mt-4" id="eeeditor" style="">
                            <label>Texto del contrato</label>
                            <livewire:quill-text-editor wire:model.live="ficha.texto"
                                theme="{{$ficha['firmado']?'snowdisabled':'snow'}}" />
                        </div>


                        <div class="col-8 text-end">
                            &nbsp;
                        </div>
                        <div class="col-4 text-end">
                            <div class="dropdown">
                                <a class="dropdown-toggle italica" type="button" id="dropdownMenuButton2"
                                    data-bs-toggle="dropdown" aria-expanded="false">introducir una variable</a>
                                <ul class="dropdown-menu text-end" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(1)">check
                                            obligatorio</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(2)">check
                                            opcional</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(3)">nombre
                                            empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(4)">nombre
                                            propio empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(5)">nif
                                            empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(6)">domicilio empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(7)">código
                                            postal empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(8)">población empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(9)">provincia empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(10)">teléfono empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(11)">email
                                            empresa</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(12)">nombre/apellidos cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(13)">nif
                                            cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(14)">domicilio cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(15)">código postal cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(16)">población cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(17)">provincia cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(18)">teléfono cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(19)">email
                                            cliente</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(20)">nombre/apellidos pareja</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(21)">nif
                                        pareja</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(22)">Primer hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(23)">Segundo hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(24)">Tercer hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(25)">Cuarto hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica"
                                            wire:click="variable(26)">Quinto hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(27)">Sexto
                                            hijo</a></li>
                                    <li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(28)">Fecha
                                            contrato</a></li>
                                    <!--<li><a class="mb-0 me-4 float-start puntero italica" wire:click="variable(29)">Cuadro de firma</a></li>-->
                                </ul>
                            </div>


                        </div>
                        <div class="row">



                            <div class="col-12 col-md-12 text-center">
                                @if($ficha->cliente_id>0)
                                <a target="_blank" style="color:white" class="btn bg-dark mt-3 text-center"
                                    href="{{ route('contratocliente',[$idmov,$conmd5]) }}">Previsualizar como
                                    cliente</a>
                                    @else
                                    <a target="_blank" style="color:white" class="btn bg-dark mt-3 text-center"
                                        href="{{ route('contratocliente',[$idmov,$conmd5]) }}">Previsualizar (aunque no
                                        haya seleccionado cliente)</a>
                                @endif
                                <button wire:click="sendclient" class="btn btn-dark mt-3 text-center">Enviar por mail</button>
                                <button wire:click="sendclientwhatsapp" class="btn btn-dark mt-3 text-center">Enviar por Whatsapp</button>
                                <button 
                                    class="btn btn-dark mt-3 text-center copiapega" 
                                    data-clipboard-action="copy" data-clipboard-target="#ilr"
                                    title="copia el enlace del contrato para enviar manualmente">Copiar enlace</button>
                                </div>
                                <div class="col-12 col-md-12 text-center">
                                <button wire:click="update" class="btn btn-dark mt-3 text-center">Guardar
                                    contrato</button>
                                <button wire:click="updateout" class="btn btn-dark mt-3 text-center">Guardar contrato y
                                    salir</button>
                            </div>
                            <p class="aoculto2 text-center" id="ilr">{{$ruta}}</p>
                            @if($errormail)
                            <div class="col-12 text-center">
                                <p class="rojo">{{$errormail}}</p>
                            </div>
                            @endif



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createcliente" tabindex="9999" style="z-index:9999" data-bs-backdrop="static"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo cliente</h5>
                    <br />
                    <h6></h6>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="row">
                            <x-input :model="'newnombre'" :tipo="'text'" :titulo="'Nombre'" :maxlen="100"
                                :idfor="'newno'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            <x-input :model="'newapellidos'" :tipo="'text'" :titulo="'Apellidos'" :maxlen="100"
                                :idfor="'newap'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            <x-input :model="'newdni'" :tipo="'text'" :titulo="'N.I.F.'" :maxlen="15" :idfor="'newdn'"
                                :col="9" :colmd="7" :disabled="''" :change="''" />
                            <x-input :model="'newtelefono'" :tipo="'text'" :titulo="'Teléfono'" :maxlen="200"
                                :idfor="'newtel'" :col="9" :colmd="7" :disabled="''" :change="''" />
                            <x-input :model="'newemail'" :tipo="'text'" :titulo="'e-mail'" :maxlen="200"
                                :idfor="'newmail'" :col="9" :colmd="7" :disabled="''" :change="''" />

                            <x-separador />
                            <p class="rojo text-center mt-2">{{$newtext}}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="cancelarnuevocliente" type="button" class="btn bg-gradient-dark"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button wire:click="crearnuevocliente" type="button" class="btn btn-dark">Crear</button>
                </div>
            </div>
        </div>
    </div>


</div>





@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quilloh.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill-resize-module.js"></script>
<script src="{{ asset('assets') }}/js/plugins/quill.imageCompressor.min.js"></script>
<script>
    var vselect;
    var selectedUsers;

    document.addEventListener('livewire:initialized', function() {

    var datac = @this.clientes;
    vselect=VirtualSelect.init({
  ele: '#select-cli',
  search: true,
  searchNormalize:true,
  options: JSON.parse(datac),
});
selectedUsers = document.querySelector('#select-cli');
    selectedUsers.addEventListener('change', () => {
        seleo = selectedUsers.value;
        seleo=(seleo==''?0:seleo);
        @this.setidcliente(seleo);
});

document.querySelector('#select-cli').setValue({{$ficha['cliente_id']}});
//quill.disable();
});

window.addEventListener('asignarcliente', event => {
    vselect.setOptions(JSON.parse(@this.clientes));
    document.querySelector('#select-cli').setValue(event.detail[0].id);
});

window.addEventListener('closemodal', event => { 
    $('#createcliente').modal('hide');
});

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
clipboard.on('error', function (e) {
  console.log(e);
});

</script>
@endpush