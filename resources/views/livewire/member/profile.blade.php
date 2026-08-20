<div  class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
        👤 {{ __('Profile') }}
    </h1>

    <!-- FORM -->
    <form wire:submit="save" class="bg-white p-4 rounded-xl shadow space-y-3 ">

        <div>
            <x-input-label for="prenom" :value="__('First name')" />
            <x-text-input wire:model="prenom" id="prenom" name="prenom" type="text" class="mt-1 block w-full" required autofocus autocomplete="prenom" />
            <x-input-error class="mt-2" :messages="$errors->get('prenom')" />
        </div>
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="licence" :value="__('Licence')" />
            <x-text-input wire:model="licence" id="licence" name="licence" type="text" class="mt-1 block w-full" required autocomplete="licence" />
            <x-input-error class="mt-2" :messages="$errors->get('licence')" />
        </div>

        <div>
            <x-input-label for="numero" :value="__('team.member.number')" />
            <x-text-input wire:model="numero" id="numero" name="numero" type="text" class="mt-1 block w-full" required autocomplete="numero" />
            <x-input-error class="mt-2" :messages="$errors->get('numero')" />
        </div>

        <div>
            <x-input-label for="birthdate" :value="__('Birthdate')" />
            <x-text-input wire:model="birthdate" id="birthdate" name="birthdate" type="date" class="mt-1 block w-full" required autocomplete="birthdate" />
            <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
        </div>

        <x-primary-button>
            {{ __('Update') }}
        </x-primary-button>

    </form>

    <div>
        <p>{{__('Number of matches played') }} : {{ count($member->games) }} </p>
        <p>{{__('Number of trainings') }} : {{ count($member->trainings) }} </p>
    </div>

</div>
