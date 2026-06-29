<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
        {{ __('Registration') }}
    </h1>

    <form wire:submit='store' class="bg-white p-4 rounded-xl shadow space-y-3">
        
        <div>
            <x-input-label for="firstname" :value="__('First name')" />
            <x-text-input wire:model="firstname" id="firstname" name="firstname" type="text" class="mt-1 block w-full" required autofocus autocomplete="firstname" />
            <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
        </div>
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

    <a href="{{ url('/auth/google') }}"
       class="flex items-center justify-center gap-3 bg-white border border-gray-300 px-4 py-2 rounded-xl shadow hover:bg-gray-100 transition">

        <img src="/images/google-logo.png" class="w-5 h-5">
        <span class="font-medium text-gray-800">
            Se connecter avec Google
        </span>
    </a>

        <x-primary-button>{{ __('Finalize my account') }}</x-primary-button>

    </form>

</div>