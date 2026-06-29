<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
   /* Volt::route('register', 'pages.auth.register')
        ->name('register');*/

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {

    $googleUser = Socialite::driver('google')->user();

    // chercher ou créer utilisateur
    $user = App\Models\User::where('google_id', $googleUser->getId())
        ->orWhere('email', $googleUser->getEmail())
        ->first();


    if (!$user) {
        \Log::info("Cree un nouvel utilisateur google :".$googleUser->getId());

        $user = App\Models\User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(uniqid()), // obligatoire mais inutile
        ]);

    } else {
        \Log::info("Utilisateur google existant :".$googleUser->getId());

        $user->update([
            'google_id' => $googleUser->getId()
        ]);
    }
    Auth::login($user);

    return redirect('/dashboard');
});

Route::get('logout', function () {
    Auth::logout();
    redirect(route('login'));
});
