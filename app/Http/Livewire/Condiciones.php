<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Log;
use App\Http\Utils;
use Illuminate\Support\Facades\Auth;

class Condiciones extends Component
{
    public function mount(){
    }
    public function render()
    {
        return view('livewire.condiciones');
    }
}
