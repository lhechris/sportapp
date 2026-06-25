<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Games extends Component
{
    public Team $team;
    public $nextGameId;

    public function mount()
    {
        $now = \Carbon\Carbon::now();
        $this->nextGameId = null;
        foreach ($this->team->games as $game) {
            if ($game->date >= $now) {
                $this->nextGameId = $game->id;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.team.games');
    }
}
