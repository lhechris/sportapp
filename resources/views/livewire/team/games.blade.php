<div>
    <div class="flex gap-4 mb-2">
        <h2 class="text-lg font-semibold mb-4">{{ __('Matches') }}</h2>
        <a href="{{ route('team.games.create', ['team' => $team->id ]) }}" 
           class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
            ➕️{{ __('Create') }}
        </a>
    </div>

    <x-cards-scroll nextElementId="game-{{ $nextGameId }}" >
        @forelse($team->games as $game)
            <x-card title="{{$game->titre}}" 
                    id="game-{{ $game->id }}" 
                    href="{{ route('game-admin.show', [ 'game' => $game->id]) }}"
                    description="{{ $game->formatdate() }}" >
                {{ __('Rendezvous') }} : {{ $game->rendezvous }}
            </x-card>
        @empty
            <p class="text-gray-500">{{ __('No matches for the team') }}</p>
        @endforelse
    </x-cards-scroll>
</div>
