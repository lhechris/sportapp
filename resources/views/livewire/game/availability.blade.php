<div>
    @foreach($members as $member)

<div class="flex justify-between p-2 bg-white rounded">

    <span>{{ $member->name }}</span>

    <div class="space-x-2">
        <button wire:click="setAvailability({{ $member->id }}, 'yes')">✅</button>
        <button wire:click="setAvailability({{ $member->id }}, 'no')">❌</button>
        <button wire:click="setAvailability({{ $member->id }}, 'maybe')">❓</button>
    </div>

</div>

@endforeach

</div>
