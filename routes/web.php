<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Team;
use App\Livewire\Member;
use App\Livewire\Dashboard;
use App\Livewire\User;
use App\Livewire\Game;
use App\Livewire\Training;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

//Route::view('/', 'welcome');
Route::get('/', function () {
        return auth()->check()
            ? redirect()->route('dashboard')
            : view('welcome');
});

/*Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');    
    Route::get('/games/{game}', Game\Show::class)->name('game.show');

});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'isCoach'])->group(function () {
    Route::get('/team/{team}', Team\Show::class)->name('team.show');
    Route::get('/team/{team}/members', Team\ManageMembers::class)->name('team.members');
    Route::get('/members', Member\Manage::class)->name("members");
    Route::get('/users', User\Manage::class)->name("users");
    Route::get('/teams/create', Team\Create::class)->name('teams.create');
    Route::get('/team/{team}/games/create', Game\Create::class)->name('team.games.create');
    Route::get('/team/{team}/trainings/create', Training\Create::class)->name('team.trainings.create');
    Route::get('/gamesadmin/{game}', Game\ShowAdmin::class)->name('game-admin.show');
    Route::get('/games/{game}/selection', Game\Selection::class)->name('game.selection');
    Route::get('/trainings/{training}', Training\Show::class)->name('training.show');
});


Route::get('/invitation/{token}', User\Accept::class)->name('invitation.accept');


Route::get('/test', function () {
    $inv = \App\Models\Invitation::all()->first();

    $carbon1 = new \Carbon\Carbon($inv->created_at);
    echo $carbon1."<br/>";
    $carbon2 = new \Carbon\Carbon($inv->expires_at);
    echo $carbon2."<br/>";

   if ($carbon1->isPast()) {
    echo '1 : yes<br/>';    
   } else {
    echo '1 : no<br/>';
   }
   if ($carbon2->isPast()) {
    echo '2 : yes<br/>';    
   } else {
    echo '2 : no<br/>';
   }


});

require __DIR__.'/auth.php';
