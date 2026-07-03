<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Member;


class Profile extends Component
{
    
    public $member;

    public $prenom;
    public $name;
    public $licence;
    public $birthdate;
    public $games;
    public $trainings;

    public function mount($member)
    {
        $this->loadData($member);

    }

    private function loadData($member) {
        $this->member = Member::with(['games' => function ($query) {
            $query->wherePivot('selected', 1)->orderBy('date');
        }])
        ->with(['trainings' => function ($query) {
            $query->wherePivot('present', 'yes')->orderBy('date');
        }])
        ->find($member);
    
        $this->prenom = $this->member->prenom;
        $this->name = $this->member->name;
        $this->licence = $this->member->licence;
        $this->birthdate = $this->member->birthdate;
       
    }

    public function save() {

        $this->validate([
            'prenom' => 'required|string|min:2',
            'name' => 'required|string|min:2',
        ]);

        $this->member->update([
            'name' => $this->name,
            'prenom' => $this->prenom,
            'licence' => $this->licence,
            'birthdate' => $this->birthdate
        ]);

        $this->loadData($this->member->id);

    }

    public function render()
    {
        return view('livewire.member.profile')->layout('layouts.app');;
    }
}
