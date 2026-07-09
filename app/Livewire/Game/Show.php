<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;

class Show extends Component
{
    public Game $game;
    public $members;
    public $players;

    private $memberIds;

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        $this->memberIds = auth()->user()->members()->pluck('members.id');
        $this->players = $this->game->members()->get();

        $this->members = $this->game->members()
            ->whereIn('members.id', $this->memberIds)
            ->get();
    }


    public function setAvailability($memberId, $value)
    {
        /* Protection uniquement les membres de l'utilisateur peuvent être modifiés */
        if ($this->memberIds->contains($memberId) ) {
            $this->game->members()->updateExistingPivot($memberId, [
                'availability' => $value
            ]);
        }
    }

    public function render()
    {
        return view('livewire.game.show')->layout('layouts.app');
    }
}
