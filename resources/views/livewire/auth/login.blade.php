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
                        <h4 class="text-center mt-2 mb-0 oh_azul">Inicio de sesión</h4>
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
                    <form wire:submit='store'>

                        @if (Session::has('status'))
                        <div class="alert alert-success-oh alert-dismissible text-white" role="alert">
                            <span class="text-sm">{{ Session::get('status') }}</span>
                            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <div
                            class="input-group input-group-outline my-3 @if(strlen($email ?? '') > 0) is-filled @endif">
                            <label class="form-label">Email</label>
                            <input wire:model.live='email' type="email" class="form-control" id="ema">
                        </div>
                        @error('email')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        <div
                            class="input-group input-group-outline mb-3 @if(strlen($password ?? '') > 0) is-filled @endif">
                            <label class="form-label">Password</label>
                            <input wire:model.live="password" type="password" class="form-control" id="pass">
                        </div>
                        @error('password')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror



                        <div
                            class="input-group input-group-outline mb-3 @if (strlen($aut2 ?? '') > 0) is-filled @endif">
                            <label class="form-label">Clave de autenticación (si está activo)</label>
                            <input wire:model='aut2' type="text" class="form-control text-center" maxlength="16"
                                id="new-password" autocomplete="new-password" name="new-password">
                        </div>
                        @error('aut2')
                            <p class='text-danger inputerror'>{!! $message !!} </p>
                        @enderror



                        <div class="text-center">
                            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Iniciar
                                sesión</button>
                        </div>
                        <p class="mt-4 text-sm text-center">
                            ¿No tienes una cuenta?
                            <a href="{{ route('register') }}"
                                class="text-dark font-weight-bolder">Regístrate</a>
                        </p>
                        <p class="text-sm text-center">
                            <a href="{{ route('forget-password') }}"
                                class="text-dark font-weight-bolder">He olvidado mi contraseña</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
<script src="{{ asset('assets') }}/js/plugins/jquery-3.6.0.min.js"></script>
<script>
    window.addEventListener('focus2fa', event => {
        $('#2fa').focus();
    });
    $(function () {
            var input = $(".input-group input");
            input.focusin(function () {
                $(this).parent().addClass("focused is-focused");
            });
            input.focusout(function () {
                $(this).parent().removeClass("focused is-focused");
            });
        });
</script>

@endpush