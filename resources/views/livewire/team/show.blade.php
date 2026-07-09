<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                🏀 {{ $team->name }}
            </h1>
            <div class="text-gray-600 flex gap-2" >
                <p>{{ $team->players()->count() }} {{ __('team.players') }}</p>
                <p>{{ $team->staffs()->count() }} {{ __('team.staffs') }}</p>
                <p>{{ $team->coaches()->count() }} {{ __('team.coaches') }}</p>
                <p>{{ $team->games()->count() }} {{ __('team.matches') }}</p>
                <p>{{ $team->trainings()->count() }} {{ __('team.trainings') }}</p>
            </div>
        </div>

    </div>

    <div class="mt-6">
        <nav class="flex flex-wrap gap-2 bg-white rounded-full border border-gray-200 p-2 shadow-sm">
            <button wire:click="setTab('games')"
                    class="px-2 sm:px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'games' ? 'bg-gray-500 text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                <img src="{{ asset('images/basketball.png') }}" alt="{{ __('team.matches') }}"/>
                <p class="text-xs">{{ __('team.matches') }}</p>
            </button>
            <button wire:click="setTab('members')"
                    class="px-2 sm:px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'members' ? 'bg-gray-500 text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                <img src="{{ asset('images/utilisateurs.png') }}" alt="{{ __('team.number') }}"/>
                <p class="text-xs">{{ __('team.number') }}</p>
            </button>
            <button wire:click="setTab('trainings')"
                    class="px-2 sm:px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'trainings' ? 'bg-gray-500 text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                <img src="{{ asset('images/exercice.png') }}" alt="{{ __('team.trainings') }}"/>
                <p class="text-xs hidden sm:block">{{ __('team.trainings') }}</p><p class="text-xs sm:hidden">{{ __('team.trainings_cut') }}</p>
            </button>
            <button wire:click="setTab('selections')"
                    class="px-2 sm:px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'selections' ? 'bg-gray-500 text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                <img src="{{ asset('images/selection.png') }}" alt="{{ __('team.selections') }}"/>
                <p class="text-xs">{{ __('team.selections') }}</p>
            </button>
            <div class="flex flex-col">
                <button wire:click="setTab('parameters')"
                        class="px-2 sm:px-4 py-2 rounded-full text-sm font-semibold {{ $activeTab === 'parameters' ? 'bg-gray-500 text-white' : 'bg-transparent text-gray-700 hover:bg-gray-100' }}">
                    <img src="{{ asset('images/parametres.png') }}" alt="{{ __('global.parameters') }}"/>
                    <p class="text-xs hidden sm:block">{{ __('global.parameters') }}</p></p><p class="text-xs sm:hidden">{{ __('global.parameters_cut') }}</p>
                </button>
                
            </div>
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

    <!-- SELECTIONS -->
    @if($activeTab === 'selections')
        <livewire:team.selections :team="$team" />
    @endif

    <!-- PARAMETERS -->
    @if($activeTab === 'parameters')
        <livewire:team.parameters :team="$team" />
    @endif


</div>
