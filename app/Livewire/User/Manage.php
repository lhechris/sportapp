<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use App\Notifications\userNotification;
use Illuminate\Support\Str;
use App\Models\Invitation;

class Manage extends Component
{
    public array $users = [];
    public $members;
    public array $newUser = [
        'name' => '',
        'firstname' => '',
        'email' => '',
        'password' => '',
        'role' => 'player',
        'selectedMembers' => [],
    ];
    public bool $creating = false;
    public ?string $link = null;
    public ?int $editingIndex = null;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->users = User::with('members')->with('invitations')->get()
            ->map(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'firstname' => $user->firstname,
                'email' => $user->email,
                'password' => '',
                'role' => $user->role,
                'selectedMembers' => $user->members
                    ->mapWithKeys(fn (Member $member): array => [$member->id => $member->pivot->relation])
                    ->toArray(),
                'memberNames' => $user->members->pluck('prenom')->join(', '),
                'invitations' => $user->invitations->pluck('token')->join(', '),
            ])
            ->all();
        $this->members = Member::select()->orderBy('prenom')->orderby('name')->get();
        if (!$this->newUser['selectedMembers']) {
            $this->newUser['selectedMembers'] = $this->members->mapWithKeys(fn (Member $member): array => [$member->id => ''])->all();
        }
    }

    public function startCreate(): void
    {
        $this->resetValidation();
        $this->editingIndex = null;
        $this->creating = true;
    }

    public function cancelCreate(): void
    {
        $this->resetValidation();
        $this->newUser = [
            'name' => '',
            'firstname' => '',
            'email' => '',
            'password' => '',
            'role' => 'player',
            'selectedMembers' => $this->members->mapWithKeys(fn (Member $member): array => [$member->id => ''])->all(),
        ];
        $this->creating = false;
    }

    public function createUser(): void
    {
        $this->validate([
            'newUser.firstname' => ['required', 'string', 'min:2'],
            'newUser.name' => ['required', 'string', 'min:2'],
            'newUser.email' => ['required', 'email', 'unique:users,email'],
            'newUser.password' => ['required', 'string', 'min:6'],
            'newUser.role' => ['required', 'in:player,parent,coach'],
            'newUser.selectedMembers' => ['array'],
        ]);

        $validRelations = [\App\Enums\MemberRelation::PARENT, \App\Enums\MemberRelation::SELF, \App\Enums\MemberRelation::COACH];
        foreach ($this->newUser['selectedMembers'] as $relation) {
            if ($relation && !in_array($relation, $validRelations, true)) {
                abort(400);
            }
        }

        $user = User::create([
            'name' => $this->newUser['name'],
            'firstname' => $this->newUser['firstname'],
            'email' => $this->newUser['email'],
            'password' => Hash::make($this->newUser['password']),
            'role' => $this->newUser['role'],
        ]);
        $this->sync_members($user, $this->newUser['selectedMembers']);
        $this->loadData();
        $this->cancelCreate();
        session()->flash('success', 'Utilisateur créé.');
    }

    public function saveUser(int $index): void
    {
        $this->validate([
            "users.$index.firstname" => ['required', 'string', 'min:2'],
            "users.$index.name" => ['required', 'string', 'min:2'],
            "users.$index.email" => ['required', 'email'],
            "users.$index.role" => ['required', 'in:player,parent,coach'],
            "users.$index.password" => ['nullable', 'string', 'min:6'],
        ]);

        $validRelations = [\App\Enums\MemberRelation::PARENT, \App\Enums\MemberRelation::SELF, \App\Enums\MemberRelation::COACH];

        foreach ($this->users[$index]['selectedMembers'] as $relation) {
            if ($relation && !in_array($relation, $validRelations)) {
                abort(400);
            }
        }

        $data = [
            'name' => $this->users[$index]['name'],
            'firstname' => $this->users[$index]['firstname'],
            'email' => $this->users[$index]['email'],
            'role' => $this->users[$index]['role'],
        ];

        if ($this->users[$index]['password']) {
            $data['password'] = Hash::make($this->users[$index]['password']);
        }

        $user = User::findOrFail($this->users[$index]['id']);
        $user->update($data);
        $this->sync_members($user, $this->users[$index]['selectedMembers']);
        $this->editingIndex = null;
        session()->flash('success', 'Utilisateur modifié.');
        $this->loadData();
    }

    private function sync_members(User $user, array $selectedMembers): void
    {
        //Sync members
        $sync = [];

        foreach ($selectedMembers as $memberId => $relation) {

            if (!$relation) continue;

            $sync[$memberId] = [
                'relation' => $relation
            ];
        }

        $user->members()->sync($sync);
    }


    public function invit(): void
    {
        $this->validate([
            'newUser.firstname' => ['required', 'string', 'min:2'],
            'newUser.name' => ['required', 'string', 'min:2'],
            'newUser.email' => ['required', 'email'],
            'newUser.role' => ['required', 'in:player,parent,coach'],
            'newUser.selectedMembers' => ['array'],
        ]);

        $token = Str::uuid();
        $user = User::create([
            'name' => $this->newUser['name'],
            'firstname' => $this->newUser['firstname'],
            'email' => Str::slug($this->newUser['firstname'].'.'.$this->newUser['name']).'+'.Str::lower(Str::random(8)).'@example.com',
            'password' => Hash::make(Str::random(40)),
            'role' => $this->newUser['role'],
        ]);

        Invitation::create([
            'email' => $this->newUser['email'],
            'token' => $token,
            'created_by' => auth()->id(),
            'user_id' => $user->id,
            'expires_at' => now()->addDays(7),
        ]);

        $this->sync_members($user, $this->newUser['selectedMembers']);

        $this->loadData();
        $this->newUser['password'] = '';
        $this->creating = false;
        $this->link = url('/invitation/'.$token);

    }

    public function editUser(int $index): void
    {
        $this->creating = false;
        $this->editingIndex = $index;
    }

    public function cancelEdit(): void
    {
        $this->loadData();
        $this->editingIndex = null;
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        $this->loadData();
        $this->editingIndex = null;
    }

     public function sendNotification($userId)
    {
         $user = User::findOrFail($userId);

         \Log::info('sendNotification', [
              'user_id' => $user->id,
              'push_subscriptions' => $user->pushSubscriptions()->count(),
         ]);

         $user->notify(new userNotification());
    }


    public function render()
    {
        return view('livewire.user.manage')
            ->layout('layouts.app');
    }
}