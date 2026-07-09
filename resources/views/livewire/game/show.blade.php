<div class="space-y-6 mb-4">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $game->titre }}</h1>
            <p class="mt-2 text-sm text-gray-600">
                {{ $game->formatdate() }}
            </p>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-700 text-yellow-300">
                    <p class="text-xs uppercase tracking-[0.16em] font-semibold text-yellow-200">{{ __('team.game.location') }}</p>
                    <div class="flex gap-2" >
                        <p class="mt-2 text-base font-semibold">{{ $game->place->address }}</p>
                        @if($game->place !==NULL)                        
                        <livewire:itineraire
                                :lat="$game->place->lat"
                                :lng="$game->place->lng"
                                label="$game->place->name"/>
                        @endif
                    </div>
                </div>
                <div class="bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-700 text-yellow-300">
                    <p class="text-xs uppercase tracking-[0.16em] font-semibold text-yellow-200">{{ __('team.game.rendezvous') }}</p>
                    <p class="mt-2 text-base font-semibold">{{ $game->rendezvous }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($members as $member)
            <div class="bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col gap-4 text-yellow-300">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-yellow-200">{{ $member->prenom }}</p>
                    </div>
                    @if($member->pivot->selected)
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">{{ __('team.game.selected') }}</span>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    <button wire:click="setAvailability({{ $member->id }}, 'yes')"
                        class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $member->pivot->availability === 'yes' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{__('team.game.present')}}
                    </button>
                    <button wire:click="setAvailability({{ $member->id }}, 'no')"
                        class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $member->pivot->availability === 'no' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __('team.game.absent') }}
                    </button>
                    <button wire:click="setAvailability({{ $member->id }}, 'maybe')"
                        class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $member->pivot->availability === 'maybe' ? 'bg-yellow-400 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __('team.game.maybe') }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col gap-2 bg-gray-800 border-2 border-yellow-500 rounded-xl p-4 text-yellow-200">
        <p>{{ __('team.game.list_selected') }}:</p>
        <div class="flex gap-2">
            @foreach($players as $player)
            @if($player->pivot->selected)
            <p>{{ $player->prenom }}</p>
            @endif
            @endforeach
        </div>
    </div>

</div>