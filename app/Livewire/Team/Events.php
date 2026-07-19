<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\Team;

class Events extends Component
{
    public Team $team;
    public $events;
    public $nextEventId;

    public function mount()
    {
        $now = \Carbon\Carbon::now();
        $this->nextEventId = null;
        foreach ($this->team->events as $event) {
            if ($event->date >= $now) {
                $this->nextEventId = $event->id;
                break;
            }
        }

        $this->events = $this->team->events()
            ->orderBy('date')
            ->get();
    }



    public function render()
    {
        return view('livewire.team.events');
    }
}
