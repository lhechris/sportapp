<?php

namespace App\Livewire\Game;

use Livewire\Component;

class Availability extends Component
{
    public Game $game;

    public function setAvailability($memberId, $value)
    {
        $this->game->members()->updateExistingPivot($memberId, [
            'availability' => $value
        ]);
    }

    public function render()
    {
        return view('livewire.game.availability', [
            'members' => $this->game->members
        ])->layout('layouts.app');
    }
}
