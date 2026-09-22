<?php

namespace Tests\Feature;

use App\Livewire\Game\Card;
use App\Models\Game;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GameCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_sets_availability_and_selection_for_completed_week(): void
    {
        [$user, $member, $game] = $this->createGameContext(
            date: Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->addDays(2)->setTime(18, 0),
            selected: true,
            availability: 'yes',
        );

        Livewire::actingAs($user)
            ->test(Card::class, ['game' => $game, 'member' => $member])
            ->assertSet('availability', 'yes')
            ->assertSet('isSelected', 1);

        $this->assertDatabaseHas('game_member', [
            'game_id' => $game->id,
            'member_id' => $member->id,
            'availability' => 'yes',
            'selected' => 1,
        ]);
    }

    public function test_mount_keeps_selection_unknown_when_game_week_is_not_finished(): void
    {
        [$user, $member, $game] = $this->createGameContext(
            date: Carbon::now()->addWeek()->startOfWeek(Carbon::MONDAY)->addDays(2)->setTime(18, 0),
            selected: false,
            availability: 'no',
        );

        Livewire::actingAs($user)
            ->test(Card::class, ['game' => $game, 'member' => $member])
            ->assertSet('availability', 'no')
            ->assertSet('isSelected', -1);
    }

    public function test_set_availability_updates_the_game_member_pivot(): void
    {
        [$user, $member, $game] = $this->createGameContext(
            date: Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->addDays(2)->setTime(18, 0),
            selected: false,
            availability: 'yes',
        );

        Livewire::actingAs($user)
            ->test(Card::class, ['game' => $game, 'member' => $member])
            ->call('setAvailability', $member->id, $game->id, 'no')
            ->assertSet('availability', 'no');

        $this->assertDatabaseHas('game_member', [
            'game_id' => $game->id,
            'member_id' => $member->id,
            'availability' => 'no',
        ]);
    }

    private function createGameContext(
        Carbon $date,
        bool $selected,
        string $availability,
    ): array {
        $user = User::factory()->create(['role' => User::ROLE_COACH]);
        $team = Team::factory()->create();
        $member = Member::factory()->create();
        $game = Game::factory()->create([
            'team_id' => $team->id,
            'date' => $date->format('Y-m-d H:i:s'),
        ]);

        $game->members()->attach($member->id, [
            'availability' => $availability,
            'selected' => $selected,
        ]);

        return [$user, $member, $game];
    }
}
