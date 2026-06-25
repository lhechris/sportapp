@props(['member', 'option', 'memberOption'])


    @if($option->name === "opposition") 
        <button wire:click="setGameOption({{ $member->id }}, {{ $option->id }},'A')"
            class="px-2 py-1 rounded {{$memberOption?->value === 'A' ? 'bg-green-500 text-white' : 'bg-gray-200 text-black' }}">
            A
        </button>                                    
        <button wire:click="setGameOption({{ $member->id }}, {{ $option->id }}, 'B')"
            class="px-2 py-1 rounded {{$memberOption?->value === 'B' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black' }}">
            B
        </button>                                    
    @elseif ($option->name === "numero")
        <input
            type="number"
            class="w-full rounded border border-gray-300 px-2 py-1 text-black"
            value="{{ $memberOption?->value ?? '' }}"
            wire:change="setGameOption({{ $member->id }}, {{ $option->id }}, $event.target.value)"
        />
    @elseif ($option->name === "collation")
        <input
            type="checkbox"
            @checked($memberOption?->value === 'yes')
            wire:change="setGameOption({{ $member->id }}, {{ $option->id }}, $event.target.checked ? 'yes' : 'no')"
        />
    @else
        <input
            type="text"
            class="w-full rounded border border-gray-300 px-2 py-1 text-black"
            value="{{ $memberOption?->value }}"
            wire:change="setGameOption({{ $member->id }}, {{ $option->id }}, $event.target.value)"
        />
    @endif
