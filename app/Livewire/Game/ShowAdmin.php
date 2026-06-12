<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;

class ShowAdmin extends Component
{
    public Game $game;

    public function setAvailability($memberId, $value)
    {
        $this->game->members()->updateExistingPivot($memberId, [
            'availability' => $value
        ]);
    }

    public function toggleSelection($memberId)
    {
        $pivot = $this->game->members()->find($memberId)->pivot;

        $this->game->members()->updateExistingPivot($memberId, [
            'selected' => !$pivot->selected
        ]);
    }

    public function render()
    {
        return view('livewire.game.show-admin', [
            'members' => $this->game->members
        ])->layout('layouts.app');
    }
}
