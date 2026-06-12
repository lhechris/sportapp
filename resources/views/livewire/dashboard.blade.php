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

        <div class="text-right">
            <p class="text-gray-400 text-sm">Connecté en tant que</p>
            <p class="text-yellow-400 font-semibold">
                {{ auth()->user()->name }}
            </p>
        </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-gray-900 p-5 rounded-2xl shadow border border-gray-800">
            <p class="text-gray-400 text-sm">Équipes</p>
            <p class="text-3xl font-bold text-yellow-400">
                {{ $teams->count() }}
            </p>
        </div>

        <div class="bg-gray-900 p-5 rounded-2xl shadow border border-gray-800">
            <p class="text-gray-400 text-sm">Membres</p>
            <p class="text-3xl font-bold text-yellow-400">
                {{ \App\Models\Member::count() }}
            </p>
        </div>

        <div class="bg-gray-900 p-5 rounded-2xl shadow border border-gray-800">
            <p class="text-gray-400 text-sm">Rôle</p>
            <p class="text-3xl font-bold text-yellow-400 capitalize">
                {{ auth()->user()->role }}
            </p>
        </div>

    </div>

    <!-- ACTIONS -->
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">

        <h2 class="text-white font-bold mb-4">
            ⚡ Actions rapides
        </h2>

        <div class="flex flex-wrap gap-3">

            <a href="{{ route('teams.create') }}"
               class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300">
                ➕ Nouvelle équipe
            </a>

            <a href="{{ route('members') }}"
               class="bg-white text-black px-4 py-2 rounded-lg font-semibold hover:bg-gray-200">
                👥 Effectif
            </a>

        </div>

    </div>

    <!-- TEAMS -->
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">

        <h2 class="text-white font-bold mb-4">
            🏀 Mes équipes
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            @forelse($teams as $team)

                <a href="{{ route('team.show', ['team' => $team->id]) }}" >
                    <div class="flex justify-between items-center bg-black p-4 rounded-xl border border-yellow-300 hover:scale-[1.02] transition">

                        <div>
                            <p class="text-white font-semibold">
                                {{ $team->name }}
                            </p>

                            <p class="text-gray-400 text-sm">
                                {{ $team->members()->count() }} membres
                            </p>
                        </div>

                        <span class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300">
                            Gérer
                        </span>

                    </div>
                </a>

            @empty
                <p class="text-gray-500">Aucune équipe</p>
            @endforelse

        </div>
    </div>

</div>