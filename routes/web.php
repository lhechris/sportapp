<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Team;
use App\Livewire\Member;
use App\Livewire\Dashboard;
use App\Livewire\User;
use App\Livewire\Game;

Route::view('/', 'welcome');

/*Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');    

    Route::get('/team/{team}', Team\Show::class)->name('team.show');
    Route::get('/team/{team}/members', Team\ManageMembers::class)->name('team.members');
    Route::get('/team/{team}/games/create', Game\Create::class)->name('team.games.create');

    Route::get('/members', Member\Manage::class)->name("members");
    Route::get('/users', User\Manage::class)->name("users");

    Route::get('/games/{game}', Game\Show::class)->name('game.show');

});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'isCoach'])->group(function () {
    Route::get('/teams/create', Team\Create::class)->name('teams.create');
    Route::get('/gamesadmin/{game}', Game\ShowAdmin::class)->name('game-admin.show');
    Route::get('/games/{game}/selection', Game\Selection::class)->name('game.selection');
});




require __DIR__.'/auth.php';
