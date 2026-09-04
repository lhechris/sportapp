<?php

namespace App\Livewire\News;

use App\Models\News;
use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        return view('livewire.news.show', [
            'news' => News::query()
                ->where('active', true)
                ->latest()
                ->get(),
        ]);
    }
}
