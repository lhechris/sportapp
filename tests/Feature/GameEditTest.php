<?php

namespace Tests\Feature;

use App\Livewire\Game\Edit;
use App\Models\Game;
use App\Models\GameMemberOption;
use App\Models\GameOption;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use Illuminate\Support\Facades\DB;

class GameEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_initializes_an_empty_number_option(): void
    {
        [$team, $game, $member, $option] = $this->createGameWithNumberOption();

        GameMemberOption::create([
            'game_id' => $game->id,
            'member_id' => $member->id,
            'game_option_id' => $option->id,
            'value' => null,
        ]);

        Livewire::test(Edit::class, ['game' => $game]);

        $this->assertDatabaseHas('game_member_option', [
            'game_id' => $game->id,
            'member_id' => $member->id,
            'game_option_id' => $option->id,
            'value' => (string) $member->numero,
        ]);
    }

    public function test_mount_initializes_without_number_option(): void
    {
       /*DB::listen(function ($query) {        
        \Log::debug($query->sql);
        \Log::debug($query->bindings);
        });*/

        [$team, $game, $member, $option] = $this->createGameWithNumberOption();
      
        Livewire::test(Edit::class, ['game' => $game]);

        $this->assertDatabaseHas('game_member_option', [
            'game_id' => $game->id,
            'member_id' => $member->id,
            'game_option_id' => $option->id,
            'value' => (string) $member->numero,
        ]);
    }

    public function test_set_game_option_updates_the_member_option(): void
    {
        [$team, $game, $member, $option] = $this->createGameWithNumberOption();

        GameMemberOption::create([
            'game_id' => $game->id,
            'member_id' => $member->id,
            'game_option_id' => $option->id,
            'value' => '7',
        ]);

        Livewire::test(Edit::class, ['game' => $game])
            ->call('setGameOption', $member->id, $option->id, '12');

        $this->assertDatabaseHas('game_member_option', [
            'game_id' => $game->id,
            'member_id' => $member->id,
            'game_option_id' => $option->id,
            'value' => '12',
        ]);

        $game2 = Game::factory()->create(['team_id' => $team->id]);
        $game2->members()->attach($member->id, [
            'selected' => true,
        ]);
        Livewire::test(Edit::class, ['game' => $game2]);
        $this->assertDatabaseHas('game_member_option', [
            'game_id' => $game2->id,
            'member_id' => $member->id,
            'game_option_id' => $option->id,
            'value' => (string) $member->numero,
        ]);

    }

    private function createGameWithNumberOption(): array
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id]);
        $member = Member::factory()->create(['numero' => 10]);
        $game = Game::factory()->create(['team_id' => $team->id]);
        
        $game->members()->attach($member->id, [
            'selected' => true,
        ]);

        $option = GameOption::create([
            'team_id' => $team->id,
            'name' => 'Numero',
            'display' => GameOption::DISP_ALL,
            'type' => GameOption::TYPE_NUM,
            'order' => 1,
        ]);

        return [$team, $game, $member, $option];
    }
}
