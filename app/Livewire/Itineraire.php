<?php

namespace App\Livewire;

use Livewire\Component;

class Itineraire extends Component
{

    public float $lat;
    public float $lng;
    public string $label = '';

    // Menu ouvert/fermé pour le choix iOS
    public bool $showMenu = false;

    public function mount(float $lat, float $lng, string $label = '')
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->label = $label;
    }

    public function getGeoUrlProperty(): string
    {
        $labelEncoded = urlencode($this->label);
        return "geo:{$this->lat},{$this->lng}?q={$this->lat},{$this->lng}({$labelEncoded})";
    }

    public function getGoogleUrlProperty(): string
    {
        return "https://www.google.com/maps/dir/?api=1&destination={$this->lat},{$this->lng}";
    }

    public function getWazeUrlProperty(): string
    {
        return "https://waze.com/ul?ll={$this->lat},{$this->lng}&navigate=yes";
    }

    public function getAppleUrlProperty(): string
    {
        return "https://maps.apple.com/?daddr={$this->lat},{$this->lng}";
    }

    public function toggleMenu(): void
    {
        $this->showMenu = !$this->showMenu;
    }


    public function render()
    {
        return view('livewire.itineraire');
    }
}
