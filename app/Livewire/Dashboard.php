<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Team;
use App\Models\Member;

use Carbon\Carbon;

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

            }
            
            $this->members = auth()->user()->members()
                ->with('games')
                ->where('type',Member::TYPE_PLAYER)
                ->get();
         
        }
        $this->checkPendingGames();
    }

    /**
     * Verifie s'il y a des matchs qui n'ont pas été validée 
     * pour genérer une notification
     */
    public function checkPendingGames()
    {

        $user = auth()->user();

        $members = $user->members()->pluck('members.id');

        $games = \App\Models\Game::whereHas('members', function ($query) use ($members) {
            $query->whereIn('members.id', $members)
                ->whereNull('availability');
        })
        ->whereBetween('date', [now(), now()->addDays(5)])
        ->get();

        if ($games->isNotEmpty()) {

            $game = $games->first();

            $this->dispatch('notify', [
                'title' => '🏀 Match bientôt',
                'body' => 'Tu as des disponibilités à renseigner'
            ]);
            
            if (session()->has('notified')) return;
            session()->put('notified', true);

        }
    }

    public function installPwa() {
        $this->dispatch('triggerInstall');
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
        //Recherche le prochain match à afficher
        $now = \Carbon\Carbon::now();
        foreach ($this->members as &$member) {
            $member->nextGameId = null;
            foreach ($member->games as $game) {
                if ($game->date >= $now) {
                    $member->nextGameId = $game->id;
                    break;
                }
            }
        }


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
