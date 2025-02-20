<?php

namespace App\View\Components;

use App\Models\Zona as ZonaModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Zona extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ZonaModel $zona,
        public bool $showActions = true,
        public bool $showDates = true,
        public bool $showOperators = true
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.zona');
    }
}
