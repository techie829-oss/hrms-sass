<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        if (saas_tenant('id')) {
            return view('layouts.tenant.app');
        }
        return view('layouts.app');
    }
}
