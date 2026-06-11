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

        <a href="/team/{{ $team->id }}/members"
           class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
            Gérer effectif
        </a>

    </div>

    <!-- COACHS -->
    <div>
        <h2 class="text-lg font-semibold mb-2">👑 Coachs</h2>

        <div class="flex gap-3 flex-wrap">

            @forelse($team->coaches as $coach)
                <div class="bg-white px-4 py-2 rounded-xl shadow">
                    {{ $coach->prenom }}
                </div>
            @empty
                <p class="text-gray-500">Aucun coach</p>
            @endforelse

        </div>
    </div>

    <!-- JOUEURS -->
    <div>
        <h2 class="text-lg font-semibold mb-4">👥 Joueurs</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            @forelse($team->players as $player)

                <div class="bg-white p-4 rounded-2xl shadow hover:shadow-lg transition hover:-translate-y-1">

                    <!-- HEADER CARD -->
                    <div class="flex justify-between items-center mb-2">

                        <p class="font-semibold text-gray-900">
                            {{ $player->prenom }}
                        </p>

                        <span class="bg-yellow-400 text-black text-xs px-2 py-1 rounded-lg">
                            {{ $player->pivot->role ?? 'Joueur' }}
                        </span>

                    </div>

                    <!-- INFOS -->
                    <p class="text-sm text-gray-500">
                        Né le : {{ $player->birthdate ?? 'Non renseigné' }}
                    </p>

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

</div>