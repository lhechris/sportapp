<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
        👤 Gestion des utilisateurs
    </h1>

    <!-- FORM -->
    <div class="bg-white p-4 rounded-xl shadow space-y-3">

        <input wire:model="name" placeholder="Nom"
               class="w-full border p-2 rounded">

        <input wire:model="email" placeholder="Email"
               class="w-full border p-2 rounded">

        @if(!$editingId)
            <input wire:model="password" type="password"
                   placeholder="Mot de passe"
                   class="w-full border p-2 rounded">
        @endif

        <div>
            <p class="font-semibold mb-2">Role</p>
            <select wire:model="role" placeholder="Email"
                class="w-full border p-2 rounded">
                <option value="{{ \App\Models\User::ROLE_PLAYER }}" >Joueur</option>
                <option value="{{ \App\Models\User::ROLE_PARENT }}" >Parent</option>
                <option value="{{ \App\Models\User::ROLE_COACH }}" >Coach</option>
            </select>
        </div>
        <!-- MEMBERS -->
        <div>
            <p class="font-semibold mb-2">Associé aux membres</p>

            <div class="space-y-2 max-h-60 overflow-y-auto border p-2 rounded">

                @foreach($members as $member)

                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded">

                        <span>
                            {{ $member->prenom }} {{ $member->name }} ({{ $member->type }})
                        </span>
                        <div class="flex items-center gap-2">
                            
                            <input type="checkbox"
                                wire:model="selectedMembers.{{ $member->id }}"
                                value="parent">

                            <select
                                wire:model="selectedMembers.{{ $member->id }}"
                                class="border rounded p-1 min-w-32"
                            >
                                <option value="">--</option>
                                <option value="{{ \App\Enums\MemberRelation::PARENT }}">Parent</option>
                                <option value="{{ \App\Enums\MemberRelation::SELF }}">Moi</option>
                                <option value="{{ \App\Enums\MemberRelation::COACH }}">Coach</option>
                            </select>
                        </div>
                    </div>

                @endforeach

            </div>

        </div>


        <button wire:click="save"
                class="bg-black text-yellow-400 px-4 py-2 rounded">
            {{ $editingId ? 'Mettre à jour' : 'Créer' }}
        </button>

    </div>

    <!-- LIST -->
    <div class="space-y-3">

        @foreach($users as $user)

            <div class="bg-white p-4 rounded-xl shadow flex justify-between">

                <div>
                    <p class="font-semibold">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>

                    <div class="text-xs text-gray-400 mt-1">
                        Membres :
                        {{ $user->members->pluck('prenom')->join(', ') }}
                    </div>
                </div>

                <div class="space-x-2">

                    <button wire:click="edit({{ $user->id }})"
                        class="text-blue-600">
                        Modifier
                    </button>

                    <button wire:click="delete({{ $user->id }})"
                        class="text-red-600">
                        Supprimer
                    </button>

                </div>

            </div>

        @endforeach

    </div>

</div>