<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $game->titre }}</h1>
            <p class="mt-2 text-sm text-gray-600">
                {{ $game->formatdate() }}
            </p>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-700 text-yellow-300">
                    <p class="text-xs uppercase tracking-[0.16em] font-semibold text-yellow-200">{{ __('Address') }}</p>
                    <p class="mt-2 text-base font-semibold">{{ $game->location }}</p>
                </div>
                <div class="bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-700 text-yellow-300">
                    <p class="text-xs uppercase tracking-[0.16em] font-semibold text-yellow-200">{{ __('Rendezvous') }}</p>
                    <p class="mt-2 text-base font-semibold">{{ $game->rendezvous }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 w-full md:w-auto">
            <div class="bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-700 text-yellow-300">
                <p class="font-semibold text-sm text-yellow-200">{{ __('Present') }}</p>
                <p class="mt-2 text-2xl font-bold">{{ $members->where('pivot.availability', 'yes')->count() }}</p>
            </div>
            <div class="bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-700 text-yellow-300">
                <p class="font-semibold text-sm text-yellow-200">{{ __('Absent') }}</p>
                <p class="mt-2 text-2xl font-bold">{{ $members->where('pivot.availability', 'no')->count() }}</p>
            </div>
            <div class="bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-700 text-yellow-300">
                <p class="font-semibold text-sm text-yellow-200">{{ __('Selected') }}</p>
                <p class="mt-2 text-2xl font-bold">{{ $members->where('pivot.selected', true)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($members as $member)
            <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $member->prenom }}</p>
                        <p class="text-sm text-gray-500">{{ $member->pivot->availability ?? __('Not answered') }}</p>
                    </div>
                    @if($member->pivot->selected)
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">{{ __('Selected') }}</span>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    <button wire:click="setAvailability({{ $member->id }}, 'yes')"
                        class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $member->pivot->availability === 'yes' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __('Present') }}
                    </button>
                    <button wire:click="setAvailability({{ $member->id }}, 'no')"
                        class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $member->pivot->availability === 'no' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __('Absent') }}
                    </button>
                    <button wire:click="setAvailability({{ $member->id }}, 'maybe')"
                        class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $member->pivot->availability === 'maybe' ? 'bg-yellow-400 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __('Maybe') }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>

</div>