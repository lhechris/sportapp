<?php

namespace Tests\Feature;

use App\Livewire\Team\Games;
use App\Livewire\Team\Members;
use App\Livewire\Team\Parameters;
use App\Models\Game;
use App\Models\GameOption;
use App\Models\Member;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamSubcomponentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_lists_players_and_saves_member_edits(): void
    {
        $team = Team::factory()->create();
        $player = Member::factory()->create([
            'prenom' => 'Alice',
            'licence' => 'LIC-1',
            'numero' => 8,
        ]);
        $coach = Member::factory()->create([
            'prenom' => 'Coach',
            'type' => Member::TYPE_COACH,
        ]);
        $team->members()->attach([$player->id, $coach->id]);

        Livewire::test(Members::class, ['team' => $team])
            ->assertSee('Alice')
            ->assertSet('members', fn ($members) => $members->modelKeys() === [$player->id])
            ->call('editMember', $player->id)
            ->assertSet('editingMemberId', $player->id)
            ->set('editingMember.prenom', 'Alice mise à jour')
            ->set('editingMember.licence', 'LIC-2')
            ->set('editingMember.numero', '12')
            ->call('saveMember')
            ->assertSet('editingMemberId', null);

        $this->assertDatabaseHas('members', [
            'id' => $player->id,
            'prenom' => 'Alice mise à jour',
            'licence' => 'LIC-2',
            'numero' => '12',
        ]);
    }

    public function test_games_lists_matches_and_identifies_the_next_game(): void
    {
        $team = Team::factory()->create();
        Game::factory()->create([
            'team_id' => $team->id,
            'titre' => 'Match passé',
            'date' => now()->subDay(),
        ]);
        $nextGame = Game::factory()->create([
            'team_id' => $team->id,
            'titre' => 'Prochain match',
            'date' => now()->addDay(),
        ]);
        Game::factory()->create([
            'team_id' => $team->id,
            'titre' => 'Match suivant',
            'date' => now()->addDays(2),
        ]);

        Livewire::test(Games::class, ['team' => $team])
            ->assertSet('nextGameId', $nextGame->id)
            ->assertSee('Match passé')
            ->assertSee('Prochain match')
            ->assertSee('Match suivant');
    }

    public function test_parameters_saves_team_details_and_creates_game_options(): void
    {
        $team = Team::factory()->create();

        Livewire::test(Parameters::class, ['team' => $team])
            ->set('teamName', 'Equipe modifiée')
            ->set('teamWhatsapp', 'https://wa.me/123456')
            ->set('teamMsgConvocation', 'Rendez-vous à 18h')
            ->set('gameoptions.new-option', [
                'id' => null,
                'name' => 'Numéro',
                'type' => GameOption::TYPE_NUM,
                'order' => 1,
                'display' => GameOption::DISP_ALL,
            ])
            ->call('saveAll')
            ->assertDispatched('saved');

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'Equipe modifiée',
            'whatsapp' => 'https://wa.me/123456',
            'msg_convocation' => 'Rendez-vous à 18h',
        ]);
        $this->assertDatabaseHas('game_options', [
            'team_id' => $team->id,
            'name' => 'Numéro',
            'type' => GameOption::TYPE_NUM,
            'order' => 1,
            'display' => GameOption::DISP_ALL,
        ]);
    }

    public function test_parameters_can_add_and_delete_game_option_rows(): void
    {
        $team = Team::factory()->create();
        $option = GameOption::create([
            'team_id' => $team->id,
            'name' => 'Ancienne option',
            'type' => GameOption::TYPE_TEXT,
            'order' => 1,
            'display' => GameOption::DISP_ALL,
        ]);

        Livewire::test(Parameters::class, ['team' => $team])
            ->call('addRow')
            ->assertCount('gameoptions', 2)
            ->call('delete', $option->id);

        $this->assertDatabaseMissing('game_options', ['id' => $option->id]);
    }
}