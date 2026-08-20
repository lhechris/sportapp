<?php

namespace App\Livewire\Game;

use Livewire\Component;

use App\Models\Game;
use App\Models\GameMemberOption;
use App\Models\GameOption;
use App\Models\Place;
use App\Models\User;

use App\Notifications\GameNotification;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Edit extends Component
{
    public Game $game;
    public bool $editingGame = false;
    public string $gameTitle = '';
    public string $gameDate = '';
    public ?string $gameLocation = '';
    public ?int $gamePlaceId = null;
    public string $gameRendezvous = '';
    public ?string $gameScore = '';
    public ?string $gameCommentaire = '';
    public ?int $gameNumero = null;

    public $options;

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
        $this->gameNumero = $this->game->numero ?? 0;
        
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
        $this->options = GameOption::where("team_id",$this->game->team_id)
                        ->orderBy('order')
                        ->get();

        $opts = $this->options->filter(function ($option) {
            return in_array(
                mb_strtolower($option->name),
                ['numero', 'numéro']
            );
        });
        if ($opts->count() > 0) {
            foreach ($this->members as $member) {
                $tocreate=true;
                foreach ($member->options as $option) {
                    if (in_array(strtolower($option->name), ['numero', 'numéro']) &&
                        !empty($member->gameOptions->firstWhere('game_option_id', $option->id)?->value)) {
                        
                        $tocreate=false;
                    }
                }
                if ($tocreate) {
                        $member->gameOptions->push(GameMemberOption::updateOrCreate(
                            [
                                'game_id' => $this->game->id,
                                'member_id' => $member->id,
                                'game_option_id' => $opts->first()->id,
                            ],
                            [
                                'value' => $member->numero,
                            ]
                        ));
                }
            }
        }

        $this->generateMessage();
        
        $this->places = Place::orderby('name')->get();

        /*foreach ($this->members as $member) {
            \Log::info("$member->prenom");
            foreach ($member->options as $option) {
                \Log::info("$option->type = ".$option->pivot->value);
            }
        }*/


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


    public function generateFeuille() {
        $spreadsheet = new Spreadsheet();
        $inputFileName = storage_path('app/' .  env("TEMPLATE_FILE"));
        $spreadsheet = IOFactory::load($inputFileName);
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('C3', $this->game->numero);
        $date = \Carbon\Carbon::parse($this->game->date)->format("Y-m-d"); 
        $activeWorksheet->setCellValue('E3', $date);

        $oppositionA = $this->game->members()
                                  ->whereHas('oppositionOptions', function ($query) {
                    $query->where('game_id', $this->game->id)
                        ->where('value', 'A');
                })->get();

        $oppositionB = $this->game->members()
                                  ->whereHas('oppositionOptions', function ($query) {
                    $query->where('game_id', $this->game->id)
                        ->where('value', 'B');
                })->get();

        $this->writeopposition($activeWorksheet,$oppositionB,8);
        $this->writeopposition($activeWorksheet,$oppositionA,18);
        $this->writestaff($activeWorksheet,$this->game->team->coaches()->get(),27);
        /*$this->writestaff($activeWorksheet,$this->game->otm,32);*/
        $writer = new Xlsx($spreadsheet);

        $filename = 'feuille_match_' . $this->game->numero . '.xlsx';

        $this->loaddata();

        return response()->streamDownload(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
        
    }

    private function writeopposition($sheet,$opp,$start) 
    {
        $numligne=$start;
        foreach($opp as $joueur) {
            foreach ($joueur->options as $option) {
                if ($option->type === GameOption::TYPE_NUM) {
                    $sheet->setCellValue("A$numligne", $option->pivot->value);                    
                }
            }
            
            $sheet->setCellValue("B$numligne", $joueur->licence);
            $sheet->setCellValue("C$numligne", $joueur->name);
            $sheet->setCellValue("D$numligne", $joueur->prenom);
            $numligne++;
        }
    }

    private function writestaff($sheet,$staffs,$start) 
    {
        $numligne=$start;
        foreach($staffs as $s) {            
            $sheet->setCellValue("A$numligne", $s->licence);
            $sheet->setCellValue("C$numligne", $s->name);
            $sheet->setCellValue("D$numligne", $s->prenom);
            $numligne++;
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
            $this->gameNumero = $this->game->numero ?? 0;          
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
            'commentaire' => $this->gameCommentaire,
            'numero' => $this->gameNumero
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
        return view('livewire.game.edit', [
        ])->layout('layouts.app');
    }
}
