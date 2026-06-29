<div>
    <!-- COACHS -->
    <div>
        <h2 class="text-lg font-semibold mb-2">{{ __('Coaches') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            @forelse($team->coaches as $coach)
                <x-card title="{{ $coach->prenom }}" tag=" {{ $coach->licence ?? '.....' }}" />
            @empty
                <p class="text-gray-500">{{ __('No coach') }}</p>
            @endforelse

        </div>
    </div>


    <div class="flex gap-4 mb-2 mt-2">
        <h2 class="text-lg font-semibold mb-4">{{ __('Players') }}</h2>
        <a href="{{ route('team.members', ['team' => $team->id ]) }}" wire:navigate>
            <x-button>⚙️ {{ __('Edit') }}</x-button>
        </a>
    </div>

    <div class="flex gap-4">
        <table class="w-full text-sm text-left rtl:text-right text-body text-yellow-400">
            <thead class="bg-black border-b border-default">
                <tr>
                    <th scope="col" class="px-2 py-3 font-medium">{{ __('First name') }}</th>
                    <th scope="col" class="px-2 py-3 font-medium">{{ __('Licence') }}</th>
                    <th scope="col" class="px-2 py-3 font-medium">{{ __('Matchs') }}</th>
                    <th scope="col" class="px-2 py-3 font-medium">{{ __('Trainings') }}</th>
                    <th scope="col" class="px-2 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $player)
                <tr class="odd:bg-gray-800 even:bg-gray-900 border-b border-default">
                    <td class="px-2 py-4">{{ $player->prenom }}</td>
                    <td class="px-2 py-4">{{ $player->licence ?? '.....' }}</td>
                    <td class="px-2 py-4">{{ $player->games_count }}</td>
                    <td class="px-2 py-4">{{ $player->trainings_count }}</td>
                    <td class="px-2 py-4">
                    <button class="text-sm text-white hover:underline">
                        {{ __('View profile') }} →
                    </button>
                    </td>
                </tr>
                @empty
                <tr class="text-gray-500">{{ __('No players on the team') }}</tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
