<div class="space-y-6">
    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ auth()->user()->firstname }}
                </h1>
                <p class="text-gray-400 text-sm">
                    AS Labarthaise Basket
                </p>
            </div>
            <livewire:InstallPrompt />
            <x-button 
                x-show="!{{$hasSubscription ? 'true' : 'false'}}"
                onclick="subscribeToPush()" > S'abonner</x-button>
        </div>
 

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-gray-900 p-5 rounded-2xl shadow border border-gray-800">
            <p class="text-gray-400 text-sm">{{ __('team.teams') }}</p>
            <p class="text-3xl font-bold text-yellow-400">
                {{ $teams->count() }}
            </p>
        </div>

        <div class="bg-gray-900 p-5 rounded-2xl shadow border border-gray-800">
            <p class="text-gray-400 text-sm">{{ __('team.members') }}</p>
            <p class="text-3xl font-bold text-yellow-400">
                {{ \App\Models\Member::count() }}
            </p>
        </div>

        <div class="bg-gray-900 p-5 rounded-2xl shadow border border-gray-800">
            <p class="text-gray-400 text-sm">{{ __('team.role') }}</p>
            <p class="text-3xl font-bold text-yellow-400 capitalize">
                {{ auth()->user()->role }}
            </p>
        </div>

    </div>

    <!-- ACTIONS -->
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">

        <h2 class="text-white font-bold mb-4">
            ⚡ {{ __('team.quickactions') }}
        </h2>

        <div class="flex flex-wrap gap-3">

            <a href="{{ route('teams.create') }}"
               class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300">
                ➕ {{ __('team.create') }}
            </a>

            <a href="{{ route('members') }}"
               class="bg-white text-black px-4 py-2 rounded-lg font-semibold hover:bg-gray-200">
                👥 {{ __('team.number') }}
            </a>

        </div>

    </div>

    <!-- TEAMS -->
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">

        <h2 class="text-white font-bold mb-4">
            🏀 {{ __('team.myteams') }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            @forelse(auth()->user()->teams as $team)

                <a href="{{ route('team.show', ['team' => $team->id]) }}" >
                    <div class="flex justify-between items-center bg-black p-4 rounded-xl border border-yellow-300 hover:scale-[1.02] transition">

                        <div>
                            <p class="text-white font-semibold">
                                {{ $team->name }}
                            </p>

                            <p class="text-gray-400 text-sm">
                                {{ $team->members()->count() }} {{ __('team.members') }}
                            </p>
                        </div>

                        <span class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300">
                            {{ __('global.manage') }}
                        </span>

                    </div>
                </a>

            @empty
                <p class="text-gray-500">{{ __('team.noteams') }}</p>
            @endforelse

        </div>
    </div>

    <!-- MEMBRES -->
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">
@forelse($members as $member)
@if($member->isplayer())
        <h2 class="text-white font-bold mb-4">
            {{ __('team.presenceof') }} {{ $member->prenom }} {{ __('team.upcomingmatches') }}
        </h2>

        <x-cards-scroll nextElementId="game-{{$member->id}}-{{$member->nextGameId}}" >

            @forelse($member->games as $game)
            <div id="game-{{$member->id}}-{{$game->id}}" class="bg-black border border-yellow-300 p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">

                <div class="flex justify-between items-center mb-2">

                    <p class="text-yellow-400 font-semibold">{{ $game->titre }}</p>

                    <span class="text-yellow-400 px-2 py-1 rounded-lg">
                        {{ $game->formatdate() }}
                    </span>
                </div>

                <div class="mt-3 flex justify-between items-center">
                    <span class="text-gray-400">{{ __('team.game.rendezvous') }} : {{ $game->rendezvous }}</span>
                    <a href="{{  route('game-admin.show', ['game' => $game->id]) }}" >
                        <span class="bg-yellow-400 text-black text-sm px-2 py-1 rounded-lg">
                        {{ __('global.info') }}
                        </span>
                    </a>
                </div>

                <div class="mt-3 text-black">
                    <p class="text-sm text-gray-300 mb-2">
                        Disponibilité : {{ ucfirst($game->pivot->availability ?? 'pas répondu') }}
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <button wire:click="setAvailability({{ $member->id }}, {{ $game->id }}, 'yes')"
                            class="px-3 py-1 rounded-lg {{ $game->pivot->availability === 'yes' ? 'bg-green-500' : 'bg-gray-600' }} text-white text-sm hover:bg-green-600">
                            {{ __('team.game.present') }}
                        </button>

                        <button wire:click="setAvailability({{ $member->id }}, {{ $game->id }}, 'no')"
                            class="px-3 py-1 rounded-lg {{ $game->pivot->availability === 'no' ? 'bg-red-500' : 'bg-gray-600' }} text-white text-sm hover:bg-red-600">
                            {{ __('team.game.absent') }}
                        </button>
                    </div>
                </div>
            </div> 
            @empty
                <p class="text-gray-500">{{ __('team.game.no') }}</p>
            @endforelse
        </x-cards-scroll>
@endif
@empty
        <p class="text-gray-500">{{ __('team.nomembers') }}</p>
@endforelse

    </div>

</div>