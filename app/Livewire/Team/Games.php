<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Games extends Component
{
    public Team $team;
    public $games;
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

        $this->games = $this->team->games()
            ->withCount(['members as members_count' => function ($query) {
                    $query->where('game_member.selected', 1);
            }])
            ->orderBy('date')
            ->get();
    }

    public function render()
    {
        return view('livewire.team.games');
    }
}
