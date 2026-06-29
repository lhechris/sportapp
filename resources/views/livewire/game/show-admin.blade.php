<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>
            @if($editingGame)
                <div class="space-y-3 bg-white p-4 rounded-xl shadow mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700">{{ __('Title') }}</label>
                        <input type="text" wire:model="gameTitle" class="w-full rounded border border-gray-300 px-3 py-2 text-black">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">{{ __('Date') }}</label>
                        <input type="datetime-local" wire:model="gameDate" class="w-full rounded border border-gray-300 px-3 py-2 text-black">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">{{ __('Location') }}</label>
                        <input type="text" wire:model="gameLocation" class="w-full rounded border border-gray-300 px-3 py-2 text-black">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">{{ __('Rendezvous') }}</label>
                        <input type="text" wire:model="gameRendezvous" class="w-full rounded border border-gray-300 px-3 py-2 text-black">
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="updateGame()" class="bg-green-600 text-white px-3 py-2 rounded font-semibold hover:bg-green-700">
                            {{ __('Save') }}
                        </button>
                        <button wire:click="toggleEditingGame()" class="bg-gray-600 text-white px-3 py-2 rounded font-semibold hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            @else
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $game->titre }}
                </h1>

                <p class="text-gray-600">
                    {{ $game->formatdate() }} • {{ $game->location }}
                </p>
                <p class="text-gray-900">Rendez-vous : {{ $game->rendezvous }}</p>
                <button wire:click="toggleEditingGame()" class="mt-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                    ✏️ {{ __('Edit') }}
                </button>
            @endif
        </div>

        <div class="flex gap-2">
            <!--<button wire:click="sendNotification()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-blue-700">
                {{ __('Send notification') }}
            </button>-->
            <a href="{{ route('team.show', ['team' => $game->team->id ]) }}" 
               class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
                {{ __('Back to team') }}
            </a>
        </div>

        <div class="bg-white p-3 rounded-xl shadow">
            {{ __('Selected') }}: {{ $members->where('pivot.selected', true)->count() }}
        </div>

    </div>

    <!-- LISTE -->
    <div class="flex flex-col lg:flex-row gap-4">
        <div>
            <table class="w-full text-sm text-left rtl:text-right text-body text-yellow-400 max-w-lg">
                <thead class="bg-black border-b border-default">
                    <tr>
                        <th scope="col" class="px-2 sm:px-6 py-3 font-bold">{{ __('First name') }}</th>
                        <th scope="col" class="px-2 sm:px-6 py-3 font-bold">{{ __('Availability') }}</th>
                        <th scope="col" class="px-2 sm:px-6 py-3 font-bold">{{ __('Selected') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                    <tr class="odd:bg-gray-700 even:bg-gray-800 border-b border-default">
                        <td class="px-2 sm:px-6 py-4">{{ $member->prenom }}</td>
                        <td class="px-2 sm:px-6 py-4 text-black">
                            <button wire:click="setAvailability({{ $member->id }}, 'yes')"
                                class="px-1 py-1 rounded {{ $member->pivot->availability === 'yes' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                                ✔️​
                            </button>

                            <button wire:click="setAvailability({{ $member->id }}, 'no')"
                                class="px-1 py-1 rounded {{ $member->pivot->availability === 'no' ? 'bg-red-500 text-white' : 'bg-gray-200' }}">
                            ❌​
                            </button>

                            <button wire:click="setAvailability({{ $member->id }}, 'maybe')"
                                class="px-1 py-1 rounded {{ $member->pivot->availability === 'maybe' ? 'bg-yellow-400' : 'bg-gray-200' }}">
                                ❓
                            </button>
                        </td>
                        <td class="px-2 sm:px-6 py-4">
                            @if($member->pivot->availability === 'yes')
                                <button wire:click="toggleSelection({{ $member->id }})"
                                    class="px-3 py-1 rounded font-semibold
                                    {{ $member->pivot->selected ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $member->pivot->selected ? __('Yes') : __('No') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <table class="text-sm text-left rtl:text-right text-body text-yellow-400 max-w-lg ">
                <thead class="bg-black border-b border-default">
                    <tr>
                        <th scope="col" class="px-2 sm:px-6 py-3 font-bold">{{ __('Member') }}</th>
                        @foreach($options as $option)
                            <th scope="col" class="px-2 sm:px-6 py-3 font-bold">{{ $option->label ?? ucfirst($option->name) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        @if($member->pivot->selected)
                        <tr class="odd:bg-gray-700 even:bg-gray-800 border-b border-default">
                            <td class="px-2 sm:px-6 py-4">{{ $member->prenom }}</td>
                            @foreach($options as $option)
                                @php
                                    $memberOption = $member->gameOptions->firstWhere('game_option_id', $option->id);
                                @endphp
                                <td class="px-2 sm:px-6 py-4">
                                    <x-game-member-option-cell :member="$member" :option="$option" :memberOption="$memberOption" />
                                </td>
                            @endforeach
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>


