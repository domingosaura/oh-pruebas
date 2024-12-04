<div class="container-fluid py-4">
    <div class="row xxmt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Crear impuesto</h5>
                    <!--<p>Nuevo cliente</p>-->
                </div>
                <div class="col-12 text-end">
                    <a class="btn bg-gradient-dark mb-0 me-4" href="{{ route('impuesto-management') }}">Volver</a>
                </div>

                <div class="card-body">
                    <form wire:submit="store" class='xxd-flex xxflex-column align-items-center'>
                    <div class="row">

                        <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'" :model="'nombre'" :titulo="'Título'" :disabled="''" :maxlen="''" :change="''"/>
                        <x-input :tipo="'number'" :col="3" :colmd="3" :idfor="'iporce'" :model="'porcentaje'" :titulo="'Porcentaje'" :disabled="''" :maxlen="''" :change="''"/>


                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-dark mt-3 text-center">Crear impuesto</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>

@endpush
