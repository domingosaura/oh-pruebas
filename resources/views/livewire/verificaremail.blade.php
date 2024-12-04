<div class="container-fluid py-4 bg-gray-200">
    <div class="row mt-0">
        <div class="col-12">
            <div class="row">

                <div class="col-12 text-center">
                    <img src="/oh/oh_icon_min_tx.png" class="img-fluid navbar-brand-img h-100" alt="main_logo">
                </div>

                <div class="col-12 mt-md-5 mt-5 text-center">
                    <div class="card">
                        <div class="row mt-5">
                            <div class="col-3">
                            </div>
                                <x-input :tipo="'text'" :col="6" :colmd="6" :idfor="'contra'" :model="'codigo'"
                                    :titulo="'Código recibido por e-mail'" :disabled="''" :maxlen="'20'" :change="''" />
                            </div>
                        <div class="card-body py-3">

                            <button wire:click="updatesecurity" class="btn btn-dark mt-3 text-center">Verificar</button>
                            <button wire:click="resendmail" class="btn btn-dark mt-3 text-center">Reenviar código</button>


                        </div>
                    </div>
                </div>







            </div>
        </div>

    </div>
</div>
<!--   Core JS Files   -->
@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>

<script>
</script>
@endpush