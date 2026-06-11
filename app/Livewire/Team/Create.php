<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Create extends Component
{
    public $name;

    public function create()
    {
        $this->validate([
            'name' => 'required|min:3'
        ]);

        if (!auth()->user()->isCoach()) {
            abort(403);
        }

        Team::create([
            'name' => $this->name,
            'owner_id' => auth()->id()
        ]);


        // récupérer le member du coach
        $member = auth()->user()->members()->first();

        $team->members()->attach($member->id, [
            'role' => 'coach'
        ]);

        session()->flash('success', 'Équipe créée');

        $this->reset('name');
    }

    public function render()
    {
        return view('livewire.team.create')
            ->layout('layouts.app');
    }
}
