<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Actualités</h1>
        <button wire:click="create" type="button" class="rounded-lg bg-black px-4 py-2 font-semibold text-yellow-400">
            Nouvelle actualité
        </button>
    </div>

    <div class="overflow-x-auto rounded-xl bg-gray-900 p-4 shadow">
        <table class="w-full text-left text-sm text-white">
            <thead class="border-b border-gray-700 text-yellow-400">
                <tr>
                    <th class="px-3 py-2">Titre</th>
                    <th class="px-3 py-2">Statut</th>
                    <th class="px-3 py-2 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    <tr class="border-b border-gray-800">
                        <td class="px-3 py-3">{{ $item->title }}</td>
                        <td class="px-3 py-3">
                            {{ $item->active ? 'Publiée' : 'Désactivée' }}
                        </td>
                        <td class="px-3 py-3 text-right">
                            <button wire:click="edit({{ $item->id }})" type="button" class="font-semibold text-yellow-400 hover:text-yellow-300">
                                Modifier
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-3 py-4 text-gray-400">Aucune actualité.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <livewire:news.form :news="$editingNews" :key="'news-form-' . ($editingNews?->id ?? 'new')" />
</div>
