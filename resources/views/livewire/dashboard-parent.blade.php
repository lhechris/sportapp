<div class="space-y-6">
    <livewire:InstallPrompt />
    <x-button
         x-show="!{{$hasSubscription ? 'true' : 'false'}}"
         onclick="subscribeToPush()" > S'abonner</x-button>
    <!-- MEMBRES -->
@forelse($members as $member)
    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">
        <h2 class="text-white font-bold mb-4">
            {{ __('team.presenceof') }} {{ $member->prenom }} {{ __('team.upcomingmatches') }}
        </h2>

        <x-cards-scroll nextElementId="game-{{$member->id}}-{{$member->nextGameId}}" >

            @forelse($member->combined as $game)
                @if($game instanceof \App\Models\Event)
                <livewire:event.card :event="$game" :member="$member"></livewire:event.card>                
                @elseif($game instanceof \App\Models\Game)
                <livewire:game.card :game="$game" :member="$member"></livewire:game.card>                
                @endif 
            @empty
                <p class="text-gray-500">{{ __('team.game.no') }}</p>
            @endforelse
        </x-cards-scroll>
    </div>
@empty
        <p class="text-gray-500">{{ __('team.nomembers') }}</p>
@endforelse
</div>