<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-white"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 p-4 d-flex align-items-center text-wrap" href="{{ route('dashboard') }}">
            <img src="/oh/oh_icon_min_tx.png" class="img-fluid navbar-brand-img h-100" alt="OhMyPhoto" style="width:95%;height:auto !important;max-height: none;">
            <!--<span class="ms-2 font-weight-bold text-dark">OhMyPhoto</span>-->
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            @if(env('TESTMODE'))
            <li class="nav-item text-center">
                <span class="badge badge-danger ms-auto mb-auto naranjito">ENTORNO DE TEST</span>
            </li>
            @endif

            <!-- personal -->

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#galerias"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'contratos')=== false&&strpos(Request::route()->uri(), 'misgalerias')=== false ? '' : 'active' }} "
                    aria-controls="galerias" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">web_stories</i>
                    <span class="nav-link-text ms-2 ps-1">Sesiones</span>
                </a>
                <div class="collapse {{ strpos(Request::route()->uri(), 'contratos')=== false&&strpos(Request::route()->uri(), 'misgalerias')=== false ? '' : 'show' }} "
                    id="galerias">
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'galeria-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'galeria-management' ? 'active' : '' }} "
                                href="{{ route('galeria-management') }}">
                                <span class="sidenav-mini-icon"> G </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Galerías </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'clientecontrato-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'clientecontrato-management' ? 'active' : '' }} "
                                href="{{ route('clientecontrato-management') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Contratos de cliente </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            @if(env('TESTMODE'))
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#calendario"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'calendario')=== false&&strpos(Request::route()->uri(), 'servicios')=== false&&strpos(Request::route()->uri(), 'packs')=== false ? '' : 'active' }} "
                    aria-controls="calendario" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">calendar_month</i>
                    <span class="nav-link-text ms-2 ps-1">Calendarios</span>
                </a>
                <div class="collapse {{ strpos(Request::route()->uri(), 'calendario')=== false&&strpos(Request::route()->uri(), 'servicios')=== false&&strpos(Request::route()->uri(), 'packs')=== false ? '' : 'show' }} "
                    id="calendario">
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'calendarios-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'calendarios-management' ? 'active' : '' }} "
                                href="{{ route('calendarios-management') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Calendarios </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'sesiones-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'sesiones-management' ? 'active' : '' }} "
                                href="{{ route('sesiones-management') }}">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Servicios </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'packs-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'packs-management' ? 'active' : '' }} "
                                href="{{ route('packs-management') }}">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Packs </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if(!env('TESTMODE'))
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#calendario"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'calendario')=== false&&strpos(Request::route()->uri(), 'servicios')=== false&&strpos(Request::route()->uri(), 'packs')=== false ? '' : 'active' }} "
                    aria-controls="calendario" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">calendar_month</i>
                    <span class="nav-link-text ms-2 ps-1">Calendarios</span>
                    <span class="badge badge-danger ms-auto mb-auto naranjito">¡pronto!</span>
                </a>

            </li>
            @endif


            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#plan"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'plantillas')=== false ? '' : 'active' }} "
                    aria-controls="plan" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">layers</i>
                    <span class="nav-link-text ms-2 ps-1">Plantillas</span>
                </a>
                <div class="collapse {{ strpos(Request::route()->uri(), 'plantillas')=== false ? '' : 'show' }} "
                    id="plan">
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'plantillagaleria-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'plantillagaleria-management' ? 'active' : '' }} "
                                href="{{ route('plantillagaleria-management') }}">
                                <span class="sidenav-mini-icon"> G </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Galerías </span>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'plantillacontrato-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'plantillacontrato-management' ? 'active' : '' }} "
                                href="{{ route('plantillacontrato-management') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Contratos </span>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'productos-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'productos-management' ? 'active' : '' }} "
                                href="{{ route('productos-management') }}">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Productos para galerías </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#clipro"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'cliente')=== false&&strpos(Request::route()->uri(), 'proveedor')=== false ? '' : 'active' }} "
                    aria-controls="clipro" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">group</i>
                    <span class="nav-link-text ms-2 ps-1">Clientes/Proveedores</span>
                </a>
                <div class="collapse {{ strpos(Request::route()->uri(), 'cliente')=== false&&strpos(Request::route()->uri(), 'proveedor')=== false ? '' : 'show' }} "
                    id="clipro">
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'cliente-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'cliente-management' ? 'active' : '' }} "
                                href="{{ route('cliente-management') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Clientes </span>
                            </a>
                        </li>
                        <!--
                        <li class="nav-item { { Route::currentRouteName() == 'clientecontrato-management' ? 'active' : '' } }  ">
                            <a class="nav-link text-dark { { Route::currentRouteName() == 'clientecontrato-management' ? 'active' : '' } } "
                                href="{ { route('clientecontrato-management') } }">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Contratos de cliente </span>
                            </a>
                        </li>
                    -->
                        <li class="nav-item {{ Route::currentRouteName() == 'proveedor-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'proveedor-management' ? 'active' : '' }} "
                                href="{{ route('proveedor-management') }}">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Proveedores </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#monetario"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'monetario')=== false ? '' : 'active' }} "
                    aria-controls="monetario" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">euro</i>
                    <span class="nav-link-text ms-2 ps-1">Ingresos y Gastos</span>
                </a>
                <div class="collapse {{ strpos(Request::route()->uri(), 'monetario')=== false ? '' : 'show' }} "
                    id="monetario">
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'ingreso-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'ingreso-management' ? 'active' : '' }} "
                                href="{{ route('ingreso-management') }}">
                                <span class="sidenav-mini-icon"> I </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Registro de ingresos </span>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'gasto-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'gasto-management' ? 'active' : '' }} "
                                href="{{ route('gasto-management') }}">
                                <span class="sidenav-mini-icon"> G </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Registro de gastos </span>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'impuesto-management' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'impuesto-management' ? 'active' : '' }} "
                                href="{{ route('impuesto-management') }}">
                                <span class="sidenav-mini-icon"> I </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Impuestos </span>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'listados' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'listados' ? 'active' : '' }} "
                                href="{{ route('listados') }}">
                                <span class="sidenav-mini-icon"> L </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Listados </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Paneles -------------------------------------------------------------------------- -->
            <!-- Paneles -------------------------------------------------------------------------- -->
            <!-- Paneles -------------------------------------------------------------------------- -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#paneles"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'panel1')=== false ? '' : 'active' }} "
                    aria-controls="paneles" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">layers</i>
                    <span class="nav-link-text ms-2 ps-1">Paneles</span>
                </a>
                <div class="collapse {{ strpos(Request::route()->uri(), 'panel1')=== false ? '' : 'show' }} "
                    id="paneles">
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'panel1' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'panel1' ? 'active' : '' }} "
                                href="{{ route('panel1') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Panel 1 </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- FIN Paneles -------------------------------------------------------------------------- -->








            <hr class="horizontal light mt-0">

            <li class="nav-item mb-2 mt-0">
                <a data-bs-toggle="collapse" href="#confi"
                    class="nav-link text-dark {{ strpos(Request::route()->uri(), 'micuenta')=== false&&strpos(Request::route()->uri(), 'importaciones')=== false ? '' : 'active' }}" aria-controls="confi"
                    role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">manage_accounts</i>
                    <!--<img src="{ { asset('assets') } }/img/default-avatar.png" alt="avatar" class="avatar">-->
                    <span class="nav-link-text ms-2 ps-1">Cuenta</span>
                </a>
                <div class="collapse {{ strpos(Request::route()->uri(), 'micuenta')=== false&&strpos(Request::route()->uri(), 'importaciones')=== false ? '' : 'show' }} " id="confi">
                    <ul class="nav ">
                        <li class="nav-item {{ Route::currentRouteName() == 'micuenta' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'micuenta' ? 'active' : '' }} "
                                href="{{ route('micuenta') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Mi cuenta </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'importaciones' ? 'active' : '' }}  ">
                            <a class="nav-link text-dark {{ Route::currentRouteName() == 'importaciones' ? 'active' : '' }} "
                                href="{{ route('importaciones') }}">
                                <span class="sidenav-mini-icon"> I </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Importaciones </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="{{ route('logout') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-3  ps-1"> Cerrar sesión </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-2 mt-4">
                <a href="https://www.ohmyphoto.es/tutoriales/" target="_blank"
                    class="nav-link text-dark" aria-controls="confi"
                    role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">subscriptions</i>
                    <span class="nav-link-text ms-2 ps-1">Tutoriales</span>
                </a>
            </li>


            <hr class="horizontal light mt-10">




            <!-- fin personal -->





        </ul>
    </div>
</aside>