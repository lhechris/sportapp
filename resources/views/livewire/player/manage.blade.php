<div class="p-6 bg-white rounded shadow">

    <h1 class="text-xl font-bold mb-4">
        Gestion des joueurs
    </h1>

    <!-- FORMULAIRE -->
    <div class="mb-6">
        <input wire:model="name" placeholder="Nom" class="border p-2 w-full mb-2">

        <input wire:model="email" placeholder="Email" class="border p-2 w-full mb-2">

        @if(!$editingId)
            <input wire:model="password" type="password" placeholder="Mot de passe" class="border p-2 w-full mb-2">
        @endif


        <select wire:model="parent_id">
            <option value="">-- Parent --</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
        </select>


        <button wire:click="save" class="bg-blue-500 text-white px-4 py-2">
            {{ $editingId ? 'Mettre à jour' : 'Créer' }}
        </button>

        <button wire:click="resetForm" class="ml-2 text-gray-600">
            Reset
        </button>
    </div>

    <!-- LISTE -->
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2">Nom</th>
                <th class="p-2">Email</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($players as $player)
                <tr class="border-t">
                    <td class="p-2">{{ $player->name }}</td>
                    <td class="p-2">{{ $player->email }}</td>
                    <td class="p-2">
                        <button wire:click="edit({{ $player->id }})" class="text-blue-600">
                            Modifier
                        </button>

                        <button wire:click="delete({{ $player->id }})" class="text-red-600 ml-2">
                            Supprimer
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
