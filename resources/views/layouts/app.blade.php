<x-layouts.base>

    @if (in_array(request()->route()->getName(),['rtl']))
        {{ $slot }}
    
    @elseif (in_array(request()->route()->getName(),['pricing-page','basic-lock', 'basic-reset', 'basic-sign-in', 'basic-sign-up','basic-verification','cover-lock', 'illustration-lock','cover-reset','illustration-reset','cover-sign-in','cover-sign-up','illustration-sign-up','cover-verification','illustration-verification','error404','error500','register','register2', 'login','cookies','galeriacliente','contratocliente','reservas','rellenarporcliente','forget-password','reset-password']))

        @if (in_array(request()->route()->getName(),['illustration-lock','illustration-reset','illustration-sign-up','illustration-verification']))
 
            <div class="container position-sticky z-index-sticky top-0">
                <div class="row">
                  <div class="col-12">
                <x-navbars.navs.guest class='blur border-radius-lg shadow mt-4 py-2 start-0 end-0 mx-4'>
                </x-navbars.navs.guest>
                  </div>
                </div>
            </div>

        @else

            <x-navbars.navs.guest class='w-100 shadow-none my-3 navbar-transparent mt-4'>
            </x-navbars.navs.guest>

        @endif

        @if ((in_array(request()->route()->getName(),['login'])))

        <main class="main-content mt-0">
            <div class="page-header page-header-bg-sign-oh align-items-start min-vh-100">
                <span class="xxmask xxbg-gradient-dark xxopacity-6"></span>
                {{ $slot }}
                <x-footers.guest.basic-footer textColor="text-white"></x-footers.guest.basic-footer>
            </div>
        </main>

            
        @elseif ((in_array(request()->route()->getName(),['register','register2','forget-password'])))

        <main class="main-content mt-0">
            <div class="page-header page-header-bg-sign-oh align-items-start min-vh-100">
                <span class="xxmask xxbg-gradient-dark xxopacity-6"></span>
                {{ $slot }}
                <x-footers.guest.basic-footer textColor="text-white"></x-footers.guest.basic-footer>
            </div>
        </main>

        @else
        {{ $slot }}

        @if (in_array(request()->route()->getName(),['basic-reset','cover-sign-in', 'cover-verification','forget-password','cover-reset','cookies','galeriacliente','contratocliente','reservas','rellenarporcliente']))
            <x-footers.guest.basic-footer textColor="text-muted"></x-footers.guest.basic-footer>
        @elseif (in_array(request()->route()->getName(),['basic-sign-in', 'basic-sign-up','reset-password']))
            <x-footers.guest.basic-footer textColor="text-white"></x-footers.guest.basic-footer>
        @elseif(in_array(request()->route()->getName(),['pricing-page','basic-lock', 'cover-lock','cover-sign-up','error404','error500']))
            <x-footers.guest.social-icons-footer></x-footers.guest.social-icons-footer>
        @else
           
        @endif
        @endif

    @elseif (in_array(request()->route()->getName(),['vr-info', 'vr-default']))
    @else
        <x-navbars.sidebar></x-navbars.sidebar>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            @include("livewire.verificaremailpush")
            <x-navbars.navs.auth></x-navbars.navs.auth>
            {{ $slot }}
            <x-footers.auth.footer></x-footers.auth.footer>
        </main>
        <x-plugins></x-plugins>
    @endif
</x-layouts.base>