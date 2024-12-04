<div class="modal fade" id="pedircita" tabindex="9999" style="z-index:9999" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enlace a cliente para reservar sesión</h5>
            </div>
            <div class="modal-body">
                <div class="tab-content">


                    <div class="row">
                        <div class="col-12 text-center">
                            <p id="ilr">{{$rutaaccesocliente}}</p>
                        </div>
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-danger fondonaranjito copiapega" 
                                data-clipboard-action="copy" data-clipboard-target="#ilr">Copiar enlace</button>
                        </div>
                    </div>

                    <hr class="horizontal dark my-1 mt-2 mb-2">

                    <div class="row">
                        <div id="select-cli1" placeholder="cliente (opcional)" value="{{ $idclisesion }}" wire:ignore>
                        </div>
                        <x-separador />
                        <x-input :model="'emailsesion'" :tipo="'mail'" :titulo="'E-mail'" :maxlen="200"
                            :idfor="'iemses'" :col="12" :colmd="12" :disabled="''" :change="''" />
                        <x-input :model="'telefsesion'" :tipo="'text'" :titulo="'Teléfono'" :maxlen="20"
                            :idfor="'itlses'" :col="12" :colmd="12" :disabled="''"
                            :change="''" />
                    </div>
                    <h6 class="rojo text-center mt-4">{{ $notifsesion }}</h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-start" data-bs-dismiss="modal">Cerrar</button>
                <button wire:click="enviarenlace" type="button" class="btn btn-success fondonaranjito">Enviar
                    email</button>
                <button wire:click="enviarenlacewhatsapp" type="button" class="btn btn-success fondonaranjito">Enviar
                    Whatsapp</button>
            </div>
        </div>
    </div>
</div>
<script>
    var vselect;
    var selectedUsers;
    var vselect1;
    var selectedUsers1;
    document.addEventListener('livewire:initialized', function() {
        var datac = @this.clientes;
        vselect1 = VirtualSelect.init({
            ele: '#select-cli1',
            search: true,
            searchNormalize:true,
            options: JSON.parse(datac),
        });
        selectedUsers1 = document.querySelector('#select-cli1');
        selectedUsers1.addEventListener('change', () => {
            seleo = selectedUsers1.value;
            seleo = (seleo == '' ? 0 : seleo);
            @this.setidcliente(seleo);
            @this.fillemailsession(seleo);
        });
    });
    window.addEventListener('closemodalcita', event => {
        $('#pedircita').modal('hide');
    });
    window.addEventListener('vselectsetvalue', event => {
        //alert(event.detail[0].idcli);
        document.querySelector('#select-cli1').setValue(event.detail[0].idcli);
    });
</script>
