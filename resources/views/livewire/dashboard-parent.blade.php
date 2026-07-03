<div class="space-y-6">
    <livewire:InstallPrompt />
    <x-button
         x-show="!{{$hasSubscription ? 'true' : 'false'}}"
         onclick="subscribeToPush()" > S'abonner</x-button>
    <!-- MEMBRES -->
@forelse($members as $member)
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">
        <h2 class="text-white font-bold mb-4">
            {{ __('team.presenceof') }} {{ $member->prenom }} {{ __('team.upcomingmatches') }}
        </h2>

        <x-cards-scroll nextElementId="game-{{$member->id}}-{{$member->nextGameId}}" >

            @forelse($member->games as $game)
            <div id="game-{{$member->id}}-{{$game->id}}"
                 class="bg-black border border-yellow-300 p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">

                <div class="flex justify-between items-center mb-2">

                    <p class="text-yellow-400 font-semibold">{{ $game->titre }}</p>

                    <span class="text-yellow-400 px-2 py-1 rounded-lg">
                        {{ $game->formatdate() }}
                    </span>
                </div>

                <div class="mt-3 flex justify-between items-center">
                    <span class="text-gray-400">{{ __('team.game.rendezvous') }} : {{ $game->rendezvous }}</span>
                    <a href="{{ route('game.show', ['game' => $game->id]) }}" >
                        <span class="bg-yellow-400 text-black text-sm px-2 py-1 rounded-lg">
                        {{ __('global.info') }}
                        </span>
                    </a>
                </div>

                <div class="mt-3 text-black">
                    <p class="text-sm text-gray-300 mb-2">
                        {{ __('team.game.availability') }} : {{ ucfirst($game->pivot->availability ?? __('team.game.notrespond')) }}
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
    </div>
@empty
        <p class="text-gray-500">{{ __('team.nomembers') }}</p>
@endforelse
</div>