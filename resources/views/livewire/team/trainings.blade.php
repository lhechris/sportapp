<div>
    <div class="flex gap-4 mb-2">
        <h2 class="text-lg font-semibold mb-4">{{ __('team.trainings') }}</h2>
        <a href="{{ route('team.trainings.create', ['team' => $team->id ]) }}" >
            <x-button>➕️{{ __('global.add') }}</x-button>
        </a>
    </div>

    <x-cards-scroll nextElementId="training-{{ $nextTrainingId }}" >
        @forelse($trainings as $training)
            <x-card title="{{$training->titre}}" 
                    id="training-{{ $training->id }}" 
                    href="{{ route('training.show', [ 'training' => $training->id]) }}"
                    description="{{ $training->formatdate() }}" >
                    <p>{{ $training->members_count }} {{ __('team.players')}}</p>
            </x-card>
        @empty
            <p class="text-gray-500">{{ __('team.training.no') }}</p>
        @endforelse
    </x-cards-scroll>
</div>
