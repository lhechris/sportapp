<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Create extends Component
{
    public $name;
    public $whatsapp;
    public $msg_convocation="Bonjour pour le match du %JOURMATCH% voici la liste des joueurs sélectionnées %SELECTIONS%";

    public function create()
    {
        $this->validate([
            'name' => 'required|min:3'
        ]);

        if (!auth()->user()->isCoach()) {
            abort(403);
        }

        $team = Team::create([
            'name' => $this->name,
            'whatsapp' => $this->whatsapp,
            'msg_convocation' => $this->msg_convocation
        ]);

        $team->owners()->attach(auth()->id());

        // récupérer le member du coach
        $member = auth()->user()->members()->first();

        session()->flash('success', 'Équipe créée');

        $this->reset('name');
    }

    public function render()
    {
        return view('livewire.team.create')
            ->layout('layouts.app');
    }
}
