<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                🏀 {{ $team->name }}
            </h1>

            <p class="text-gray-600">
                {{ $members->count() }} membres
            </p>
        </div>

        <a href="{{ route('team.games.create', ['team' => $team->id ]) }}" 
           class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
            Créer un match
        </a>

        <a href="{{ route('team.members', ['team' => $team->id ]) }}" 
           class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
            Gérer effectif
        </a>

    </div>

    <!-- COACHS -->
    <div>
        <h2 class="text-lg font-semibold mb-2">Coachs</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            @forelse($team->coaches as $coach)
                <div class="bg-gray-900 text-yellow-400 p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-2">
                        <p class="font-semibold">{{ $coach->prenom }}</p>
                        <span class="bg-yellow-400 text-black text-xs px-2 py-1 rounded-lg">
                            {{ $coach->licence ?? '.....' }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Aucun coach</p>
            @endforelse

        </div>
    </div>

    <!-- JOUEURS -->
    <div>
        <h2 class="text-lg font-semibold mb-4">Joueurs</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            @forelse($team->players as $player)

                <div class="bg-gray-900 text-yellow-400 p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">

                    <!-- HEADER CARD -->
                    <div class="flex justify-between items-center mb-2">

                        <p class="font-semibold">
                            {{ $player->prenom }}
                        </p>

                        <span class="bg-yellow-400 text-black text-xs px-2 py-1 rounded-lg">
                            {{ $player->licence ?? '.....' }}
                        </span>

                    </div>

                    <!-- ACTION -->
                    <div class="mt-3">
                        <button class="text-sm text-gray-700 hover:underline">
                            Voir profil →
                        </button>
                    </div>

                </div>

            @empty
                <p class="text-gray-500">Aucun joueur dans l'équipe</p>
            @endforelse

        </div>
    </div>

    <!-- MATCHS -->
    <div>
        <h2 class="text-lg font-semibold mb-4">Matchs</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            @forelse($team->games as $game)
                <a href="{{ route('game-admin.show', [ 'game' => $game->id]) }}" >
                    <div class="bg-gray-900 text-yellow-400 p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">

                        <!-- HEADER CARD -->
                        <div class="flex justify-between items-center mb-2">

                            <p class="font-semibold">
                                {{ $game->titre }}
                            </p>

                            <span class="bg-yellow-400 text-black text-xs px-2 py-1 rounded-lg">
                                {{ \Carbon\Carbon::parse($game->date)->format('d/m/Y à H:i')}}
                            </span>

                        </div>

                        <!-- ACTION -->
                        <div class="mt-3 text-gray-400">
                            Rendez vous : {{ $game->rendezvous }}
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-500">Aucun match pour l'équipe</p>
            @endforelse
        </div>
    </div>
</div>