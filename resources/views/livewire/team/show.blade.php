<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                🏀 {{ $team->name }}
            </h1>
            <div class="text-gray-600 flex gap-2" >
                <p>{{ $team->players()->count() }} {{ __('Players') }}</p>
                <p>{{ $team->staffs()->count() }} {{ __('Staffs') }}</p>
                <p>{{ $team->coaches()->count() }} {{ __('Coaches') }}</p>
                <p>{{ $team->games()->count() }} {{ __('Matches') }}</p>
                <p>{{ $team->trainings()->count() }} {{ __('Trainings') }}</p>
            </div>
        </div>

    </div>

    <div class="mt-6">
        <nav class="flex flex-wrap gap-2 bg-white rounded-full border border-gray-200 p-2 shadow-sm">
            <button wire:click="setTab('members')"
                    class="px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'members' ? 'bg-black text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                {{ __('Team numbers') }}
            </button>
            <button wire:click="setTab('games')"
                    class="px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'games' ? 'bg-black text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                {{ __('Matches') }}
            </button>
            <button wire:click="setTab('trainings')"
                    class="px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'trainings' ? 'bg-black text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                {{ __('Trainings') }}
            </button>
        </nav>
    </div>

    <!-- JOUEURS -->
    @if($activeTab === 'members')
        <livewire:team.members :team="$team" />
    @endif

    <!-- MATCHS -->
    @if($activeTab === 'games')
        <livewire:team.games :team="$team" />
    @endif

    <!-- ENTRAINEMENTS -->
    @if($activeTab === 'trainings')
        <livewire:team.trainings :team="$team" />
    @endif

</div>
