<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Members extends Component
{
    public Team $team;
    public $members;
    public $coaches;
    public $owners;

    public function mount()
    {
        $this->members = $this->team->members()
                    ->withCount(['games as games_count' => function ($query) {
                        $query->where('game_member.selected', 1);
                    }])
                    ->withCount(['trainings as trainings_count' => function ($query) {
                        $query->where('member_training.present', 'yes');
                    }])
                    ->where('type',\App\Models\Member::TYPE_PLAYER)
                    ->get();

        $this->coaches = $this->team->coaches()->get();
        $this->owners = $this->team->owners()->get();

    }

    public function render()
    {
        return view('livewire.team.members');
    }
}
