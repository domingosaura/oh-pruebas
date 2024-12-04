        <div class="container-fluid py-4">
            <div class="row xxmt-4">

                

                <div class="col-12">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h5 class="mb-0">Crear calendario</h5>
                            <!--<p>Nuevo cliente</p>-->
                        </div>
                        <div class="col-12 text-end">
                            <a class="btn bg-gradient-dark mb-0 me-4" href="{{ route('calendarios-management') }}">Volver</a>
                        </div>

                        <div class="card-body">


                            <div class="col-12 col-md-4">
                                <img id="{{$idfot}}" src="
                                @if(strlen($base64)==0)
                                /oh/img/gallery-generic.jpg
                                @else
                                data:image/jpeg;base64,{{$base64}}
                                @endif
                                " class="img-fluid shadow border-radius-xl">
                            </div>

                            <div class="col-12 col-md-4">
                                <x-filepond wire:model="files" maxsize="27MB" resize="true" width="425" height="283"  w5sec="true"/>
                            </div>

                            <form wire:submit="store" class='xxd-flex xxflex-column align-items-center'>
                            <div class="row">

                                <x-input :tipo="'name'" :col="12" :colmd="6" :idfor="'inombre'" :model="'nombre'" :titulo="'Nombre'" :disabled="''" :change="''" :maxlen="''"/>
                                <x-input :tipo="'name'" :col="12" :colmd="12" :idfor="'idescri'" :model="'descripcion'" :titulo="'Descripción'" :disabled="''" :change="''" :maxlen="'200'"/>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-dark mt-3 text-center">Crear calendario</button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @push('css')
    @endpush
    @push('js')
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    @endpush
