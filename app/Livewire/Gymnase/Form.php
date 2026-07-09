<?php

namespace App\Livewire\Gymnase;

use App\Models\Place;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Form extends Component
{
    public ?Place $place = null;

    public string $name = '';
    public string $address = '';
    public ?float $lat = null;
    public ?float $lng = null;

    public bool $isGeocoding = false;
    public bool $geocodeSuccess = false;
    public ?string $geocodeError = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ];
    }

    public function mount(?Place $place = null): void
    {
        $this->syncFromPlace($place);
    }

    public function updatedPlace(?Place $place): void
    {
        $this->syncFromPlace($place);
    }

    private function syncFromPlace(?Place $place): void
    {
        if (! $place || ! $place->exists) {
            $this->place = null;
            $this->name = '';
            $this->address = '';
            $this->lat = null;
            $this->lng = null;
            return;
        }

        $this->place = $place;
        $this->name = $place->name;
        $this->address = $place->address;
        $this->lat = $place->lat;
        $this->lng = $place->lng;
    }

    public function geocode(): void
    {
        $this->geocodeError = null;
        $this->geocodeSuccess = false;

        $this->validateOnly('address', [
            'address' => 'required|string|max:255',
        ]);

        $this->isGeocoding = true;

        try {
            $response = Http::withHeaders([
                // Nominatim exige un User-Agent identifiable (usage policy)
                'User-Agent' => 'ASLabartheBasket/1.0 (lhechris@gmail.com)',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $this->address,
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 0,
            ]);

            if (! $response->successful()) {
                $this->geocodeError = "Erreur lors de la requête de géocodage.";
                return;
            }

            $results = $response->json();

            if (empty($results)) {
                $this->geocodeError = "Aucun résultat trouvé pour cette adresse.";
                return;
            }

            $this->lat = (float) $results[0]['lat'];
            $this->lng = (float) $results[0]['lon'];
            $this->geocodeSuccess = true;
        } catch (\Exception $e) {
            $this->geocodeError = "Impossible de contacter le service de géocodage.".$e;
        } finally {
            $this->isGeocoding = false;
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];

        if ($this->place) {
            $this->place->update($data);
        } else {
            $this->place = Place::create($data);
        }

        session()->flash('success', 'Gymnase enregistré avec succès.');

        $this->dispatch('gymnase-saved', gymnaseId: $this->place->id);
    }

    public function render()
    {
        return view('livewire.gymnase.form');
    }
}