<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;
use App\Models\User;

class Owners extends Component
{
    public Team $team;
    public $members;
    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        $this->results = User::where('firstname', 'like', "%{$this->search}%")
            ->limit(10)
            ->get();
    }

    public function addMember($memberId)
    {
        if (!$this->team->owners()->whereKey(auth()->id())->exists()) {
            abort(403);
        }

        $this->team->owners()->syncWithoutDetaching([
            $memberId
        ]);
    }

    public function removeMember($memberId)
    {
        $this->team->owner()->detach($memberId);
    }

    public function render()
    {
        $this->members = $this->team->owners;
        return view('livewire.team.owners')->layout('layouts.app');;
    }
}
