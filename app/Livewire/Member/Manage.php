<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Member;

class Manage extends Component
{
    public $members;

    public $name;
    public $type = 'player';
    public $birthdate;
    public $prenom;
    public $licence;
    public $editingId = null;

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        $this->members = Member::latest()->get();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2',
            'type' => 'required|in:player,coach,staff',
        ]);

        if ($this->editingId) {

            $member = Member::findOrFail($this->editingId);

            $member->update([
                'name' => $this->name,
                'type' => $this->type,
                'birthdate' => $this->birthdate,
                'prenom' => $this->prenom,
                'licence' => $this->licence
            ]);

        } else {

            Member::create([
                'name' => $this->name,
                'type' => $this->type,
                'birthdate' => $this->birthdate,
                'prenom' => $this->prenom,
                'licence' => $this->licence
            ]);
        }

        $this->resetForm();
        $this->loadMembers();
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);

        $this->editingId = $member->id;
        $this->name = $member->name;
        $this->type = $member->type;
        $this->birthdate = $member->birthdate;
        $this->prenom = $member->prenom;
        $this->licence = $member->licence;
    }

    public function delete($id)
    {
        Member::findOrFail($id)->delete();
        $this->loadMembers();
    }

    public function resetForm()
    {
        $this->reset(['name', 'type', 'birthdate', 'editingId','prenom','licence']);
        $this->type = 'player';
    }

    public function render()
    {
        return view('livewire.member.manage')
            ->layout('layouts.app');
    }
}