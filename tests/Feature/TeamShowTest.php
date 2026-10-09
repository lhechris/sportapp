<?php

namespace Tests\Feature;

use App\Livewire\Team\Show;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_show_defaults_to_the_games_tab(): void
    {
        $team = Team::factory()->create(['name' => 'Équipe Test']);

        Livewire::test(Show::class, ['team' => $team])
            ->assertSet('activeTab', 'games')
            ->assertSee('Équipe Test');
    }

    public function test_team_show_can_switch_tabs(): void
    {
        $team = Team::factory()->create();

        Livewire::test(Show::class, ['team' => $team])
            ->call('setTab', 'members')
            ->assertSet('activeTab', 'members')
            ->call('setTab', 'trainings')
            ->assertSet('activeTab', 'trainings')
            ->call('setTab', 'parameters')
            ->assertSet('activeTab', 'parameters');
    }
}
