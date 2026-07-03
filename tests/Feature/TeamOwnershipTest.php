<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_team_can_have_multiple_owners(): void
    {
        $firstOwner = User::factory()->create();
        $secondOwner = User::factory()->create();

        $team = Team::create([
            'name' => 'Equipe test',
        ]);

        $team->owners()->attach([$firstOwner->id, $secondOwner->id]);

        $this->assertTrue($team->owners()->whereKey($firstOwner->id)->exists());
        $this->assertTrue($team->owners()->whereKey($secondOwner->id)->exists());
        $this->assertCount(2, $team->owners);
        $this->assertTrue($firstOwner->ownedTeams()->whereKey($team->id)->exists());
        $this->assertTrue($secondOwner->ownedTeams()->whereKey($team->id)->exists());
    }
}
