@auth
    @if(!Auth::user()->hasVerifiedEmail())
    <div>
        <div class="alert alert-primary alert-dismissible text-white" role="alert">
            <span class="text-sm"><a href="{{route("verificaremail")}}"
                    class="alert-link text-white">pulse aquí para verificar su email y dejar de ver este mensaje</a></span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif
@endauth
