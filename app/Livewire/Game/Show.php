<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;

class Show extends Component
{
    public Game $game;
    public $members;

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        $memberIds = auth()->user()->members()->pluck('members.id');

        $this->members = $this->game->members()
            ->whereIn('members.id', $memberIds)
            ->get();
    }


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
        return view('livewire.game.show', [
            'members' => $this->members
        ])->layout('layouts.app');
    }
}
