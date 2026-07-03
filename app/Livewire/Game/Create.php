<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Models\Game;
use App\Models\Team;

class Create extends Component
{
    public Team $team;

    public $date;
    public $titre;
    public $location;
    public $rendezvous;

    public function save()
    {
        $this->validate([
            'date' => "required",
            'location' => 'required',
            'titre' => 'required',
            'rendezvous' => 'required']);

        $game = Game::create([
            'team_id' => $this->team->id,
            'date' => $this->date,
            'location' => $this->location,
            'titre' => $this->titre,
            'rendezvous' => $this->rendezvous
        ]);

        // initialiser tous les joueurs
        foreach ($this->team->players as $player) {
            $game->members()->attach($player->id);
        }

        return redirect(route('game-admin.show',[$game->id]));
    }

    public function render()
    {
        return view('livewire.game.create')
            ->layout('layouts.app');
    }
}