<?php

namespace Tests\Feature;

use App\Livewire\News\Form;
use App\Livewire\News\Manage;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_news_is_displayed_on_the_dashboard(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_COACH]);
        News::create([
            'title' => 'Annonce publiée',
            'description' => "Ligne 1\n\tLigne 2\\nLigne 3",
            'active' => true,
        ]);
        News::create([
            'title' => 'Annonce masquée',
            'description' => 'Contenu invisible',
            'active' => false,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Annonce publiée')
            ->assertDontSee('Annonce masquée')
            ->assertSee('<br>', false)
            ->assertSee('tab-size: 4;', false)
            ->assertSee('Voir plus');
    }

    public function test_coach_can_create_news(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_COACH]);

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('title', 'Nouvelle annonce')
            ->set('description', 'Le contenu de l’annonce')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('news', [
            'title' => 'Nouvelle annonce',
            'active' => true,
        ]);
    }

    public function test_non_coach_cannot_access_news_form(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_PARENT]);

        $this->actingAs($user)
            ->get('/news/create')
            ->assertNotfound();
    }

    public function test_coach_can_edit_news(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_COACH]);
        $news = News::create([
            'title' => 'Ancien titre',
            'description' => 'Ancienne description',
            'active' => true,
        ]);

        Livewire::actingAs($user)
            ->test(Manage::class)
            ->call('edit', $news->id)
            ->assertSet('editingNews.id', $news->id);

        Livewire::actingAs($user)
            ->test(Form::class, ['news' => $news])
            ->set('title', 'Nouveau titre')
            ->set('description', 'Nouvelle description')
            ->set('active', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Nouveau titre',
            'description' => 'Nouvelle description',
            'active' => false,
        ]);
    }
}
