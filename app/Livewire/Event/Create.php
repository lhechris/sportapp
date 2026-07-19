<?php

namespace App\Livewire\Event;

use Livewire\Component;
use App\Models\Event;
use App\Models\Team;

class Create extends Component
{
    public Team $team;

    public $date;
    public $titre;
    public $location;
    public $description;

    public function save()
    {
        $this->validate([
            'date' => "required",
            'location' => 'required',
            'titre' => 'required']);

        $event = Event::create([
            'team_id' => $this->team->id,
            'date' => $this->date,
            'location' => $this->location,
            'titre' => $this->titre,
            'description' => $this->description
        ]);

        // initialiser tous les joueurs
        foreach ($this->team->players as $player) {
            $event->members()->attach($player->id);
        }

        return redirect(route('event.edit',[$event->id]));
    }

    public function render()
    {
        return view('livewire.event.create')
            ->layout('layouts.app');
    }
}
