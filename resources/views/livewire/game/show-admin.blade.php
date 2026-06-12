<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $game->titre }}
            </h1>

            <p class="text-gray-600">
                {{ \Carbon\Carbon::parse($game->date)->format('d/m/Y H:i') }}
                • {{ $game->location }}
            </p>
        </div>

        <a href="{{ route('team.show', ['team' => $game->team->id ]) }}" 
           class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
            Retour à l'équipe
        </a>


    </div>

    <!-- STATS -->
    <div class="flex gap-4">

        <div class="bg-white p-3 rounded-xl shadow">
            Présentes: {{ $members->where('pivot.availability', 'yes')->count() }}
        </div>

        <div class="bg-white p-3 rounded-xl shadow">
            Absentes: {{ $members->where('pivot.availability', 'no')->count() }}
        </div>

        <div class="bg-white p-3 rounded-xl shadow">
            Sélectionnées : {{ $members->where('pivot.selected', true)->count() }}
        </div>

    </div>

    <!-- LISTE -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 ">

        @foreach($members as $member)

            <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">

                <div>
                    <p class="font-semibold">{{ $member->prenom }}</p>

                    <p class="text-sm text-gray-500">
                        {{ $member->pivot->availability ?? 'Pas répondu' }}
                    </p>
                </div>

                <div class="flex items-center gap-2">

                    <!-- DISPONIBILITE -->
                    <button wire:click="setAvailability({{ $member->id }}, 'yes')"
                        class="px-2 py-1 rounded {{ $member->pivot->availability === 'yes' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                        ✅
                    </button>

                    <button wire:click="setAvailability({{ $member->id }}, 'no')"
                        class="px-2 py-1 rounded {{ $member->pivot->availability === 'no' ? 'bg-red-500 text-white' : 'bg-gray-200' }}">
                        ❌
                    </button>

                    <button wire:click="setAvailability({{ $member->id }}, 'maybe')"
                        class="px-2 py-1 rounded {{ $member->pivot->availability === 'maybe' ? 'bg-yellow-400' : 'bg-gray-200' }}">
                        ❓
                    </button>

                    <!-- SELECTION -->
                    @if($member->pivot->availability === 'yes')
                        <button wire:click="toggleSelection({{ $member->id }})"
                            class="px-3 py-1 rounded font-semibold
                            {{ $member->pivot->selected ? 'bg-black text-blue-500' : 'bg-gray-300' }}">
                            {{ $member->pivot->selected ? 'Sélectionné' : 'Choisir' }}
                        </button>
                    @endif

                </div>

            </div>

        @endforeach

    </div>

</div>