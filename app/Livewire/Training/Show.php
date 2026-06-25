<?php

namespace App\Livewire\Training;

use Livewire\Component;
use App\Models\Training;

class Show extends Component
{
    public Training $training;

    public bool $editingTraining = false;

    public string $trainingTitle = '';
    public string $trainingDate = '';
    public string $trainingLocation = '';
    public $presence = [];
    public $members;

    public function mount()
    {
        $this->trainingTitle = $this->training->titre===null?'':$this->training->titre;
        $this->trainingDate = \Carbon\Carbon::parse($this->training->date)->format("Y-m-d");
        $this->trainingLocation = $this->training->location ?? '';

        $this->members = $this->training->team->members()->where("type",\App\Models\Member::TYPE_PLAYER)->get();
        $this->presence = $this->training->members
                                ->mapWithKeys(fn ($member) => [
                                    $member->id => $member->pivot->present
                                ])
                                ->toArray();
    }

    public function toggleEditingTraining()
    {
        $this->editingTraining = !$this->editingTraining;
        if ($this->editingTraining) {
            $this->trainingTitle = $this->training->titre===null?'':$this->training->titre;
            $this->trainingDate = \Carbon\Carbon::parse($this->training->date)->format("Y-m-d");
            $this->trainingLocation = $this->training->location??'';
        }
    }
    
    public function updateTraining()
    {
        $this->training->update([
            'titre' => $this->trainingTitle,
            'date' => $this->trainingDate,
            'location' => $this->trainingLocation,
        ]);
        $this->editingTraining = false;
    }

    public function setPresence($memberId,$value)
    {

        if (($this->presence[$memberId] ?? null) === $value) {
            return; // rien à faire
        }

        $this->presence[$memberId] = $value;

        $this->training->members()->syncWithoutDetaching([
            $memberId => ['present' => $value]
        ]);

    }

    public function render()
    {
        return view('livewire.training.show', [
            'members' => $this->members,
            'presence' => $this->presence
        ])->layout('layouts.app');
    }
}
