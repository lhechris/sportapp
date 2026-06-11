<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Show extends Component
{
    public Team $team;

    public function render()
    {
        return view('livewire.team.show', [
            'members' => $this->team->members
        ])->layout('layouts.app');
    }
}
