<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Show extends Component
{
    public Team $team;
    public string $activeTab = 'members';

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.team.show')->layout('layouts.app');
    }
}
