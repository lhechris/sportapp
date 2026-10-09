<?php

namespace App\Livewire\Event;

use Livewire\Component;
use App\Models\Event;

class Show extends Component
{
    public Event $event;
    public $members;
    public $menberIds;
    public $players;

    public function mount() {
        $this->loaddata();
    }

    public function setAvailability($memberId,$value)
    {
        abort_unless(auth()->user()?->members()->whereKey($memberId)->exists(), 403);
        abort_unless($this->event->members()->whereKey($memberId)->exists(), 403);

        validator(['value' => $value], [
            'value' => ['required', 'in:yes,no,maybe'],
        ])->validate();

        $this->event->members()->updateExistingPivot($memberId, [
            'availability' => $value,
        ]);
        $this->loaddata();

    }
 
    private function loaddata() {
        $this->memberIds = auth()->user()->members()->pluck('members.id');
        $this->members = $this->event->members()
                ->whereIn('members.id', $this->memberIds)
                ->get();

        $this->players = $this->event->members()->get();
    }

    public function render()
    {
        return view('livewire.event.show')->layout('layouts.app');
    }
}
