<?php

namespace App\Livewire\Gymnase;

use Livewire\Component;
use App\Models\Place;

class Show extends Component
{
    public $places;
    public ?Place $editingPlace = null;

    public function mount(): void
    {
        $this->loadData();
    }

    private function loadData(): void
    {
        $this->places = Place::orderBy('name')->get();
    }

    public function create(): void
    {
        $this->editingPlace = null;
    }

    public function edit(int $id): void
    {
        $this->editingPlace = Place::findOrFail($id);
    }

    public function delete(int $id): void
    {
        Place::findOrFail($id)->delete();
        $this->loadData();
    }

    public function refreshPlaces(): void
    {
        $this->loadData();
        $this->editingPlace = null;
    }

    protected $listeners = ['gymnase-saved' => 'refreshPlaces'];

    public function render()
    {
        return view('livewire.gymnase.show')->layout('layouts.app');
    }
}
