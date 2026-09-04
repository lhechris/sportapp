<div class="mx-auto max-w-xl space-y-6">
    <h2 class="text-2xl font-bold text-gray-900">
        {{ $news ? 'Modifier l’actualité' : 'Créer une actualité' }}
    </h2>

    @if (session('success'))
        <p class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800" role="status">
            {{ session('success') }}
        </p>
    @endif

    <form wire:submit="save" class="space-y-4 rounded-xl bg-white p-5 shadow">
        <div>
            <x-input-label for="title" value="Titre" />
            <x-text-input wire:model="title" id="title" name="title" type="text" class="mt-1 block w-full" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <div>
            <x-input-label for="description" value="Description" />
            <textarea wire:model="description" id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500" required></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <label for="active" class="flex items-center gap-2 text-sm text-gray-700">
            <input wire:model="active" id="active" name="active" type="checkbox" class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-500">
            Publier immédiatement
        </label>

        <x-primary-button>{{ $news ? 'Enregistrer les modifications' : 'Créer l’actualité' }}</x-primary-button>
    </form>
</div>
