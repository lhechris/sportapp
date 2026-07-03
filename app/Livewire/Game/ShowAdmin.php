<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;
use App\Models\GameMemberOption;
use App\Models\GameOption;
use App\Models\User;

use App\Notifications\GameNotification;

class ShowAdmin extends Component
{
    public Game $game;
    public bool $editingGame = false;
    public string $gameTitle = '';
    public string $gameDate = '';
    public string $gameLocation = '';
    public string $gameRendezvous = '';

    public function mount()
    {
        $this->gameTitle = $this->game->titre===null?'':$this->game->titre;
        $this->gameDate = $this->game->date;
        $this->gameLocation = $this->game->location;
        $this->gameRendezvous = $this->game->rendezvous ?? '';
    }

    public function toggleEditingGame()
    {
        $this->editingGame = !$this->editingGame;
        if ($this->editingGame) {
            $this->gameTitle = $this->game->titre===null?'':$this->game->titre;
            $this->gameDate = $this->game->date;
            $this->gameLocation = $this->game->location;
            $this->gameRendezvous = $this->game->rendezvous ?? '';
        }
    }

    public function updateGame()
    {
        $this->game->update([
            'titre' => $this->gameTitle,
            'date' => $this->gameDate,
            'location' => $this->gameLocation,
            'rendezvous' => $this->gameRendezvous,
        ]);
        $this->editingGame = false;
    }

    public function setAvailability($memberId,$value)
    {
        $this->game->members()->updateExistingPivot($memberId, [
            'availability' => $value
        ]);
    }

    public function toggleSelection($memberId)
    {
        $pivot = $this->game->members()->find($memberId)->pivot;

        $this->game->members()->updateExistingPivot($memberId, [
            'selected' => !$pivot->selected
        ]);
    }

    public function setGameOption($memberId, $optionId, $value)
    {
        GameMemberOption::updateOrCreate(
            [
                'game_id' => $this->game->id,
                'member_id' => $memberId,
                'game_option_id' => $optionId,
            ],
            [
                'value' => $value,
            ]
        );
    }

    public function deleteGame() {
        $team_id=$this->game->team_id;
        $this->game->delete();
        redirect(route('team.show',[$team_id]));
    }

    public function sendNotification()
    {
       //$this->dispatch('notify', ['title' => 'ASLB','body'=>'Je notifie un truc']);
       \Log::info("sendNotification");
       User::all()->each->notify(new GameNotification());
    }

    public function render()
    {
        $members = $this->game->members()
                        ->with(['gameOptions' => function ($query) {
                            $query->where('game_id', $this->game->id);
                        }])
                        ->with(['options' => function($query) {
                            $query->wherePivot('game_id',$this->game->id);
                        }])
                        ->get();

        $options = GameOption::where("team_id",$this->game->team_id)
                        ->orderBy('order')
                        ->get();

        return view('livewire.game.show-admin', [
            'members' => $members,
            'options' => $options,
        ])->layout('layouts.app');
    }
}
