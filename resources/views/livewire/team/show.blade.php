<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                🏀 {{ $team->name }}
            </h1>
            <div class="text-gray-600 flex gap-2" >
                <p>{{ $team->players()->count() }} {{ __('Players') }}</p>
                <p>{{ $team->staffs()->count() }} {{ __('Staffs') }}</p>
                <p>{{ $team->coaches()->count() }} {{ __('Coaches') }}</p>
            </div>
        </div>

    </div>

    <!-- COACHS -->
    <div>
        <h2 class="text-lg font-semibold mb-2">{{ __('Coaches') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            @forelse($team->coaches as $coach)
                <x-card title="{{ $coach->prenom }}" tag=" {{ $coach->licence ?? '.....' }}" />
            @empty
                <p class="text-gray-500">{{ __('No coach') }}</p>
            @endforelse

        </div>
    </div>

    <!-- JOUEURS -->
    <div>
        <div class="flex gap-4 mb-2">
            <h2 class="text-lg font-semibold mb-4">{{ __('Players') }}</h2>
            <a href="{{ route('team.members', ['team' => $team->id ]) }}" 
            class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
               ⚙️ {{ __('Edit') }}
            </a>
        </div>


        <div class="flex gap-4">

            <table class="w-full text-sm text-left rtl:text-right text-body text-yellow-400">
                <thead class="bg-black border-b border-default">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">{{ __('First name') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium">{{ __('Licence') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium">{{ __('Matchs') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium">{{ __('Trainings') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $player)
                    <tr class="odd:bg-gray-800 even:bg-gray-900 border-b border-default">
                        <td class="px-6 py-4">{{ $player->prenom }}</td>
                        <td class="px-6 py-4">{{ $player->licence ?? '.....' }}</td>
                        <td class="px-6 py-4">{{ $player->games_count }}</td>
                        <td class="px-6 py-4">{{ $player->trainings_count }}</td>
                        <td class="px-6 py-4">
                        <button class="text-sm text-white hover:underline">
                            {{ __('View profile') }} →
                        </button>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-gray-500">{{ __('No players on the team') }}</tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <!-- MATCHS -->
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


    <!-- ENTRAINEMENTS -->
    <div>
        <div class="flex gap-4 mb-2">
            <h2 class="text-lg font-semibold mb-4">{{ __('Trainings') }}</h2>
            <a href="{{ route('team.trainings.create', ['team' => $team->id ]) }}" 
               class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
                ➕️{{ __('Create') }}
            </a>
        </div>

        <x-cards-scroll nextElementId="training-{{ $nextTrainingId }}" >
            @forelse($team->trainings as $training)
                <x-card title="{{$training->titre}}" 
                        id="training-{{ $training->id }}" 
                        href="{{ route('training.show', [ 'training' => $training->id]) }}"
                        description="{{ $training->formatdate() }}" >
                </x-card>
            @empty
                <p class="text-gray-500">{{ __('No training for the team') }}</p>
            @endforelse
        </x-cards-scroll>
    </div>


</div>