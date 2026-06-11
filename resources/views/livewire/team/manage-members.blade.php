<div class="p-6 rounded shadow">

    <h1 class="text-xl font-bold mb-4">
        Gestion équipe : {{ $team->name }}
    </h1>

    <!-- RECHERCHE -->
    <input 
        type="text"
        wire:model.live="search"
        placeholder="Rechercher un membre"
        class="border p-2 w-full mb-4"
    >

    <!-- RESULTATS -->
    @foreach($results as $member)
        <div class="flex justify-between border p-2 mb-2">

            <span>
                {{ $member->prenom }} ({{ $member->type }})
            </span>

            <div class="space-x-2">
                <button wire:click="addMember({{ $member->id }}, 'player')"
                    class="bg-green-500 text-white px-2 py-1">
                    Joueur
                </button>

                <button wire:click="addMember({{ $member->id }}, 'coach')"
                    class="bg-blue-500 text-white px-2 py-1">
                    Coach
                </button>
            </div>

        </div>
    @endforeach

    <!-- LISTE ACTUELLE -->
    <h2 class="mt-6 font-bold">Effectif</h2>

    @foreach($members as $member)
        <div class="flex justify-between border p-2 mt-2">

            <span>
                {{ $member->prenom }} 
                → {{ $member->pivot->role }}
            </span>

            <button wire:click="removeMember({{ $member->id }})"
                class="text-red-600">
                Supprimer
            </button>
        </div>
    @endforeach

</div>