<div class="modal fade" id="pedircita" tabindex="9999" style="z-index:9999" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enlace a cliente para reservar sesión</h5>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <x-input :model="'emailsesion'" :tipo="'mail'" :titulo="'E-mail'" :maxlen="200" :idfor="'iemses'"
                        :col="12" :colmd="12" :disabled="''" :change="''" />
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
<script>
    window.addEventListener('closemodalcita', event => {
        $('#pedircita').modal('hide');
    });
</script>
