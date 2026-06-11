<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;
use App\Models\Member;

class ManageMembers extends Component
{
    public Team $team;

    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        $this->results = Member::where('prenom', 'like', "%{$this->search}%")
            ->limit(10)
            ->get();
    }

    public function addMember($memberId, $role = 'player')
    {
        if ($this->team->owner_id !== auth()->id()) {
            abort(403);
        }

        $this->team->members()->syncWithoutDetaching([
            $memberId => ['role' => $role]
        ]);
    }

    public function removeMember($memberId)
    {
        $this->team->members()->detach($memberId);
    }

    public function render()
    {
        return view('livewire.team.manage-members', [
            'members' => $this->team->members
        ])->layout('layouts.app');
    }
}