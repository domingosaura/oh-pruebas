<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class inputboolean2 extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $col,
        public string $colmd,
        public string $idfor,
        public string $model,
        public string $titulo,
        public string $change,
        public string $disabled="",

    )
    {
        //:col="12" :colmd="6" :idfor="inombre" :model="nombre" :titulo="Nombre
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputboolean2');
    }
}
