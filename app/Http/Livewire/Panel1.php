<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Galeria;
use App\Models\Binarios;
use Illuminate\Support\Facades\Auth;

class Panel1 extends Component
{
    public $numGalerias;
    public $numFotos;
    public $megasOcupadas;

    public function mount()
    {
        $user = Auth::user();
        $this->numGalerias = Galeria::where('user_id', $user->id)->count();

        // Obtener las galerías del usuario
        $galeriasIds = Galeria::where('user_id', $user->id)->pluck('id');

        // Calcular el número de fotos y el tamaño total ocupado
        $this->numFotos = Binarios::whereIn('galeria_id', $galeriasIds)->count();
        $this->megasOcupadas = round(Binarios::whereIn('galeria_id', $galeriasIds)->sum('originalsize') / (1024 * 1024), 2); // Asumiendo que el tamaño está en bytes
    }

    public function render()
    {
        return view('livewire.panel1');
    }
}
