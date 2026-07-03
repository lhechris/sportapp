@props(['player','options'])

<div>
    <p>{{$player->prenom}}</p>
    <table class="text-sm text-left rtl:text-right text-body text-yellow-400 max-w-lg ">

        <tbody>
    @foreach($options as $option)
    @if($option->isDisplayStat())
        @php
            $memberOption = $player->gameOptions->firstWhere('game_option_id', $option->id);
        @endphp
    <tr class="odd:bg-gray-700 even:bg-gray-800 border-b border-default">
        <td class="px-2 sm:px-6 py-4">{{ $option->name }}</td>
        <td class="px-2 sm:px-6 py-4"><x-game-member-option-cell :member="$player" :option="$option" :value="$memberOption?->value" /></td>
    <tr>
    @endif
    @endforeach
        <tbody>
    </table>
</div>
