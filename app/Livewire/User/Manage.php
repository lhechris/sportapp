<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

class Manage extends Component
{
    public $users;
    public $members;

    public $name;
    public $email;
    public $password;

    public $selectedMembers = [];
    public $editingId = null;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->users = User::with('members')->get();
        $this->members = Member::all();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);


        $validRelations = [\App\Enums\MemberRelation::PARENT, \App\Enums\MemberRelation::SELF, \App\Enums\MemberRelation::COACH];

        foreach ($this->selectedMembers as $relation) {
            if ($relation && !in_array($relation, $validRelations)) {
                abort(400);
            }
        }


        if ($this->editingId) {

            $user = User::findOrFail($this->editingId);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

        } else {

            $this->validate([
                'password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
        }

        //Sync members
        $sync = [];

        foreach ($this->selectedMembers as $memberId => $relation) {

            if (!$relation) continue;

            $sync[$memberId] = [
                'relation' => $relation
            ];
        }

        $user->members()->sync($sync);

        $this->resetForm();
        $this->loadData();
    }

    public function edit($id)
    {
        $user = User::with('members')->findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;


        $this->selectedMembers = $user->members
            ->mapWithKeys(function ($member) {
                return [
                    $member->id => $member->pivot->relation
                ];
            })
            ->toArray();
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        $this->loadData();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'selectedMembers', 'editingId']);
    }

    public function render()
    {
        return view('livewire.user.manage')
            ->layout('layouts.app');
    }
}