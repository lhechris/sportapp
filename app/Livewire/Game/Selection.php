<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Models\Game;

class Selection extends Component
{
    public Game $game;

    public function toggleSelection($memberId)
    {
        $pivot = $this->game->members()->find($memberId)->pivot;

        $this->game->members()->updateExistingPivot($memberId, [
            'selected' => !$pivot->selected
        ]);
    }

    public function render()
    {
        $players = $this->game->members;

        return view('livewire.game.selection', compact('players'))
            ->layout('layouts.app');
    }
}