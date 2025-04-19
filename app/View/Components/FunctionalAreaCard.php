<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FunctionalAreaCard extends Component
{

    public $functionalArea;
    public $functional_area_id_num_jobs;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $functionalArea,
        $functional_area_id_num_jobs,
    ) {
        $this->$functionalArea = $functionalArea;
        $this->$functional_area_id_num_jobs = $functional_area_id_num_jobs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.functional-area-card');
    }
}
