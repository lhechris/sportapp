<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;
use App\Models\Member;
use Carbon\Carbon;

class Card extends Component
{
    public Game $game;
    public Member $member;
    public $availability;
    public $isSelected = -1;

    public function mount()
    {
        $gameMember = $this->game->members()
            ->where('member_id', $this->member->id)
            ->first();

        $this->availability = $gameMember?->pivot?->availability;
        $mercredi = Carbon::parse($this->game->date)
                        ->startOfWeek(Carbon::MONDAY)
                        ->addDays(2)
                        ->endOfDay();

        if (Carbon::now()->isAfter($mercredi)) {
            $isSel = (bool) ($gameMember?->pivot?->selected);
            $this->isSelected = $isSel?1:0;
        }
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
