@props(['member', 'option', 'value'])

    @if($option->type === \App\Models\GameOption::TYPE_OPPOSITION) 
        <button wire:click="setGameOption({{ $member->id }}, {{ $option->id }},'A')"
            class="px-2 py-1 rounded {{$value === 'A' ? 'bg-green-500 text-white' : 'bg-gray-200 text-black' }}">
            A
        </button>                                    
        <button wire:click="setGameOption({{ $member->id }}, {{ $option->id }}, 'B')"
            class="px-2 py-1 rounded {{$value === 'B' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black' }}">
            B
        </button>                                    
    @elseif ($option->type === \App\Models\GameOption::TYPE_NUM)
        <input
            type="number"
            class="w-full max-w-20 rounded border border-gray-300 px-2 py-1 text-black"
            value="{{ $value ?? '' }}"
            wire:change="setGameOption({{ $member->id }}, {{ $option->id }}, $event.target.value)"
        />
    @elseif ($option->type === \App\Models\GameOption::TYPE_CHECKBOX)
        <input
            type="checkbox"
            @checked($value === 'yes')
            wire:change="setGameOption({{ $member->id }}, {{ $option->id }}, $event.target.checked ? 'yes' : 'no')"
        />
    @else
        <input
            type="text"
            class="w-full rounded border border-gray-300 px-2 py-1 text-black"
            value="{{ $value }}"
            wire:change="setGameOption({{ $member->id }}, {{ $option->id }}, $event.target.value)"
        />
    @endif
