<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Show extends Component
{
    public Team $team;
    public $nextGameId;
    public $nextTrainingId;
    public $members;

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

        $this->nextTrainingId = null;
        foreach ($this->team->trainings as $training) {
            if ($training->date >= $now) {
                $this->nextTrainingId = $training->id;
                break;
            }
        }

        $this->members = $this->team->members()
                    ->withCount(['games as games_count' => function ($query) {
                        $query->where('game_member.selected', 1);
                    }])
                    ->withCount(['trainings as trainings_count' => function ($query) {
                        $query->where('member_training.present', 'yes');
                    }])
                    ->where('type',\App\Models\Member::TYPE_PLAYER)
                    ->get();
    }

    public function render()
    {
        return view('livewire.team.show', [
            'members' => $this->members,
            'nextGameId' => $this->nextGameId,
            'nextTrainingId' => $this->nextTrainingId,
        ])->layout('layouts.app');
    }
}
