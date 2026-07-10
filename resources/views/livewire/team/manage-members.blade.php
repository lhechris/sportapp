<div class="p-6 rounded shadow">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ __('team.member.update') }} {{ $team->name }}
            </h1>
        </div>

        <a href="{{ route('team.show', ['team' => $team->id ]) }}" 
           class="bg-black text-white px-4 py-2 rounded-xl font-semibold hover:bg-gray-800">
            {{ __('team.back') }}
        </a>

    </div>

    <!-- RECHERCHE -->
    <input 
        type="text"
        wire:model.live="search"
        placeholder="{{ __('team.member.search') }}"
        class="border p-2 w-full mb-4"
    >

    <!-- RESULTATS -->
    @foreach($results as $member)
        <div class="flex justify-between border p-2 mb-2">

            <span>
                {{ $member->prenom }} ({{ $member->type }})
            </span>

            <div class="space-x-2">
                <button wire:click="addMember({{ $member->id }}, 'player')"
                    class="bg-green-500 text-white px-2 py-1">
                    {{ __('team.player') }}
                </button>

                <button wire:click="addMember({{ $member->id }}, 'coach')"
                    class="bg-blue-500 text-white px-2 py-1">
                    {{ __('team.coach') }}
                </button>
            </div>

        </div>
    @endforeach

    <!-- LISTE ACTUELLE -->
    <h2 class="mt-6 font-bold">{{ __('team.number') }}</h2>


    <table class="w-full text-sm text-left rtl:text-right text-body text-yellow-400">
        <thead class="bg-black border-b border-default">
            <tr>
                <th scope="col" class="px-6 py-3 font-bold">{{ __('global.firstname') }}</th>
                <th scope="col" class="px-6 py-3 font-bold">{{ __('team.role') }}</th>
                <th scope="col" class="px-6 py-3 font-bold"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr class="odd:bg-gray-700 even:bg-gray-800 border-b border-default">
                <td class="px-6 py-4">{{ $member->prenom }}</td>
                <td class="px-6 py-4">{{ $member->type }}</td>
                <td class="px-6 py-4">
                    <button wire:click="removeMember({{ $member->id }})" class="text-red-600">
                    {{ __('global.remove') }}
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>