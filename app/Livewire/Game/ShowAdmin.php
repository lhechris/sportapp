<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;
use App\Models\GameMemberOption;
use App\Models\GameOption;
use App\Models\Place;
use App\Models\User;

use App\Notifications\GameNotification;

class ShowAdmin extends Component
{
    public Game $game;
    public bool $editingGame = false;
    public string $gameTitle = '';
    public string $gameDate = '';
    public string $gameLocation = '';
    public ?int $gamePlaceId = null;
    public string $gameRendezvous = '';
    public string $gameScore = '';
    public string $gameCommentaire = '';

    public string $message = '';
    public $members;
    public $selected;
    public $places;


    public function mount()
    {
        $this->gameTitle = $this->game->titre===null?'':$this->game->titre;
        $this->gameDate = $this->game->date;
        $this->gameLocation = $this->game->location;
        $this->gamePlaceId = $this->game->place_id;
        $this->gameRendezvous = $this->game->rendezvous ?? '';
        $this->gameScore = $this->game->score ?? '';
        $this->gameCommentaire = $this->game->commentaire ?? '';
        
        $this->loaddata();

    }

    private function loaddata() {
        $this->members = $this->game->members()
                ->with(['gameOptions' => function ($query) {
                    $query->where('game_id', $this->game->id);
                }])
                ->with(['options' => function($query) {
                    $query->wherePivot('game_id',$this->game->id);
                }])
                ->get();

        $this->generateMessage();
        
        $this->places = Place::orderby('name')->get();
    }


    private function generateMessage() {
        if ($this->game->team->msg_convocation) {
            $this->message = $this->game->team->msg_convocation;

            $sels='';
            $notsels='';
            foreach($this->members as $member) {
                if ($member->pivot->selected) {
                    if ($sels !== "") { $sels.=", ";}
                    $sels .= $member->prenom;
                } else {
                    if ($notsels !== "") { $notsels.=", ";}
                    $notsels .= $member->prenom;
                }
            }
            $this->message = str_replace('%SELECTION%',$sels,$this->message);
            $this->message = str_replace('%NONSELECTION%',$notsels,$this->message);

            $jourmatch = \Carbon\Carbon::parse($this->game->date)->translatedFormat('l d F');
            $this->message = str_replace('%JOURMATCH%',$jourmatch,$this->message);

            $this->message = str_replace('%RENDEZVOUS%',$this->game->rendezvous,$this->message);


        } else {
            $this->message = '';
        }

    }

    public function toggleEditingGame()
    {
        $this->editingGame = !$this->editingGame;
        if ($this->editingGame) {
            $this->gameTitle = $this->game->titre===null?'':$this->game->titre;
            $this->gameDate = $this->game->date;
            $this->gamePlaceId = $this->game->place_id;
            $this->gameLocation = $this->game->location;
            $this->gameRendezvous = $this->game->rendezvous ?? '';
            $this->gameScore = $this->game->score ?? '';
            $this->gameCommentaire = $this->game->commentaire ?? '';           
        }
        $this->loaddata();
    }

    public function updateGame()
    {
        $place = $this->gamePlaceId ? Place::find($this->gamePlaceId) : null;

        $this->game->update([
            'titre' => $this->gameTitle,
            'date' => $this->gameDate,
            'location' => $place ? $place->name : $this->gameLocation,
            'place_id' => $this->gamePlaceId,
            'rendezvous' => $this->gameRendezvous,
            'score' => $this->gameScore,
            'commentaire' => $this->gameCommentaire
        ]);
        $this->editingGame = false;
        $this->loaddata();
    }

    public function setAvailability($memberId,$value)
    {
        $this->game->members()->updateExistingPivot($memberId, [
            'availability' => $value
        ]);
        $this->loaddata();
    }

    public function toggleSelection($memberId)
    {
        $pivot = $this->game->members()->find($memberId)->pivot;

        $this->game->members()->updateExistingPivot($memberId, [
            'selected' => !$pivot->selected
        ]);

        $this->loaddata();

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
         $this->loaddata();
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

    public function copyAndOpenWhatsapp(): void
    {
        $this->dispatch(
            'copy-and-open-whatsapp',
            message: $this->message,
            link: $this->game->team->whatsapp
        );
        $this->loaddata();
    }


    public function render()
    {
        $options = GameOption::where("team_id",$this->game->team_id)
                        ->orderBy('order')
                        ->get();

        return view('livewire.game.show-admin', [
            'options' => $options,
        ])->layout('layouts.app');
    }
}
