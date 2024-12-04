<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class inputdatetime extends Component
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
        public string $maxlen="",
        public string $disabled="",

    )
    {
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputdatetime');
    }
}
