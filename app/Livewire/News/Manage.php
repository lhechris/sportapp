<?php

namespace App\Livewire\News;

use App\Models\News;
use Livewire\Component;

class Manage extends Component
{
    public $news;
    public ?News $editingNews = null;

    public function mount(): void
    {
        $this->loadNews();
    }

    private function loadNews(): void
    {
        $this->news = News::query()->latest()->get();
    }

    public function create(): void
    {
        $this->editingNews = null;
    }

    public function edit(int $id): void
    {
        $this->editingNews = News::findOrFail($id);
    }

    public function refreshNews(): void
    {
        $this->loadNews();
        $this->editingNews = null;
    }

    protected $listeners = ['news-saved' => 'refreshNews'];

    public function render()
    {
        return view('livewire.news.manage')->layout('layouts.app');
    }
}
