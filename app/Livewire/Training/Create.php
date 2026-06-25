<?php

namespace App\Livewire\Training;

use Livewire\Component;
use App\Models\Team;
use App\Models\Training;

class Create extends Component
{
    public Team $team;

    public $date;
    public $titre;
    public $location;

    public function mount() {
        $this->date = \Carbon\Carbon::now()->format("Y-m-d");
        $this->titre = "Entrainement";        
    }

    public function save()
    {
        $training = Training::create([
            'team_id' => $this->team->id,
            'date' => $this->date,
            'location' => $this->location,
            'titre' => $this->titre,
        ]);

        // initialiser tous les joueurs
        foreach ($this->team->players as $player) {
            $training->members()->attach($player->id);
        }

        return redirect()->route('training.show',['training' => $training->id]);
    }

    public function render()
    {
        return view('livewire.training.create')
            ->layout('layouts.app');
    }
}
