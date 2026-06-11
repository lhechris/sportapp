<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;

class Dashboard extends Component
{
    public $teams;

    public function mount()
    {
        // équipes du user (via owner)
        $this->teams = Team::where('owner_id', auth()->id())->get();
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
