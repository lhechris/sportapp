<div class="p-6 rounded shadow">

    <h1 class="text-xl font-bold mb-4">
        Gestion des membres
    </h1>

    <!-- FORM -->
    <div class="mb-6 space-y-2">

        <input wire:model="name" placeholder="Nom" class="border p-2 w-full">

        <select wire:model="type" class="border p-2 w-full">
            <option value="player">Joueur</option>
            <option value="coach">Coach</option>
            <option value="staff">Staff</option>
        </select>

        <input wire:model="prenom" placeholder="Prénom" class="border p-2 w-full">

        <input type="date" wire:model="birthdate" class="border p-2 w-full">

        <input wire:model="licence" placeholder="Licence" class="border p-2 w-full">

        <div>
            <button wire:click="save" class="bg-blue-500 text-white px-4 py-2">
                {{ $editingId ? 'Mettre à jour' : 'Créer' }}
            </button>

            <button wire:click="resetForm" class="ml-2 text-gray-600">
                Reset
            </button>
        </div>

    </div>

    <!-- LIST -->
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2">Nom</th>
                <th class="p-2">Prénom</th>
                <th class="p-2">Type</th>
                <th class="p-2">Naissance</th>
                <th class="p-2">Licence</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
                <tr class="border-t">
                    <td class="p-2">{{ $member->name }}</td>
                    <td class="p-2">{{ $member->prenom }}</td>
                    <td class="p-2">{{ $member->type }}</td>
                    <td class="p-2">{{ $member->birthdate }}</td>
                    <td class="p-2">{{ $member->licence }}</td>
                    <td class="p-2">
                        <button wire:click="edit({{ $member->id }})" class="text-blue-600">
                            Modifier
                        </button>

                        <button wire:click="delete({{ $member->id }})" class="text-red-600 ml-2">
                            Supprimer
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>