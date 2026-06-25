<div class="p-6 rounded shadow">

    <h1 class="text-xl font-bold mb-4">
        {{ __('Manage members') }}
    </h1>

    <!-- FORM -->
    <div class="mb-6 space-y-2">

        <input wire:model="name" placeholder="{{ __('Name') }}" class="border p-2 w-full">

        <select wire:model="type" class="border p-2 w-full">
            <option value="player">{{ __('Player') }}</option>
            <option value="coach">{{ __('Coach') }}</option>
            <option value="staff">{{ __('Staff') }}</option>
        </select>

        <input wire:model="prenom" placeholder="{{ __('First name') }}" class="border p-2 w-full">

        <input type="date" wire:model="birthdate" class="border p-2 w-full">

        <input wire:model="licence" placeholder="{{ __('License') }}" class="border p-2 w-full">

        <div>
            <button wire:click="save" class="bg-blue-500 text-white px-4 py-2">
                {{ $editingId ? __('Update') : __('Create') }}
            </button>

            <button wire:click="resetForm" class="ml-2 text-gray-600">
                {{ __('Reset') }}
            </button>
        </div>

    </div>

    <!-- LIST -->
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2">{{ __('Name') }}</th>
                <th class="p-2">{{ __('First name') }}</th>
                <th class="p-2">{{ __('Type') }}</th>
                <th class="p-2">{{ __('Birthdate') }}</th>
                <th class="p-2">{{ __('License') }}</th>
                <th class="p-2">{{ __('Actions') }}</th>
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
                            {{ __('Edit') }}
                        </button>

                        <button wire:click="delete({{ $member->id }})" class="text-red-600 ml-2">
                            {{ __('Delete') }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>