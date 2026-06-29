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
    public $firstname;
    public $email;
    public $password;
    public $role;

    public $selectedMembers = [];
    public $editingId = null;

    public $link = "";

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->users = User::with('members')->with('invitations')->get();
        $this->members = Member::select()->orderBy('prenom')->orderby('name')->get();
        $this->link = "";
    }

    public function save()
    {
        $this->validate([
            'firstname' => 'required|string|min:2',
            'name' => 'required|string|min:2',
            'role' => 'required',
        ]);

        $validRelations = [\App\Enums\MemberRelation::PARENT, \App\Enums\MemberRelation::SELF, \App\Enums\MemberRelation::COACH];

        foreach ($this->selectedMembers as $relation) {
            if ($relation && !in_array($relation, $validRelations)) {
                abort(400);
            }
        }

        if ($this->editingId) {

            $this->validate([
                'email' => 'required|email',
            ]);
            $user = User::findOrFail($this->editingId);

            $user->update([
                'name' => $this->name,
                'firstname' => $this->firstname,
                'email' => $this->email,
                'role' => $this->role,
            ]);

        } else {

            $this->validate([
                'password' => 'required|min:6',
                'email' => 'required|email|unique:users,email',
            ]);

            $user = User::create([
                'name' => $this->name,
                'firstname' => $this->firstname,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);
        }

        $this->sync_members($user);
        $this->resetForm();
        $this->loadData();

    }
    private function sync_members($user) {
        //Sync members
        $sync = [];

        foreach ($this->selectedMembers as $memberId => $relation) {

            if (!$relation) continue;

            $sync[$memberId] = [
                'relation' => $relation
            ];
        }

        $user->members()->sync($sync);


    }

    public function edit($id)
    {
        $user = User::with(['members' => function($query) {
            $query->orderBy('prenom')->orderby('name');
        }])->findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->firstname = $user->firstname;
        $this->email = $user->email;
        $this->role = $user->role;


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

    public function invit()
    {
        $this->validate([
            'name' => 'required',
            'role' => 'required'
        ]);

        $validRelations = [\App\Enums\MemberRelation::PARENT, \App\Enums\MemberRelation::SELF, \App\Enums\MemberRelation::COACH];

        foreach ($this->selectedMembers as $relation) {
            if ($relation && !in_array($relation, $validRelations)) {
                abort(400);
            }
        }

        $token = \Illuminate\Support\Str::uuid();

        //On cree l'utilisateur avec juste un nom
        $user = User::create([
            'name' => $this->name,
            'firstname' => $this->firstname,
            'email' => '',
            'password' => Hash::make(uniqid()), //cree un mdp aléatoire
            'role' => $this->role,
        ]);

        //On génère l'invitation
        \App\Models\Invitation::create([
            'email' => $this->email,
            'token' => $token,
            'created_by' => auth()->id(),
            'user_id' => $user->id,
            'expires_at' => now()->addDays(7),
        ]);

        $this->sync_members($user);

        $this->resetForm();
        $this->loadData();

        $this->link = url('/invitation/' . $token);

    }

    public function resetForm()
    {
        $this->reset(['name', 'firstname','email', 'password', 'role', 'selectedMembers', 'editingId']);
    }

    public function render()
    {
        return view('livewire.user.manage')
            ->layout('layouts.app');
    }
}