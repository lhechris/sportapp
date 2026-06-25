<div class="p-6 bg-white rounded shadow">

    <h1 class="text-xl font-bold mb-4">
        {{ __('Manage players') }}
    </h1>

    <!-- FORMULAIRE -->
    <div class="mb-6">
        <input wire:model="name" placeholder="{{ __('Name') }}" class="border p-2 w-full mb-2">

        <input wire:model="email" placeholder="{{ __('Email') }}" class="border p-2 w-full mb-2">

        @if(!$editingId)
            <input wire:model="password" type="password" placeholder="{{ __('Password') }}" class="border p-2 w-full mb-2">
        @endif


        <select wire:model="parent_id">
            <option value="">{{ __('-- Parent --') }}</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
        </select>


        <button wire:click="save" class="bg-blue-500 text-white px-4 py-2">
            {{ $editingId ? __('Update') : __('Create') }}
        </button>

        <button wire:click="resetForm" class="ml-2 text-gray-600">
            {{ __('Reset') }}
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
                            {{ __('Edit') }}
                        </button>

                        <button wire:click="delete({{ $player->id }})" class="text-red-600 ml-2">
                            {{ __('Delete') }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
