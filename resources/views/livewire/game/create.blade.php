<div class="max-w-xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
        Nouveau match pour l'équipe {{ $team->name }}
    </h1>

    <div class="bg-white p-6 rounded-2xl shadow space-y-4">

        <input type="text"
               wire:model="titre"
               placeholder="Titre"
               class="w-full border p-2 rounded">

        <input type="datetime-local"
               wire:model="date"
               class="w-full border p-2 rounded">

        <input type="text"
               wire:model="location"
               placeholder="Lieu"
               class="w-full border p-2 rounded">

        <input type="text"
               wire:model="rendezvous"
               placeholder="Rendez vous"
               class="w-full border p-2 rounded">

        <button wire:click="save"
                class="bg-black text-yellow-400 px-4 py-2 rounded-lg">
            Créer le match
        </button>

    </div>

</div>