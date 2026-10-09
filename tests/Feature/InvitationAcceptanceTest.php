<?php

namespace Tests\Feature;

use App\Livewire\User\Accept;
use App\Models\Invitation;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class InvitationAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_invited_user_can_complete_account_without_changing_attached_members(): void
    {
        [$invitation, $invitedUser, $member, $otherMember] = $this->createInvitationWithMembers();

        Livewire::test(Accept::class, ['token' => $invitation->token])
            ->set('firstname', 'Alice')
            ->set('name', 'Dupont')
            ->set('email', 'parent@example.test')
            ->set('password', 'StrongPassword123!')
            ->set('password_confirmation', 'StrongPassword123!')
            ->call('store')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $invitedUser->refresh();

        $this->assertSame('Alice', $invitedUser->firstname);
        $this->assertSame('Dupont', $invitedUser->name);
        $this->assertSame('parent@example.test', $invitedUser->email);
        $this->assertTrue(Hash::check('StrongPassword123!', $invitedUser->password));
        $this->assertDatabaseMissing('invitations', ['id' => $invitation->id]);
        $this->assertDatabaseHas('member_user', [
            'user_id' => $invitedUser->id,
            'member_id' => $member->id,
            'relation' => 'parent',
        ]);
        $this->assertDatabaseMissing('member_user', [
            'user_id' => $invitedUser->id,
            'member_id' => $otherMember->id,
        ]);
        $this->assertAuthenticatedAs($invitedUser);
    }

    public function test_invitation_acceptance_requires_registration_fields(): void
    {
        [$invitation] = $this->createInvitationWithMembers();

        Livewire::test(Accept::class, ['token' => $invitation->token])
            ->call('store')
            ->assertHasErrors([
                'firstname' => 'required',
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);

        $this->assertDatabaseHas('invitations', ['id' => $invitation->id]);
    }

    private function createInvitationWithMembers(): array
    {
        $creator = User::factory()->create(['role' => User::ROLE_COACH]);
        $invitedUser = User::factory()->create([
            'firstname' => 'À compléter',
            'name' => 'À compléter',
            'email' => 'invited@example.test',
            'role' => User::ROLE_PARENT,
        ]);
        $member = Member::factory()->create(['prenom' => 'Enfant lié']);
        $otherMember = Member::factory()->create(['prenom' => 'Autre enfant']);
        $invitedUser->members()->attach($member->id, ['relation' => 'parent']);

        $invitation = Invitation::create([
            'email' => 'invited@example.test',
            'token' => 'acceptance-test-token-'.$invitedUser->id,
            'created_by' => $creator->id,
            'user_id' => $invitedUser->id,
            'expires_at' => now()->addDays(7),
        ]);

        return [$invitation, $invitedUser, $member, $otherMember];
    }
}