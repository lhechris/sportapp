<div class="p-0 sm:p-6 rounded shadow">

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
    <table class="w-full border text-left">
        <thead>
            <tr class="bg-gray-100">
                <th class="sm:p-2">{{ __('Name') }}</th>
                <th class="sm:p-2">{{ __('Type') }}</th>
                <th class="hidden sm:p-2 sm:block">{{ __('Birthdate') }}</th>
                <th class="sm:p-2">{{ __('License') }}</th>
                <th class="sm:p-2">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
                <tr class="border-t">
                    <td class="sm:p-2">{{ $member->prenom }} {{ $member->name }}</td>
                    <td class="sm:p-2">{{ $member->type }}</td>
                    <td class="hidden sm:p-2 sm:block">{{ $member->birthdate }}</td>
                    <td class="sm:p-2">{{ $member->licence }}</td>
                    <td class="sm:p-2">
                        <button wire:click="edit({{ $member->id }})" class="text-blue-600">
                            📝​
                        </button>

                        <button wire:click="delete({{ $member->id }})" class="text-red-600 ml-2">
                            ​🗑️​
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>