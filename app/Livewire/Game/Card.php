<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;
use App\Models\Member;

class Card extends Component
{
    public Game $game;
    public Member $member;
    public $availability;

    public function mount()
    {
        // Charge la valeur initiale du pivot
        $this->availability = $this->game->members()->where('member_id', $this->member->id)->first()?->pivot?->availability;
    }

    public function setAvailability($memberId, $gameId, $value)
    {
        $game = Game::find($gameId);

        if (!$game) {
            return;
        }

        $game->members()->updateExistingPivot($memberId, [
            'availability' => $value,
        ]);

        $this->availability=$value;
    }

    public function render()
    {
        return view('livewire.game.card');
    }
}
