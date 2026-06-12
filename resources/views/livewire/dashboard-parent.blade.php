<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-4">
            <img src="/images/logo.jpg" class="h-12 w-12">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Dashboard
                </h1>
                <p class="text-gray-400 text-sm">
                    AS Labarthaise Basket
                </p>
            </div>
        </div>
    </div>


    <!-- MEMBRES -->
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">
@forelse($members as $member)
        <h2 class="text-white font-bold mb-4">
            Présence de {{ $member->prenom }} aux matchs a venir
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            @forelse($member->games as $game)
                
                    <div class="bg-black border border-yellow-300 p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">

                        <div class="flex justify-between items-center mb-2">

                            <p class="text-yellow-400 font-semibold">{{ $game->titre }}</p>

                            <span class="text-yellow-400 px-2 py-1 rounded-lg">
                                {{ \Carbon\Carbon::parse($game->date)->format('d/m/Y à H:i')}}
                            </span>
                        </div>

                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-gray-400">Rendez vous : {{ $game->rendezvous }}</span>
                            <a href="{{ route('game.show', ['game' => $game->id]) }}" >
                                <span class="bg-yellow-400 text-black text-sm px-2 py-1 rounded-lg">
                                Infos
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
                                    Présente
                                </button>

                                <button wire:click="setAvailability({{ $member->id }}, {{ $game->id }}, 'no')"
                                    class="px-3 py-1 rounded-lg {{ $game->pivot->availability === 'no' ? 'bg-red-500' : 'bg-gray-600' }} text-white text-sm hover:bg-red-600">
                                    Absente
                                </button>
                            </div>
                        </div>
                    </div>                 
            @empty
                <p class="text-gray-500">Pas de matchs</p>
            @endforelse

@empty
        <p class="text-gray-500">Aucun membre</p>
@endforelse

        </div>
    </div>

</div>