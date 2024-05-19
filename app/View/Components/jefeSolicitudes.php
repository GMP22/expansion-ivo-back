<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class jefeSolicitudes extends Component
{   

    public $valor;

    /**
     * Create a new component instance.
     */
    public function __construct($entrada)
    {
        $this->valor = $entrada;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.jefe-solicitudes');
    }
}
