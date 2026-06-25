<div>
    <div class="flex gap-4 mb-2">
        <h2 class="text-lg font-semibold mb-4">{{ __('Trainings') }}</h2>
        <a href="{{ route('team.trainings.create', ['team' => $team->id ]) }}" 
           class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
            ➕️{{ __('Create') }}
        </a>
    </div>

    <x-cards-scroll nextElementId="training-{{ $nextTrainingId }}" >
        @forelse($team->trainings as $training)
            <x-card title="{{$training->titre}}" 
                    id="training-{{ $training->id }}" 
                    href="{{ route('training.show', [ 'training' => $training->id]) }}"
                    description="{{ $training->formatdate() }}" >
            </x-card>
        @empty
            <p class="text-gray-500">{{ __('No training for the team') }}</p>
        @endforelse
    </x-cards-scroll>
</div>
