<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $layout = request()->routeIs('ikli-survey.*')
            ? 'layouts.ikli-survey'
            : 'layouts.guest';

        return view($layout);
    }
}
