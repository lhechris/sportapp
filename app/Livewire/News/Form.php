<?php

namespace App\Livewire\News;

use App\Models\News;
use Livewire\Component;

class Form extends Component
{
    public ?News $news = null;
    public string $title = '';
    public string $description = '';
    public bool $active = true;

    public function mount(?News $news = null): void
    {
        $this->news = $news;

        if ($news) {
            $this->title = $news->title;
            $this->description = $news->description;
            $this->active = $news->active;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'active' => ['boolean'],
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'active' => $this->active,
        ];

        if ($this->news) {
            $this->news->update($data);
        } else {
            News::create($data);
        }

        session()->flash('success', $this->news ? 'Actualité modifiée.' : 'Actualité créée.');
        $this->dispatch('news-saved');
        $this->reset('title', 'description');
        $this->active = true;
        $this->news = null;
    }

    public function render()
    {
        return view('livewire.news.form');
    }
}
