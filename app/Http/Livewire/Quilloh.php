<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class Quilloh extends Component
{
    #[Modelable]
    public string | null $value = '';

    #[Locked]
    public string $quillId;

    public string $theme;

    public string $idid;

    public function mount(string $theme = 'snow',$idid): void
    {
        $this->theme = $theme;
        $this->idid = $idid;
        $this->quillId = 'ql-editor-'.Str::uuid()->toString();
        $this->quillId = $idid;
    }

    public function updatedValue($value): void
    {
        $this->value = $value;
    }
    public function render(): View
    {
        return view('livewire.quilloh');
    }
}
