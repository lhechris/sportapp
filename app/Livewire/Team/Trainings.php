<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Trainings extends Component
{
    public Team $team;
    public $trainings;
    public $nextTrainingId;

    public function mount()
    {
        $now = \Carbon\Carbon::now();
        $this->nextTrainingId = null;
        foreach ($this->team->trainings as $training) {
            if ($training->date >= $now) {
                $this->nextTrainingId = $training->id;
                break;
            }
        }
        $this->trainings = $this->team->trainings;
    }

    public function render()
    {
        return view('livewire.team.trainings');
    }
}
