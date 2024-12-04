<div class="container mt-0">
    <div class="row signin-margin">
        <div class="col-lg-5 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 xxfadeIn3 xxfadeInBottom">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="xxbg-gradient-secondary shadow-secondary border-radius-lg py-3 pe-1"
                        style="background-color:white">
                        <div class="text-center">

                            <img src="/oh/oh_icon_min_tx.png" class="img-fluid navbar-brand-img h-100" alt="main_logo">
                        </div>
                        <h4 class="oh_azul text-center mt-2 mb-0">Registro de usuario</h4>
                        <!--
                        <div class="row mt-3">
                            <div class="col-2 text-center ms-auto">
                                <a class="btn btn-link px-3" href="javascript:;">
                                    <i class="fa fa-facebook text-white text-lg"></i>
                                </a>
                            </div>
                            <div class="col-2 text-center px-1">
                                <a class="btn btn-link px-3" href="javascript:;">
                                    <i class="fa fa-github text-white text-lg"></i>
                                </a>
                            </div>
                            <div class="col-2 text-center me-auto">
                                <a class="btn btn-link px-3" href="javascript:;">
                                    <i class="fa fa-google text-white text-lg"></i>
                                </a>
                            </div>
                        </div>
                    -->
                    </div>
                </div>
                <div class="card-body">
                    <form wire:submit='store' role="form">
                        <div
                            class="input-group input-group-outline my-3 @if (strlen($name ?? '') > 0) is-filled @endif">
                            <label class="form-label">Nombre</label>
                            <input wire:model.live="name" type="text" class="form-control" aria-label="name">
                        </div>
                        @error('name')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror

                        <div
                            class="input-group input-group-outline my-3 @if (strlen($email ?? '') > 0) is-filled @endif">
                            <label class="form-label">Email</label>
                            <input wire:model.live="email" type="email" class="form-control" aria-label="Email">
                        </div>
                        @error('email')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror

                        <div
                            class="input-group input-group-outline my-3 @if (strlen($password ?? '') > 0) is-filled @endif">
                            <label class="form-label">Password</label>
                            <input wire:model.live="password" type="password" class="form-control"
                                aria-label="Password">
                        </div>
                        @error('password')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        <div class="form-check text-start mt-3">
                            <input class="form-check-input check-naranja" wire:change="showaccept" wire:model="acepto" type="checkbox" value=""
                                id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Acepto los <a href="javascript:;" wire:click="showaccept2"
                                    class="text-dark font-weight-bolder puntero">términos y
                                    condiciones</a>
                            </label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Registrarse</button>
                        </div>
                        <p class="text-sm mt-3 mb-0">¿Ya tiene una cuenta?
                            <a href="{{ route('login') }}" class="text-dark font-weight-bolder">Iniciar sesión
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="condiciones" tabindex="9999" style="z-index:9999" data-bs-backdrop="static" wireignoreself>
        <div class="modal-dialog aamodal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="tab-content">
                        @include('livewire.condiciones')
                    </div>
                </div>
                <div class="modal-footer text-start">
                    <button type="button" wire:click="aceptar" class="btn btn-primary anaranjito text-start" data-bs-dismiss="modal">He leído las condiciones</button>
                </div>
            </div>
        </div>
    </div>





</div>

@push('js')
    <script src="{{ asset('assets') }}/js/plugins/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {

            var input = $(".input-group input");
            input.focusin(function() {
                $(this).parent().addClass("focused is-focused");
            });
            input.focusout(function() {
                $(this).parent().removeClass("focused is-focused");
            });
        });

        window.addEventListener('closemodalreg', event => {
            $('#condiciones').modal('hide');
        });
        window.addEventListener('showmodalreg', event => {
            $("#condiciones").appendTo("body");
            //alert("showmodalreg");
            $('#condiciones').modal('show');
        });
</script>
@endpush
