<div class="col-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
    <div class="nav-wrapper position-relative" nowireignore>
        <ul class="nav nav-pills nav-fill p-1" role="tablist">
            <li></li>
            <li class="nav-item" wire:click="mostrarsolo(1)">
                <a class="nav-link mb-0 px-0 py-1 active {{$seleccionamostrar==1?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                    role="tab" aria-selected="true">
                    <i class="material-icons text-lg position-relative">apps</i>
                    <span class="ms-1">Todas</span>
                </a>
            </li>
            <li class="nav-item" wire:click="mostrarsolo(2)">
                <a class="nav-link mb-0 px-0 py-1 {{$seleccionamostrar==2?'naranjitobold':''}}" data-bs-toggle="tab" href="" role="tab"
                    aria-selected="false">
                    <i class="material-icons text-lg position-relative">favorite</i>
                    <span class="ms-1">Seleccionadas</span>
                </a>
            </li>
            <li class="nav-item" wire:click="mostrarsolo(3)">
                <a class="nav-link mb-0 px-0 py-1  {{$seleccionamostrar==3?'naranjitobold':''}}" data-bs-toggle="tab" href=""
                    role="tab" aria-selected="false">
                    <i class="material-icons text-lg position-relative">close</i>
                    <span class="ms-1">No seleccionadas</span>
                </a>
            </li>

            <!--
            <li class="nav-item" wire: click="zoom(1)">
                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href=""
                    role="tab" aria-selected="false" title="ampliar zoom">
                    <i class="material-icons text-lg position-relative">zoom_in</i>
                </a>
            </li>
            <li class="nav-item" wire: click="zoom(-1)">
                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href=""
                    role="tab" aria-selected="false" title="reducir zoom">
                    <i class="material-icons text-lg position-relative">zoom_out</i>
                </a>
            </li>
            
    
-->


            
        </ul>
    </div>
</div>
