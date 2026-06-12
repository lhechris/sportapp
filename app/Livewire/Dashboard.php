<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Team;

class Dashboard extends Component
{
    public $members;
    public $teams;

    public function mount()
    {
        // équipes du user (via owner)
        if (auth()->user()) {
            if (auth()->user()->isCoach()) {
                $this->teams = Team::where('owner_id', auth()->id())->get();

            } else {
                $this->members = auth()->user()->members()->with('games')->get();
            }
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

        $this->members = auth()->user()->members()->with('games')->get();
    }

    public function render()
    {
        if (auth()->user() && auth()->user()->isCoach()){
            return view('livewire.dashboard')
                ->layout('layouts.app');
        
        } else if (auth()->user() && auth()->user()->isParent()){
            return view('livewire.dashboard-parent')
                ->layout('layouts.app');
        }
        else {
             abort(403);
        }
    }
}
