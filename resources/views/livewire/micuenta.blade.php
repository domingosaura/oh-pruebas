<div class="container-fluid px-2 px-md-4 bg-gray-200">
    <div class="page-header min-height-300 border-radius-xl xxmt-4" style="background-image: url('/oh/fondo2.jpg');">
        <span class="mask bg-gradient-secondary opacity-0"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="
                        @if (strlen($ficha2->logo) == 0) /oh/img/micuentacirculo.jpg
                        @else
                        data:image/jpeg;base64,{{ $ficha2->logo }} @endif
                    " alt="profile_image" class="w-100 xxrounded-circle shadow-sm" />
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ $ficha2->nombre == '' ? 'Mi cuenta' : $ficha2->nombre }}
                    </h5>
                    <p class="mb-0 font-weight-normal text-sm">
                    </p>
                </div>
            </div>
        </div>


        <div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative" nowireignore>
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                    <li></li>
                    <li class="nav-item" wire:click="vseccion(1)">
                        <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 1 ? 'naranjitobold' : '' }}"
                            data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">face</i>
                            <span class="ms-1">Datos personales</span>
                        </a>
                    </li>
                    <li class="nav-item" wire:click="vseccion(2)">
                        <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 2 ? 'naranjitobold' : '' }}"
                            data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">security</i>
                            <span class="ms-1">Seguridad</span>
                        </a>
                    </li>
                    @if(env('TESTMODE'))
                    <li class="nav-item" wire:click="vseccion(7)">
                        <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 7 ? 'naranjitobold' : '' }}"
                            data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">sell</i>
                            <span class="ms-1">Suscripción</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item" wire:click="vseccion(3)">
                        <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 3 ? 'naranjitobold' : '' }}"
                            data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">email</i>
                            <span class="ms-1">Correo electrónico</span>
                        </a>
                    </li>
                    <li class="nav-item" wire:click="vseccion(4)">
                        <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 4 ? 'naranjitobold' : '' }}"
                            data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">payments</i>
                            <span class="ms-1">Formas de pago</span>
                        </a>
                    </li>
                    <li class="nav-item" wire:click="vseccion(5)">
                        <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 5 ? 'naranjitobold' : '' }}"
                            data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">image</i>
                            <span class="ms-1">Imagen de marca</span>
                        </a>
                    </li>
                    @if ($administrador)
                    <li class="nav-item" wire:click="vseccion(6)">
                        <a class="nav-link mb-0 px-0 py-1 {{ $seccion == 6 ? 'naranjitobold' : '' }}"
                            data-bs-toggle="tab" href="" role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">superscript</i>
                            <span class="ms-1">SuperJefes</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>




        <div class="{{ $seccion == 7 ? 'visible' : 'oculto2' }}">
            <!-- suscripcion -->

            @if(1==2)
            <x-table>
                <x-slot name="head">
                    <x-table.heading>Sistema</x-table.heading>
                    <x-table.heading>Fecha de pago</x-table.heading>
                    <x-table.heading>ID de pago</x-table.heading>
                    <x-table.heading>Periodicidad (meses)</x-table.heading>
                    <x-table.heading>Importe</x-table.heading>
                    <x-table.heading>Fecha creación</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @foreach ($sus_cripciones as $tag)
                    <x-table.row wire:key="rowss-{{ $tag->id }}">
                        <x-table.cell>{{ $tag->sistema }}</x-table.cell>
                        <x-table.cell>{{ Utils::datestr($tag->alta) }}</x-table.cell>
                        <x-table.cell>{{ $tag->idexterna }}</x-table.cell>
                        <x-table.cell>{{ $tag->meses }}</x-table.cell>
                        <x-table.cell>{{ $tag->importe }}</x-table.cell>
                        <x-table.cell>{{ Utils::datetime($tag->created_at) }}</x-table.cell>
                    </x-table.row>
                    @endforeach
                </x-slot>
            </x-table>
            @endif


            <div class="row mt-4 mb-4">
                <div class="col-3"></div>
                <div class="col-6">
                    <div class="alert alert-primary blanco negrita text-center" role="alert">
                        @if($suscrito==0)
                            <h5 class="blanco">El periodo de uso gratuito de la aplicación finalizó en {{Utils::datestr($fechacaducidad)}}</h5>
                            actualmente no está suscrito a ningún plan
                        @endif
                        @if($suscrito==1)
                            <h5 class="blanco">El periodo de uso gratuito de la aplicación finaliza en {{Utils::datestr($fechacaducidad)}}</h5>
                            actualmente no está suscrito a ningún plan
                        @endif
                        @if($suscrito==2)
                            <h6 class="blanco">
                                Actualmente está suscrito
                                <a href="{{ route('billing') }}" role="button" class="blanco italica">
                                    Gestionar mi suscripción desde Stripe
                                </a>
                            </h6>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>




            <div class="container-fluid xxpx-5 xxmy-6">
                <div class="card xxmt-n8">
                    <div class="container">
                        <div class="tab-content tab-space">
                            <div class="tab-pane active" id="monthly">
                                <div class="row">
                                    <div class="col-lg-4 mb-lg-0 mb-4">
                                        <div class="card shadow-lg">
                                            <span
                                                class="badge rounded-pill bg-primary xxtext-dark w-30 mt-n2 mx-auto">Mensual</span>
                                            <div class="card-header text-center pt-4 pb-3">
                                                <h1 class="font-weight-bold mt-2">
                                                    <small class="text-lg align-top me-1">€</small>29<small
                                                        class="text-lg">/mes</small>
                                                </h1>
                                            </div>
                                            <div class="card-body text-lg-start text-center pt-0">
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Galerías ilimitadas</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">500Gb de almacenamiento</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Gestión de clientes</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Calendario y reservas</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Pasarela de pagos</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Contratos en línea</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Registro de ingresos y gastos</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Personalización de emails</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Catálogo propio de productos</span>
                                                </div>
                                                <div class="d-flex justify-content-center p-2">
                                                    <span class="ps-3">IVA no incluido</span>
                                                </div>
                                                <a wire:click="stripesubscribe(1)"
                                                    class="btn btn-icon bg-gradient-dark d-lg-block mt-3 mb-0">
                                                    Suscribirse
                                                    <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-lg-0 mb-4">
                                        <div class="card shadow-lg">
                                            <span
                                                class="badge rounded-pill bg-primary xxtext-dark w-30 mt-n2 mx-auto">Trimestral</span>
                                            <div class="card-header text-center pt-4 pb-3">
                                                <h1 class="font-weight-bold mt-2">
                                                    <small class="text-lg align-top me-1">€</small>79<small
                                                        class="text-lg">/trimestre</small>
                                                </h1>
                                            </div>
                                            <div class="card-body text-lg-start text-center pt-0">
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Galerías ilimitadas</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">500Gb de almacenamiento</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Gestión de clientes</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Calendario y reservas</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Pasarela de pagos</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Contratos en línea</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Registro de ingresos y gastos</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Personalización de emails</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Catálogo propio de productos</span>
                                                </div>
                                                <div class="d-flex justify-content-center p-2">
                                                    <span class="ps-3">IVA no incluido</span>
                                                </div>
                                                <a wire:click="stripesubscribe(2)"
                                                    class="btn btn-icon bg-gradient-dark d-lg-block mt-3 mb-0">
                                                    Suscribirse
                                                    <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-lg-0 mb-4">
                                        <div class="card shadow-lg">
                                            <span
                                                class="badge rounded-pill bg-primary xxtext-dark w-30 mt-n2 mx-auto">Anual</span>
                                            <div class="card-header text-center pt-4 pb-3">
                                                <h1 class="font-weight-bold mt-2">
                                                    <small class="text-lg align-top me-1">€</small>290<small
                                                        class="text-lg">/año</small>
                                                </h1>
                                            </div>
                                            <div class="card-body text-lg-start text-center pt-0">
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Galerías ilimitadas</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">500Gb de almacenamiento</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Gestión de clientes</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Calendario y reservas</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Pasarela de pagos</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Contratos en línea</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Registro de ingresos y gastos</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Personalización de emails</span>
                                                </div>
                                                <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                                    <i class="material-icons my-auto">done</i>
                                                    <span class="ps-3">Catálogo propio de productos</span>
                                                </div>
                                                <div class="d-flex justify-content-center p-2">
                                                    <span class="ps-3">IVA no incluido</span>
                                                </div>
                                                <a wire:click="stripesubscribe(3)"
                                                    class="btn btn-icon bg-gradient-dark d-lg-block mt-3 mb-0">
                                                    Suscribirse
                                                    <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-6 mx-auto text-center">
                            <h2>Preguntas frecuentes</h2>
                            <p>A lot of people don&#39;t appreciate the moment until it’s passed. I&#39;m not trying my
                                hardest,
                                and I&#39;m not trying to do </p>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-8 mx-auto">
                            <div class="accordion" id="accordionRental">
                                <div class="accordion-item my-2">
                                    <h5 class="accordion-header" id="headingOne">
                                        <button class="accordion-button border-bottom font-weight-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            How do I order?
                                            <i
                                                class="collapse-close material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">add</i>
                                            <i
                                                class="collapse-open material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">remove</i>
                                        </button>
                                    </h5>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionRental">
                                        <div class="accordion-body text-sm opacity-8">
                                            We’re not always in the position that we want to be at. We’re constantly
                                            growing.
                                            We’re constantly making mistakes. We’re constantly trying to express
                                            ourselves and
                                            actualize our dreams. If you have the opportunity to play this game
                                            of life you need to appreciate every moment. A lot of people don’t
                                            appreciate the
                                            moment until it’s passed.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item my-2">
                                    <h5 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button border-bottom font-weight-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            How can i make the payment?
                                            <i
                                                class="collapse-close material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">add</i>
                                            <i
                                                class="collapse-open material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">remove</i>
                                        </button>
                                    </h5>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionRental">
                                        <div class="accordion-body text-sm opacity-8">
                                            It really matters and then like it really doesn’t matter. What matters is
                                            the people
                                            who are sparked by it. And the people who are like offended by it, it
                                            doesn’t
                                            matter. Because it&#39;s about motivating the doers. Because I’m here to
                                            follow my
                                            dreams and inspire other people to follow their dreams, too.
                                            <br>
                                            We’re not always in the position that we want to be at. We’re constantly
                                            growing.
                                            We’re constantly making mistakes. We’re constantly trying to express
                                            ourselves and
                                            actualize our dreams. If you have the opportunity to play this game of life
                                            you need
                                            to appreciate every moment. A lot of people don’t appreciate the moment
                                            until it’s
                                            passed.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item my-2">
                                    <h5 class="accordion-header" id="headingThree">
                                        <button class="accordion-button border-bottom font-weight-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            How much time does it take to receive the order?
                                            <i
                                                class="collapse-close material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">add</i>
                                            <i
                                                class="collapse-open material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">remove</i>
                                        </button>
                                    </h5>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionRental">
                                        <div class="accordion-body text-sm opacity-8">
                                            The time is now for it to be okay to be great. People in this world shun
                                            people for
                                            being great. For being a bright color. For standing out. But the time is now
                                            to be
                                            okay to be the greatest you. Would you believe in what you believe in, if
                                            you were
                                            the only one who believed it?
                                            If everything I did failed - which it doesn&#39;t, it actually succeeds -
                                            just the
                                            fact that I&#39;m willing to fail is an inspiration. People are so scared to
                                            lose
                                            that they don&#39;t even try. Like, one thing people can&#39;t say is that
                                            I&#39;m
                                            not trying, and I&#39;m not trying my hardest, and I&#39;m not trying to do
                                            the best
                                            way I know how.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item my-2">
                                    <h5 class="accordion-header" id="headingFour">
                                        <button class="accordion-button border-bottom font-weight-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                            aria-expanded="false" aria-controls="collapseFour">
                                            Can I resell the products?
                                            <i
                                                class="collapse-close material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">add</i>
                                            <i
                                                class="collapse-open material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">remove</i>
                                        </button>
                                    </h5>
                                    <div id="collapseFour" class="accordion-collapse collapse"
                                        aria-labelledby="headingFour" data-bs-parent="#accordionRental">
                                        <div class="accordion-body text-sm opacity-8">
                                            I always felt like I could do anything. That’s the main thing people are
                                            controlled
                                            by! Thoughts- their perception of themselves! They&#39;re slowed down by
                                            their
                                            perception of themselves. If you&#39;re taught you can’t do anything, you
                                            won’t do
                                            anything. I was taught I could do everything.
                                            <br><br>
                                            If everything I did failed - which it doesn&#39;t, it actually succeeds -
                                            just the
                                            fact that I&#39;m willing to fail is an inspiration. People are so scared to
                                            lose
                                            that they don&#39;t even try. Like, one thing people can&#39;t say is that
                                            I&#39;m
                                            not trying, and I&#39;m not trying my hardest, and I&#39;m not trying to do
                                            the best
                                            way I know how.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item my-2">
                                    <h5 class="accordion-header" id="headingFifth">
                                        <button class="accordion-button border-bottom font-weight-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFifth"
                                            aria-expanded="false" aria-controls="collapseFifth">
                                            Where do I find the shipping details?
                                            <i
                                                class="collapse-close material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">add</i>
                                            <i
                                                class="collapse-open material-icons text-sm font-weight-bold pt-1 position-absolute end-0 me-3">remove</i>
                                        </button>
                                    </h5>
                                    <div id="collapseFifth" class="accordion-collapse collapse"
                                        aria-labelledby="headingFifth" data-bs-parent="#accordionRental">
                                        <div class="accordion-body text-sm opacity-8">
                                            There’s nothing I really wanted to do in life that I wasn’t able to get good
                                            at.
                                            That’s my skill. I’m not really specifically talented at anything except for
                                            the
                                            ability to learn. That’s what I do. That’s what I’m here for. Don’t be
                                            afraid to be
                                            wrong because you can’t learn anything from a compliment.
                                            I always felt like I could do anything. That’s the main thing people are
                                            controlled
                                            by! Thoughts- their perception of themselves! They&#39;re slowed down by
                                            their
                                            perception of themselves. If you&#39;re taught you can’t do anything, you
                                            won’t do
                                            anything. I was taught I could do everything.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @push('js')
            <script src="{{ asset('assets') }}/js/plugins/parallax.min.js"></script>
            <script>
                if (document.getElementsByClassName('page-header')) {
                window.addEventListener('scroll', function () {
                    var scrollPosition = window.pageYOffset;
                    var bgParallax = document.querySelector('.page-header');
                    var limit = bgParallax.offsetTop + bgParallax.offsetHeight;
                    if (scrollPosition > bgParallax.offsetTop && scrollPosition <= limit) {
                        bgParallax.style.backgroundPositionY = (50 - 10 * scrollPosition / limit * 3) + '%';
                    } else {
                        bgParallax.style.backgroundPositionY = '50%';
                    }
                });
            }
    
            </script>
            @endpush





        </div>

        <div class="{{ $seccion == 1 ? 'visible' : 'oculto2' }}">
            <!-- datos personales -->

            <div class="row mt-3">
                <div class="col-12 col-md-12 position-relative">
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Mis datos</h6>
                        </div>
                        <div class="card-body p-3 row">
                            <!-- col para smart colmd para pc -->
                            <x-input :tipo="'name'" :col="12" :colmd="12" :idfor="'inombre'" :model="'ficha2.nombre'"
                                :titulo="'Nombre comercial'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'name'" :col="12" :colmd="12" :idfor="'inombre2'" :model="'ficha2.nombre2'"
                                :titulo="'Nombre propio'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'email'" :col="12" :colmd="6" :idfor="'mailimail'"
                                :model="'ficha2.mail_direccion'" :titulo="'Email de la empresa'" :disabled="''"
                                :maxlen="'200'" :change="''" />
                            <x-input :tipo="'text'" :col="12" :colmd="6" :idfor="'inif'" :model="'ficha2.nif'"
                                :titulo="'NIF/CIF'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'text'" :col="12" :colmd="9" :idfor="'idom'" :model="'ficha2.domicilio'"
                                :titulo="'Domicilio'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'text'" :col="12" :colmd="5" :idfor="'itel'" :model="'ficha2.telefono'"
                                :titulo="'Teléfono'" :disabled="''" :maxlen="''" :change="''" />
                            <x-separador />
                            <x-input :tipo="'text'" :col="12" :colmd="4" :idfor="'icpos'" :model="'ficha2.codigopostal'"
                                :titulo="'Código postal'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'text'" :col="12" :colmd="4" :idfor="'icpob'" :model="'ficha2.poblacion'"
                                :titulo="'Población'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'text'" :col="12" :colmd="4" :idfor="'icprov'" :model="'ficha2.provincia'"
                                :titulo="'Provincia'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'text'" :col="9" :colmd="6" :idfor="'iiban'" :model="'ficha2.iban'"
                                :titulo="'IBAN (solo se usa para mostrar en la forma de pago manual transferencia)'"
                                :disabled="''" :maxlen="''" :change="''" />
                            <x-separador />
                            <div class="col-12 text-center">
                                <button wire:click="updateuser2(false)" class="btn btn-dark mt-3 text-center">Actualizar
                                    datos personales</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="{{ $seccion == 2 ? 'visible' : 'oculto2' }}">
            <!-- seguridad -->
            <div class="row mt-3">
                <div class="col-12 col-md-12 position-relative">
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Seguridad de la cuenta</h6>
                            <p class="mb-0 font-weight-normal text-sm">
                                Para cambiar el email de la cuenta introduzca el nuevo email y su contraseña
                                actual<br />Para cambiar la contraseña introduzca la contraseña actual y la nueva
                                contraseña
                            </p>
                        </div>
                        <div class="card-body p-3 row">
                            <!-- col para smart colmd para pc -->
                            <x-input :tipo="'email'" :col="12" :colmd="6" :idfor="'imail'" :model="'email'"
                                :titulo="'Email de la empresa'" :disabled="''" :maxlen="''" :change="''" />
                            <x-separador />
                            <x-input :tipo="'password'" :col="6" :colmd="4" :idfor="'contra'" :model="'contra'"
                                :titulo="'Contraseña actual'" :disabled="''" :maxlen="'20'" :change="''" />
                            <x-input :tipo="'password'" :col="6" :colmd="4" :idfor="'contra1'" :model="'contra1'"
                                :titulo="'Nueva contraseña'" :disabled="''" :maxlen="'20'" :change="''" />
                            <x-input :tipo="'password'" :col="6" :colmd="4" :idfor="'contra2'" :model="'contra2'"
                                :titulo="'Repita contraseña'" :disabled="''" :maxlen="'20'" :change="''" />
                            <div class="col-12 text-center">
                                <button wire:click="updatesecurity" class="btn btn-dark mt-3 text-center">Actualizar
                                    datos de seguridad</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-12 position-relative">
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Autenticación opcional de doble factor (inicio de sesión con código único,
                                mejora la seguridad)</h6>
                            <p>(requiere de Google Authenticator, Microsoft Authenticator ó similar)</p>
                            @if (session('error2fa'))
                            <div class="row">
                                <div class="alert alert-danger alert-dismissible text-white" role="alert">
                                    <span class="text-sm">{{ Session::get('error2fa') }}</span>
                                    <button type="button" class="btn-close text-lg py-3 opacity-10"
                                        data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            @endif
                            @if (session('ok2fa'))
                            <div class="row">
                                <div class="alert alert-success alert-dismissible text-white" role="alert">
                                    <span class="text-sm">{{ Session::get('ok2fa') }}</span>
                                    <button type="button" class="btn-close text-lg py-3 opacity-10"
                                        data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            @endif
                            @if ($doblefactor == 0)
                            <h6 class="mb-0">Autenticación de doble factor
                                <span class="badge badge-danger ms-auto mb-auto">Inactiva</span>
                            </h6>
                            <div class="row">
                                <div class="col-12 mt-5 mb-5">
                                    <x-input :tipo="'password'" :col="6" :colmd="4" :idfor="'contra2a'"
                                        :model="'contraactual2'"
                                        :titulo="'Para activar, introduzca primero su contraseña actual'" :disabled="''"
                                        :maxlen="'20'" :change="''" />
                                </div>
                            </div>
                            <button class="btn bg-gradient-dark btn-sm mb-0" wire:click="activar2fa">Iniciar
                                activación</button>
                            @endif
                            @if ($doblefactor == 2)
                            <h6 class="mb-0">Autenticación de doble factor
                                <span class="badge badge-success ms-auto mb-auto">Activa</span>
                            </h6>
                            <div class="row">
                                <div class="col-12 mt-5 mb-5">
                                    <x-input :tipo="'password'" :col="6" :colmd="4" :idfor="'contra2b'"
                                        :model="'contraactual2'" :titulo="'Contraseña actual'" :disabled="''"
                                        :maxlen="'20'" :change="''" />
                                </div>
                            </div>
                            <button class="btn bg-gradient-dark btn-sm mb-0"
                                wire:click="desactivar2fa">Desactivar</button>
                            @endif
                            @if ($doblefactor == 1)
                            <div class="card-body pt-0 text-center">
                                <p>Configure su aplicación de autenticación de doble factor escaneando el siguiente
                                    código.
                                    Alternativamente puede usar el código <b>{{ $secret }}<br />GUARDE ESTE
                                        CÓDIGO PARA
                                        PODER ACCEDER A SU CUENTA EN CASO DE FALLO DE AUTENTICACIÓN.</b></p>
                                <div>
                                    {!! $qr !!}
                                </div>
                                <p>Una vez configurada la aplicación introduzca el código de la aplicación para terminar
                                    la
                                    activación:</p>

                                <p>Si el registro es correcto se redirigirá a la pantalla de inicio de sesión</p>
                                <div>
                                    <input wire:model.lazy="codigoga" type="text" style="width:50%"
                                        class=" border border-2 p-2 textnegro text-center" placeholder="" maxlength="6">
                                </div>

                                <div>
                                    <button class="btn bg-gradient-dark btn-sm mb-0 mt-4"
                                        wire:click="confirmar2fa">Completar
                                        registro</button>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{{ $seccion == 3 ? 'visible' : 'oculto2' }}">
            <!-- correo electronico -->
            <div class="row mt-3">
                <div class="col-12 col-md-12 position-relative">
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Utilizar mi propia cuenta de correo electrónico</h6>
                            <p class="mb-0 font-weight-normal text-sm">
                                Si no configura su cuenta de correo el sistema utilizará nuestra cuenta de correo
                                {{ env('MAIL_FROM_ADDRESS', 'info@ohmyphoto.es') }}.<br />
                                Puede configurar su propia cuenta de correo a continuación.<br />
                                Si configura una cuenta de Gmail, puede crear una contraseña de aplicación 'OhMyPhoto'
                                desde
                                la configuración de su Gmail (<a target="_blank"
                                    href="https://support.google.com/mail/answer/185833?hl=es">https://support.google.com/mail/answer/185833?hl=es</a>).<br />
                                Solo utilizamos el puerto 587 tls para el servidor de correo saliente.
                            </p>
                        </div>
                        <div class="card-body p-3 row">
                            <!-- col para smart colmd para pc -->
                            <x-input :tipo="'email'" :col="12" :colmd="6" :idfor="'mailimail2'"
                                :model="'ficha2.mail_direccion'" :titulo="'Email de la empresa'" :disabled="''"
                                :maxlen="'200'" :change="''" />
                            <x-separador />
                            <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'mailuser'"
                                :model="'ficha2.mail_username'" :titulo="'Nombre de usuario'" :disabled="''"
                                :maxlen="'200'" :change="''" />
                            <x-input :tipo="'password'" :col="6" :colmd="4" :idfor="'mailpass'"
                                :model="'ficha2.mail_password'" :titulo="'Contraseña'" :disabled="''" :maxlen="'200'"
                                :change="''" />
                            <x-input :tipo="'text'" :col="6" :colmd="4" :idfor="'mailsmtp'" :model="'ficha2.mail_smtp'"
                                :titulo="'Servidor de correo saliente SMTP'" :disabled="''" :maxlen="'200'"
                                :change="''" />
                            <div class="col-12 text-center">
                                <button wire:click="updateuser2(true)" class="btn btn-dark mt-3 text-center">Actualizar
                                    datos de correo</button>
                            </div>
                            @if($errormail)
                            <div class="col-12 text-center">
                                <p class="rojo">{{$errormail}}</p>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{{ $seccion == 4 ? 'visible' : 'oculto2' }}">
            <!-- formas de pago -->
            <div class="row mt-3">
                <div class="col-12 col-md-12 position-relative">
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Formas de pago</h6>
                        </div>
                        <div class="card-body p-3 row">
                            <!-- col para smart colmd para pc -->

                            <x-inputboolean :model="'ficha3.efectivo'"
                                :titulo="'Permitir forma de pago efectivo (la marca de pagado se hace manualmente)'"
                                :maxlen="''" :idfor="'ifpefectivo'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            <x-inputboolean :model="'ficha3.transferencia'"
                                :titulo="'Permitir forma de pago transferencia (la marca de pagado se hace manualmente)'"
                                :maxlen="''" :idfor="'ifptra'" :col="12" :colmd="12" :disabled="''" :change="''" />
                            <x-inputboolean :model="'ficha3.redsys'"
                                :titulo="'Permitir forma de pago Redsys (en el momento del pago se marca la galería como pagada)'"
                                :maxlen="''" :idfor="'ifprs'" :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="4" :colmd="5" :idfor="'rscc'" :model="'ficha3.rscodcomercio'"
                                :titulo="'Redsys código de comercio'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'password'" :col="4" :colmd="4" :idfor="'rscl'" :model="'ficha3.rsclacomercio'"
                                :titulo="'Redsys clave de comercio'" :disabled="''" :maxlen="''" :change="''" />

                            <x-input :tipo="'number'" :col="4" :colmd="3" :idfor="'rster'" :model="'ficha3.rsterminal'"
                                :titulo="'Código de terminal'" :disabled="''" :maxlen="''" :change="''" />

                            <x-inputboolean :model="'ficha3.paypal'"
                                :titulo="'Permitir forma de pago Paypal (en el momento del pago se marca la galería como pagada)'"
                                :maxlen="''" :idfor="'ifppal'" :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="6" :colmd="5" :idfor="'rsclid'" :model="'ficha3.ppalclientid'"
                                :titulo="'Paypal Client id'" :disabled="''" :maxlen="''" :change="''" />
                            <x-input :tipo="'password'" :col="6" :colmd="4" :idfor="'rsclscrt'" :model="'ficha3.ppalsecret'"
                                :titulo="'Paypal Secret'" :disabled="''" :maxlen="''" :change="''" />

                            <x-input :tipo="'number'" :col="4" :colmd="3" :idfor="'ppprc'" :model="'ficha3.ppalprc'"
                                :titulo="'% incremento pago Paypal'" :disabled="''" :maxlen="''" :change="''" />

                            <x-inputboolean :model="'ficha3.bizum'"
                                :titulo="'Permitir forma de pago Bizum (manual, la marca de pago se hace manualmente)'"
                                :maxlen="''" :idfor="'ifpbz'" :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="7" :colmd="7" :idfor="'rsclb'" :model="'ficha3.bizumtelefono'"
                                :titulo="'Teléfono para Bizum'" :disabled="''" :maxlen="''" :change="''" />

                            <x-inputboolean :model="'ficha3.stripe'"
                                :titulo="'Permitir forma de pago Stripe (en el momento del pago se marca la galería como pagada)'"
                                :maxlen="''" :idfor="'ifpstr'" :col="12" :colmd="12" :disabled="''" :change="''" />

                            <x-input :tipo="'text'" :col="6" :colmd="5" :idfor="'strpub'"
                                :model="'ficha3.stripe_publica'" :titulo="'Clave Stripe pública'" :disabled="''"
                                :maxlen="''" :change="''" />
                            <x-input :tipo="'password'" :col="6" :colmd="5" :idfor="'strsec'"
                                :model="'ficha3.stripe_secreta'" :titulo="'Clave Stripe secreta'" :disabled="''"
                                :maxlen="''" :change="''" />

                            <x-separador />

                            <div class="col-12 text-center">
                                <button wire:click="updatefpago" class="btn btn-dark mt-3 text-center">Actualizar
                                    datos de las formas de pago</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{{ $seccion == 5 ? 'visible' : 'oculto2' }}">
            <!-- imagen de marca -->
            <div class="row mt-3">
                <div class="col-12 col-md-12 position-relative">
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Imagen de marca</h6>
                        </div>
                        <div class="card-body p-3 row">
                            <!-- col para smart colmd para pc -->
                            <div class="col-12 col-md-6">
                                <p class="mb-0 font-weight-normal text-sm">
                                    Logotipo de la empresa
                                </p>
                                @if (strlen($ficha2->logo) > 0)
                                <p class="mb-0 font-weight-normal text-sm puntero italica" wire:click="deleteimagelogo">
                                    Eliminar imagen
                                </p>
                                @endif
                                <img id="idfot1" src="
                            @if (strlen($ficha2->logo) == 0) /oh/img/micuentacirculo.jpg
                            @else
                            data:image/jpeg;base64,{{ $ficha2->logo }} @endif
                                " class="img-fluid shadow border-radius-xl" />
                                <x-filepond wire:model="files1" maxsize="1MB" resize="false" width="425" height="283"
                                    varname="fi1" w5sec="true" />
                            </div>

                            <div class="col-12 col-md-6">
                                <p class="mb-0 font-weight-normal text-sm">
                                    Marca de agua (preferible png transparente, ~ 700px de ancho)
                                </p>

                                @if (strlen($ficha2->marcaagua) > 0)
                                <p class="mb-0 font-weight-normal text-sm puntero italica"
                                    wire:click="deleteimagewater">
                                    Eliminar imagen
                                </p>
                                @endif


                                <img id="idfot2" src="
                            @if (strlen($ficha2->marcaagua) == 0) /oh/img/micuentaagua.png
                            @else
                            data:image/jpeg;base64,{{ $ficha2->marcaagua }} @endif
                                " class="img-fluid shadow border-radius-xl" />
                                <x-filepond wire:model="files2" maxsize="1MB" resize="false" width="425" height="283"
                                    varname="fi2" w5sec="true" />
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-12 position-relative">
                    <div class="card card-plain h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Contratos</h6>
                        </div>
                        <div class="card-body p-3 row">
                            <!-- col para smart colmd para pc -->
                            <p class="mb-0 font-weight-normal text-sm">
                                Al crear un contrato se mostrará esta firma además de la firma del propio cliente. Puede
                                grabar la firma directamente desde el cuadro de firma ó seleccionar una imagen de firma.
                            </p>

                            <p class="mb-0 font-weight-normal text-sm">
                                Adjuntar imagen:
                            </p>
                            <x-filepond wire:model="files3" maxsize="1MB" resize="false" width="425" height="283"
                                varname="fi3" w5sec="true" />


                            <p class="mb-0 font-weight-normal text-sm">
                                Cuadro de firma:
                            </p>
                            <div class="col-xs-12">
                                <form method="POST" action="/recibirfirmaalbaran">
                                    <!--<label class="" for="">Guardar firma:</label>-->
                                    <br />
                                    <div id="sig" wire:ignore></div>
                                    <br />
                                    <input name="documento" id="documento" type="hidden" value="" />
                                    <textarea wire:model="firma" id="signature64" name="signed"
                                        style="display: none;"></textarea>
                                </form>
                            </div>
                            @if($botonesfirma)
                            <div class="col-xs-12 text-center">
                                <button id="clear" class="btn btn-dark mt-3 text-center">limpiar imagen</button>
                                <button id="saveform" class="btn btn-dark mt-3 text-center">guardar firma</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{{ $seccion == 6 ? 'visible' : 'oculto2' }}">
            <!-- superboss usuario root clave 1234 -->




            @if($userid==6)
            <p>Accesos ilegales: {{count($ilegales)}}</p>
            @if($ilegales)
            <button wire:click="vaciailegales">vaciar ilegales</button>
            <x-table>
                <x-slot name="head">
                    <x-table.heading>IP</x-table.heading>
                    <x-table.heading>ruta</x-table.heading>
                    <x-table.heading>navegador</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @foreach ($ilegales as $tag)
                    <x-table.row wire:key="rowil-{{ $tag->id }}">
                        <x-table.cell>{{ $tag->ip }}</x-table.cell>
                        <x-table.cell>{{ $tag->ruta }}</x-table.cell>
                        <x-table.cell>{{ $tag->navegador }}</x-table.cell>
                    </x-table.row>
                    @endforeach
                </x-slot>
            </x-table>
            @endif
            <p>registro de log</p>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="20">
                {{$log}}
            </textarea>
            <button wire:click="vacialog">vaciar</button>
            @endif




            <div class="text-center mt-4 mb-4 rojo">
                {{count($usuarios)}} cuentas registradas
            </div>
            <x-table>
                <x-slot name="head">
                    <x-table.heading><a href="#" class="naranjito" wire:click="lusuarios('id')">ID</a></x-table.heading>
                    <x-table.heading><a href="#" class="naranjito" wire:click="lusuarios('email')">email</a>
                    </x-table.heading>
                    <x-table.heading><a href="#" class="naranjito" wire:click="lusuarios('name')">nombre</a>
                    </x-table.heading>
                    <x-table.heading><a href="#" class="naranjito" wire:click="lusuarios('galerias desc')">galerias</a>
                    </x-table.heading>
                    <x-table.heading><a href="#" class="naranjito" wire:click="lusuarios('gigas desc')">gigas</a>
                    </x-table.heading>
                    <x-table.heading><a href="#" class="naranjito" wire:click="lusuarios('created_at desc')">fecha
                            alta</a></x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @foreach ($usuarios as $tag)
                    <x-table.row wire:key="row-{{ $tag->id }}">
                        <x-table.cell>{{ $tag->id }}</x-table.cell>
                        <x-table.cell>{{ $tag->email }}</x-table.cell>
                        <x-table.cell>{{ $tag->name }}</x-table.cell>
                        <x-table.cell class="text-end">{{ $tag->galerias }}</x-table.cell>
                        <x-table.cell>{{ $tag->gigas }}</x-table.cell>
                        <x-table.cell>{{ Utils::datetime($tag->created_at) }}</x-table.cell>
                    </x-table.row>
                    @endforeach
                </x-slot>
            </x-table>


        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
@endpush

@push('js')
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery-ui.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.ui.touch-punch.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.signature.min.js"></script>
<script>
    $(document).ready(function() {
            var sig;
            var image;
            sig = $('#sig').signature({
                syncField: '#signature64',
                syncFormat: 'PNG',
            });
            $('#clear').click(function(e) {
                e.preventDefault();
                sig.signature('clear');
                $("#signature64").val('');
            });
            $('#saveform').click(function(e) {
                e.preventDefault();
                image = $('#signature64').val();
                @this.savesign(image);
            });
        });

        window.addEventListener('vselectsetvalue', event => {
            $('#sig').signature().signature('draw', 'data:image/png;base64,' + event.detail[0].idcli);
        });
</script>

<script></script>
@endpush