<div class="max-w-xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
        {{ __('New training for team') }} {{ $team->name }}
    </h1>

    <div class="bg-white p-6 rounded-2xl shadow space-y-4">

        <input type="text"
               wire:model="titre"
               placeholder="{{ __('Title') }}"
               class="w-full border p-2 rounded">

        <input type="date"
               wire:model="date"
               class="w-full border p-2 rounded">

        <input type="text"
               wire:model="location"
               placeholder="{{ __('Location') }}"
               class="w-full border p-2 rounded">

        <button wire:click="save"
                class="bg-black text-yellow-400 px-4 py-2 rounded-lg">
            {{ __('Create') }}
        </button>

    </div>

</div>