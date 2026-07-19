<div id="game-{{$member->id}}-{{$game->id}}" class="bg-black border border-yellow-300 p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">
    @if(auth()->user()->isCoach())
    <a href="{{  route('game.edit', ['game' => $game->id]) }}" >
    @else
    <a href="{{  route('game.show', ['game' => $game->id]) }}" >
    @endif
    <div class="flex justify-between items-center mb-2">

        <p class="text-yellow-400 font-semibold">{{ $game->titre }}</p>

        <span class="text-yellow-400 px-2 py-1 rounded-lg">
            {{ $game->formatdate() }}
        </span>
    </div>

    <div class="mt-3 flex justify-between items-center">
        <span class="text-gray-400">{{ __('team.game.rendezvous') }} : {{ $game->rendezvous }}</span>
    </div>
    </a>
    <div class="mt-3 text-black">
        <p class="text-sm text-gray-300 mb-2">
            Disponibilité : {{ ucfirst($availability ?? 'pas répondu') }}
        </p>

        <div class="flex flex-wrap gap-2">
            <button wire:click="setAvailability({{ $member->id }}, {{ $game->id }}, 'yes')"
                class="px-3 py-1 rounded-lg {{ $availability === 'yes' ? 'bg-green-500' : 'bg-gray-600' }} text-white text-sm hover:bg-green-600">
                {{ __('team.game.present') }}
            </button>

            <button wire:click="setAvailability({{ $member->id }}, {{ $game->id }}, 'no')"
                class="px-3 py-1 rounded-lg {{ $availability === 'no' ? 'bg-red-500' : 'bg-gray-600' }} text-white text-sm hover:bg-red-600">
                {{ __('team.game.absent') }}
            </button>
        </div>
    </div>
</div>
