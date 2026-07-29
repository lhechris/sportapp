<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;


new class extends Component
{
    public $teams;

    public function mount() {
        if (auth()->user()) {
            $this->teams = auth()->user()->ownedTeams()->get();
        }
    }
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="border-b border-gray-900">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 hidden md:flex md:items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>                    
                </div>
                @auth
                <!-- Navigation Links -->
                <div class="space-x-4 -my-px ms-0 sm:ms-10 flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="flex flex-col mt-1 items-center gap-1">
                        <img src="{{ asset('images/home.png') }}" alt="{{ __('global.home') }}" class="h-6 w-6" />
                        <span class="text-sm">{{ __('global.home') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate class="flex flex-col mt-1 items-center gap-1">
                        <img src="{{ asset('images/utilisateur.png') }}" alt="{{ __('global.profile.title') }}" class="h-6 w-6" />
                        <span class="text-sm">{{ __('global.profile.title') }}</span>
                    </x-nav-link>

                    @if(auth()->user()->role == "coach")

                    <div class="px-1 pt-1 my-1">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex flex-col items-center gap-1 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <img src="{{ asset('images/utilisateur.png') }}" alt="{{ __('team.teams') }}" class="h-6 w-6" />
                                    <div class="flex">
                                        <span class="text-sm">{{ __('team.teams') }}</span>                               
                                        <span class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                </button>
                            </x-slot>
                    
                            <x-slot name="content">
                            @foreach( $teams as $team)
                                <x-dropdown-link :href="route('team.show', ['team'=>$team->id])" wire:navigate>
                                    {{ $team->name }}
                                </x-dropdown-link>
                            @endforeach
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="px-1 pt-1 my-1">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex flex-col items-center gap-1 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <img src="{{ asset('images/utilisateurs.png') }}" alt="{{ __('global.users') }}" class="h-6 w-6" />
                                    <div class="flex">
                                        <span class="text-sm">{{ __('global.users_cut') }}</span>                               
                                        <span class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                </button>
                            </x-slot>
                    
                            <x-slot name="content">
                                <x-dropdown-link :href="route('users')" wire:navigate>
                                    {{ __('global.users') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('members')" wire:navigate>
                                    {{ __('team.members') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
                </div>

                    <button wire:click="logout" class="w-full text-start flex mt-1 flex-col items-center gap-1" >
                        <x-dropdown-link>
                            <img src="{{ asset('images/logout.png') }}" alt="{{ __('auth.login.logout') }}" class="h-6 w-6" />
                            <span class="text-sm truncate">{{ __('auth.login.logout_cut') }}</span>                                
                        </x-dropdown-link>
                    </button>
                @endauth
            </div>
        </div>
    </div>

</nav>
