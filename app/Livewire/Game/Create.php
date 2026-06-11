<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Models\Game;
use App\Models\Team;

class Create extends Component
{
    public Team $team;

    public $date;
    public $location;
    public $horaire;
    public $rendezvous;

    public function save()
    {
        $game = Game::create([
            'team_id' => $this->team->id,
            'date' => $this->date,
            'location' => $this->location,
            'horaire' => $this->horaire,
            'rendezvous' => $this->rendezvous
        ]);

        // initialiser tous les joueurs
        foreach ($this->team->players as $player) {
            $game->members()->attach($player->id);
        }

        return redirect('/games/' . $game->id);
    }

    public function render()
    {
        return view('livewire.game.create')
            ->layout('layouts.app');
    }
}