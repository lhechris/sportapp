<div>
    @foreach($players as $player)

@if($player->pivot->availability === 'yes')

<div class="flex justify-between p-2 bg-white rounded">

    <span>{{ $player->prenom }}</span>

    <button wire:click="toggleSelection({{ $player->id }})"
        class="{{ $player->pivot->selected ? 'bg-green-500' : 'bg-gray-300' }} px-2 py-1 rounded">
        {{ $player->pivot->selected ? 'Sélectionné' : 'Choisir' }}
    </button>

</div>

@endif

@endforeach
</div>
