<?php

namespace Tests\Feature;

use App\Livewire\Gymnase\Form;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GymnaseShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_editing_a_place_passes_it_to_the_form_component(): void
    {
        $place = Place::create([
            'name' => 'Complexe Sportif Test',
            'address' => '1 rue de la Paix',
            'lat' => 48.8566,
            'lng' => 2.3522,
        ]);

        Livewire::test(Form::class, ['place' => $place])
            ->assertSet('place', $place)
            ->assertSet('name', 'Complexe Sportif Test')
            ->assertSet('address', '1 rue de la Paix')
            ->assertSet('lat', 48.8566)
            ->assertSet('lng', 2.3522);
    }
}
