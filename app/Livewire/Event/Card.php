<?php

namespace App\Livewire\Event;

use Livewire\Component;
use App\Models\Event;
use App\Models\Member;

class Card extends Component
{
    public Event $event;
    public Member $member;
    public $availability;

    public function mount()
    {
        // Charge la valeur initiale du pivot
        $this->availability = $this->event->members()->where('member_id', $this->member->id)->first()?->pivot?->availability;
    }

    public function setAvailability($value)
    {

        $this->event->members()->updateExistingPivot($this->member->id, [
            'availability' => $value,
        ]);

        $this->availability = $value;
    }

    public function render()
    {
        return view('livewire.event.card');
    }
}