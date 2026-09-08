<?php

namespace Tests\Feature;

use App\Livewire\Member\Manage as MemberManage;
use App\Livewire\User\Manage as UserManage;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class MemberAndUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_can_create_a_member(): void
    {
        $coach = User::factory()->create(['role' => User::ROLE_COACH]);

        Livewire::actingAs($coach)
            ->test(MemberManage::class)
            ->set('newMember', [
                'name' => 'Dupont',
                'prenom' => 'Alice',
                'type' => Member::TYPE_PLAYER,
                'birthdate' => '2010-05-12',
                'licence' => 'LIC-123',
                'numero' => 7,
            ])
            ->call('createMember')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('members', [
            'name' => 'Dupont',
            'prenom' => 'Alice',
            'type' => Member::TYPE_PLAYER,
            'numero' => 7,
        ]);
    }

    public function test_coach_can_create_a_user_with_a_member_relation(): void
    {
        $coach = User::factory()->create(['role' => User::ROLE_COACH]);
        $member = Member::factory()->create();

        Livewire::actingAs($coach)
            ->test(UserManage::class)
            ->set('newUser.firstname', 'Alice')
            ->set('newUser.name', 'Dupont')
            ->set('newUser.email', 'alice@example.test')
            ->set('newUser.password', 'secret123')
            ->set('newUser.role', User::ROLE_PARENT)
            ->set("newUser.selectedMembers.{$member->id}", 'parent')
            ->call('createUser')
            ->assertHasNoErrors();

        $user = User::where('email', 'alice@example.test')->firstOrFail();

        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertDatabaseHas('member_user', [
            'user_id' => $user->id,
            'member_id' => $member->id,
            'relation' => 'parent',
        ]);
    }

    public function test_user_creation_requires_a_unique_email_and_password(): void
    {
        $coach = User::factory()->create(['role' => User::ROLE_COACH]);
        User::factory()->create(['email' => 'existing@example.test']);

        Livewire::actingAs($coach)
            ->test(UserManage::class)
            ->set('newUser.firstname', 'Alice')
            ->set('newUser.name', 'Dupont')
            ->set('newUser.email', 'existing@example.test')
            ->set('newUser.password', 'short')
            ->call('createUser')
            ->assertHasErrors(['newUser.email', 'newUser.password']);
    }
}