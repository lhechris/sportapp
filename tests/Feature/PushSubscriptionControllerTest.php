<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushSubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_store_a_push_subscription(): void
    {
        $user = User::factory()->create();
        $endpoint = 'https://push.example.test/subscription/123';

        $this->actingAs($user)
            ->postJson('/push/subscribe', [
                'endpoint' => $endpoint,
                'keys' => [
                    'p256dh' => 'public-key',
                    'auth' => 'auth-token',
                ],
            ])
            ->assertOk()
            ->assertExactJson(['success' => true]);

        $this->assertDatabaseHas('push_subscriptions', [
            'subscribable_type' => User::class,
            'subscribable_id' => $user->id,
            'endpoint' => $endpoint,
            'public_key' => 'public-key',
            'auth_token' => 'auth-token',
            'content_encoding' => 'aes128gcm',
        ]);
    }

    public function test_storing_existing_endpoint_updates_its_keys(): void
    {
        $user = User::factory()->create();
        $endpoint = 'https://push.example.test/subscription/123';

        $this->actingAs($user)->postJson('/push/subscribe', [
            'endpoint' => $endpoint,
            'keys' => ['p256dh' => 'old-public-key', 'auth' => 'old-auth-token'],
            'contentEncoding' => 'aesgcm',
        ])->assertOk();

        $this->actingAs($user)->postJson('/push/subscribe', [
            'endpoint' => $endpoint,
            'keys' => ['p256dh' => 'new-public-key', 'auth' => 'new-auth-token'],
        ])->assertOk();

        $this->assertDatabaseCount('push_subscriptions', 1);
        $this->assertDatabaseHas('push_subscriptions', [
            'subscribable_id' => $user->id,
            'endpoint' => $endpoint,
            'public_key' => 'new-public-key',
            'auth_token' => 'new-auth-token',
            'content_encoding' => 'aes128gcm',
        ]);
    }

    public function test_subscription_requires_endpoint_and_keys_and_valid_encoding(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/push/subscribe', [
                'endpoint' => 'not-a-url',
                'keys' => ['p256dh' => '', 'auth' => ''],
                'contentEncoding' => 'unsupported',
            ]);

        $this->assertSame(302, $response->status());
        $response->assertSessionHasErrors([
            'endpoint',
            'keys.p256dh',
            'keys.auth',
            'contentEncoding',
        ]);

        $this->assertDatabaseCount('push_subscriptions', 0);
    }

    public function test_authenticated_user_can_delete_only_their_own_subscription(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $ownedEndpoint = 'https://push.example.test/subscription/owned';
        $otherEndpoint = 'https://push.example.test/subscription/other';

        $this->actingAs($user)->postJson('/push/subscribe', [
            'endpoint' => $ownedEndpoint,
            'keys' => ['p256dh' => 'public-key', 'auth' => 'auth-token'],
        ]);
        $this->actingAs($otherUser)->postJson('/push/subscribe', [
            'endpoint' => $otherEndpoint,
            'keys' => ['p256dh' => 'other-public-key', 'auth' => 'other-auth-token'],
        ]);

        $this->actingAs($user)
            ->deleteJson('/push/unsubscribe', ['endpoint' => $ownedEndpoint])
            ->assertOk()
            ->assertExactJson(['success' => true]);

        $this->assertDatabaseMissing('push_subscriptions', ['endpoint' => $ownedEndpoint]);
        $this->assertDatabaseHas('push_subscriptions', [
            'subscribable_id' => $otherUser->id,
            'endpoint' => $otherEndpoint,
        ]);
    }

    public function test_push_subscription_routes_require_authentication(): void
    {
        $this->postJson('/push/subscribe', [
            'endpoint' => 'https://push.example.test/subscription/123',
            'keys' => ['p256dh' => 'public-key', 'auth' => 'auth-token'],
        ])->assertRedirect(route('login'));

        $this->deleteJson('/push/unsubscribe', [
            'endpoint' => 'https://push.example.test/subscription/123',
        ])->assertRedirect(route('login'));
    }
}