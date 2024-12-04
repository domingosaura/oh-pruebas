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
                        <h4 class="oh_azul text-center mt-2 mb-0">Recuperar mi contraseña</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if (Session::has('email'))
                    <div class="alert alert-danger alert-dismissible text-white" role="alert">
                        <span class="text-sm">{{ Session::get('email') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <form wire:submit="update" class="text-start">

                        <div class="input-group input-group-outline mt-3 @if(strlen($email ?? '') > 0) is-filled @endif">
                            <label class="form-label">Email</label>
                            <input wire:model.live="email" type="email" class="form-control">
                        </div>
                        @error('email')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror

                        <div class="input-group input-group-outline mt-3 @if(strlen($password ?? '') > 0) is-filled @endif">
                            <label class="form-label">Nueva contraseña</label>
                            <input wire:model.live="password" type="password" class="form-control">
                        </div>
                        @error('password')
                        <div class="text-danger inputerror">{{ $message }}</div>
                        @enderror

                        <div class="input-group input-group-outline mt-3 @if(strlen($passwordConfirmation ?? '') > 0) is-filled @endif">
                            <label class="form-label">Recuperar contraseña</label>
                            <input wire:model.live="passwordConfirmation" type="password" class="form-control" 
                                >
                                @error('passwordConfirmation')
                                <div class="text-danger inputerror">{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Cambiar contraseña</button>
                        </div>
                        <p class="mt-4 text-sm text-center">
                            ¿No tienes una cuenta?
                            <a href="{{ route('register') }}"
                                class="text-primary text-gradient font-weight-bold">Regístrate</a>
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
