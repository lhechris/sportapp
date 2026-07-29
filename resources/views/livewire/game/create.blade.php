<div class="max-w-xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
        {{ __('team.game.new_message', ['team' => $team->name]) }}
    </h1>

    <form wire:submit="save" class="bg-white p-4 rounded-xl shadow space-y-3">

        <div>
            <x-input-label for="titre" :value="__('global.title')" />
            <x-text-input wire:model="titre" id="titre" name="titre" type="text" class="mt-1 block w-full" required autofocus autocomplete="titre" />
            <x-input-error class="mt-2" :messages="$errors->get('titre')" />
        </div>

        <div>
            <x-input-label for="date" :value="__('team.game.date')" />
            <x-text-input wire:model="date" id="date" name="date" type="datetime-local" class="mt-1 block w-full" required autofocus autocomplete="date" />
            <x-input-error class="mt-2" :messages="$errors->get('date')" />
        </div>

        <div>
            <x-input-label for="numero" :value="__('team.game.numero')" />
            <x-text-input wire:model="numero" id="numero" name="numero" type="text" class="mt-1 block w-full" autofocus autocomplete="numero" />
            <x-input-error class="mt-2" :messages="$errors->get('numero')" />
        </div>

        <div>
            <x-input-label for="place_id" :value="__('team.game.location')" />
            <select wire:model="place_id" class="w-full rounded border border-gray-300 px-3 py-2 text-black">
                <option value="">-- {{ __('team.game.select_place') }} --</option>
                @foreach($places as $place)
                    <option value="{{ $place->id }}">{{ $place->name }} {{ $place->address ? '— ' . $place->address : '' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="rendezvous" :value="__('team.game.rendezvous')" />
            <x-text-input wire:model="rendezvous" id="rendezvous" name="rendezvous" type="text" class="mt-1 block w-full" required autofocus autocomplete="rendezvous" />
            <x-input-error class="mt-2" :messages="$errors->get('rendezvous')" />
        </div>

        <div>
            <x-input-label for="score" :value="__('team.game.score')" />
            <x-text-input wire:model="score" id="score" name="score" type="text" class="mt-1 block w-full" autofocus autocomplete="score" />
            <x-input-error class="mt-2" :messages="$errors->get('score')" />
        </div>

        <div>
            <x-input-label for="commentaire" :value="__('team.game.comment')" />
            <x-text-input wire:model="commentaire" id="commentaire" name="commentaire" type="text" class="mt-1 block w-full" autofocus autocomplete="commentaire" />
            <x-input-error class="mt-2" :messages="$errors->get('commentaire')" />
        </div>

        <x-primary-button >
            {{ __('team.game.create') }}
        </x-primary-button>

    </form>

</div>