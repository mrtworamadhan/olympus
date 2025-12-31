<?php

namespace App\Livewire;

use Livewire\Component;

class PlatformLanding extends Component
{
    public function render()
    {
        return view('livewire.platform-landing', [
            'activeEvents' => \App\Models\Event::with('tenant')
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get()
        ]);
    }
}
