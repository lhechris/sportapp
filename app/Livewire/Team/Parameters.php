<?php

namespace App\Livewire\Team;

use Livewire\Component;
use App\Models\GameOption;
use App\Models\Team;
use Illuminate\Validation\Rule;

class Parameters extends Component
{
    public Team $team;
    public array $gameoptions = [];

    public array $typeOptions = [];
    public array $displayOptions = [];

    public function mount(): void
    {
        $this->typeOptions = GameOption::types();
        $this->displayOptions = GameOption::displays();
        $this->loadData();
    }

    private function loadData(): void
    {
        $this->gameoptions = $this->team->game_options()
            ->orderBy('order')
            ->get()
            ->keyBy('id')
            ->map(fn ($option) => [
                'id'      => $option->id,
                'name'    => $option->name,
                'type'    => $option->type,
                'order'   => $option->order,
                'display' => $option->display,
            ])
            ->toArray();
    }

    public function addRow(): void
    {
        $tempKey = 'new-' . uniqid();

        $this->gameoptions[$tempKey] = [
            'id'      => null,
            'name'    => '',
            'type'    => '',
            'order'   => 0,
            'display' => '',
        ];
    }

    public function delete($key): void
    {
        $data = $this->gameoptions[$key] ?? null;

        if ($data && ! empty($data['id'])) {
            GameOption::findOrFail($data['id'])->delete();
        }

        unset($this->gameoptions[$key]);
    }

    public function saveAll(): void
    {
        $validated = validator($this->gameoptions, [
            '*.name'    => 'required|string|max:255',
            '*.type'    =>  ['required', Rule::in(array_keys(GameOption::types()))],
            '*.order'   => 'nullable|integer',
            '*.display' => ['nullable', Rule::in(array_keys(GameOption::displays()))],
        ])->validate();

        foreach ($this->gameoptions as $key => $data) {
            $row = $validated[$key];

            if (empty($data['id'])) {
                $option = $this->team->game_options()->create($row);

                unset($this->gameoptions[$key]);
                $this->gameoptions[$option->id] = array_merge($row, ['id' => $option->id]);
            } else {
                GameOption::findOrFail($data['id'])->update($row);
            }
        }

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.team.parameters');
    }
}