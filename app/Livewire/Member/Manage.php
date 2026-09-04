<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Member;

class Manage extends Component
{
    public array $members = [];
    public ?int $editingIndex = null;

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        $this->members = Member::query()
            ->orderBy('prenom')
            ->orderBy('name')
            ->get()
            ->map(fn (Member $member): array => [
                'id' => $member->id,
                'name' => $member->name,
                'prenom' => $member->prenom,
                'type' => $member->type,
                'birthdate' => $member->birthdate,
                'licence' => $member->licence,
                'numero' => $member->numero,
            ])
            ->all();
    }

    public function saveMember(int $index): void
    {
        $this->validate([
            "members.$index.name" => ['required', 'string', 'min:2'],
            "members.$index.prenom" => ['nullable', 'string'],
            "members.$index.type" => ['required', 'in:player,coach,staff'],
            "members.$index.birthdate" => ['nullable', 'date'],
            "members.$index.licence" => ['nullable', 'string'],
            "members.$index.numero" => ['nullable', 'string'],
        ]);

        $member = $this->members[$index];
        Member::findOrFail($member['id'])->update([
            'name' => $member['name'],
            'prenom' => $member['prenom'],
            'type' => $member['type'],
            'birthdate' => $member['birthdate'],
            'licence' => $member['licence'],
            'numero' => $member['numero'],
        ]);

        session()->flash('success', 'Membre modifié.');
        $this->editingIndex = null;
    }

    public function editMember(int $index): void
    {
        $this->editingIndex = $index;
    }

    public function cancelEdit(): void
    {
        $this->loadMembers();
        $this->editingIndex = null;
    }

    public function delete($id)
    {
        Member::findOrFail($id)->delete();
        $this->loadMembers();
        $this->editingIndex = null;
    }

    public function render()
    {
        return view('livewire.member.manage')
            ->layout('layouts.app');
    }
}