<?php

namespace Tests\Feature;

use App\Livewire\Team\Create;
use App\Livewire\Team\Events;
use App\Livewire\Team\Owners;
use App\Livewire\Team\Selections;
use App\Models\Event;
use App\Models\Game;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamAdditionalComponentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_can_create_a_team_and_becomes_its_owner(): void
    {
        $coach = User::factory()->create(['role' => User::ROLE_COACH]);
        $this->actingAs($coach);

        Livewire::test(Create::class)
            ->set('name', 'Equipe Espoirs')
            ->set('whatsapp', 'https://wa.me/123456')
            ->set('msg_convocation', 'Rendez-vous avant le match')
            ->call('create')
            ->assertHasNoErrors()
            ->assertSet('name', null);

        $team = Team::where('name', 'Equipe Espoirs')->firstOrFail();

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'whatsapp' => 'https://wa.me/123456',
            'msg_convocation' => 'Rendez-vous avant le match',
            'owner_id' => $coach->id,
        ]);
        $this->assertDatabaseHas('team_owner', [
            'team_id' => $team->id,
            'user_id' => $coach->id,
        ]);
    }

    public function test_team_creation_requires_a_name_of_at_least_three_characters(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_COACH]));

        Livewire::test(Create::class)
            ->set('name', 'AB')
            ->call('create')
            ->assertHasErrors(['name' => 'min']);

        $this->assertDatabaseCount('teams', 0);
    }

    public function test_events_lists_events_and_identifies_the_next_event(): void
    {
        $team = Team::factory()->create();
        Event::create([
            'team_id' => $team->id,
            'titre' => 'Événement passé',
            'date' => now()->subDay(),
        ]);
        $nextEvent = Event::create([
            'team_id' => $team->id,
            'titre' => 'Prochain événement',
            'date' => now()->addDay(),
        ]);
        Event::create([
            'team_id' => $team->id,
            'titre' => 'Événement suivant',
            'date' => now()->addDays(2),
        ]);

        Livewire::test(Events::class, ['team' => $team])
            ->assertSet('nextEventId', $nextEvent->id)
            ->assertSee('Événement passé')
            ->assertSee('Prochain événement')
            ->assertSee('Événement suivant');
    }

    public function test_owner_can_search_add_and_remove_another_owner(): void
    {
        $owner = User::factory()->create(['firstname' => 'Current']);
        $newOwner = User::factory()->create([
            'firstname' => 'Taylor',
            'name' => 'Example',
        ]);
        $team = Team::factory()->create();
        $team->owners()->attach($owner->id);
        $this->actingAs($owner);

        Livewire::test(Owners::class, ['team' => $team])
            ->set('search', 'Taylor')
            ->assertSee('Taylor (Example)')
            ->call('addMember', $newOwner->id)
            ->assertSee('Taylor')
            ->call('removeMember', $newOwner->id);

        $this->assertDatabaseMissing('team_owner', [
            'team_id' => $team->id,
            'user_id' => $newOwner->id,
        ]);
        $this->assertDatabaseHas('team_owner', [
            'team_id' => $team->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_non_owner_cannot_add_an_owner(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Owners::class, ['team' => $team])
            ->call('addMember', $user->id)
            ->assertForbidden();

        $this->assertDatabaseMissing('team_owner', [
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_selections_toggle_player_selection_for_a_game(): void
    {
        $team = Team::factory()->create();
        $game = Game::factory()->create([
            'team_id' => $team->id,
            'date' => now()->addDay(),
        ]);
        $player = Member::factory()->create(['type' => Member::TYPE_PLAYER]);
        $team->members()->attach($player->id);
        $game->members()->attach($player->id, [
            'availability' => 'yes',
            'selected' => false,
        ]);

        Livewire::test(Selections::class, ['team' => $team])
            ->call('toggleSelection', $game->id, $player->id);

        $this->assertDatabaseHas('game_member', [
            'game_id' => $game->id,
            'member_id' => $player->id,
            'selected' => true,
        ]);

        Livewire::test(Selections::class, ['team' => $team])
            ->call('toggleSelection', $game->id, $player->id);

        $this->assertDatabaseHas('game_member', [
            'game_id' => $game->id,
            'member_id' => $player->id,
            'selected' => false,
        ]);
    }
}