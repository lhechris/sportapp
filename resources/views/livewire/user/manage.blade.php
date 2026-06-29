<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
        👤 {{ __('User management') }}
    </h1>

    <!-- FORM -->
    <form wire:submit="save" class="bg-white p-4 rounded-xl shadow space-y-3">

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

        @if(!$editingId)
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        @endif

        <div>
            <x-input-label for="role" :value="__('Role')" />
            <select wire:model="role" placeholder="{{ __('Email') }}"
                class="w-full border p-2 rounded">
                <option value="" >--</option>
                <option value="{{ \App\Models\User::ROLE_PLAYER }}" >{{ __('Player') }}</option>
                <option value="{{ \App\Models\User::ROLE_PARENT }}" >{{ __('Parent') }}</option>
                <option value="{{ \App\Models\User::ROLE_COACH }}" >{{ __('Coach') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <!-- MEMBERS -->
        <div>
            <p class="font-semibold mb-2">{{ __('Associated members') }}</p>

            <div class="space-y-2 max-h-60 overflow-y-auto border p-2 rounded">

                @foreach($members as $member)

                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded">

                        <span>
                            {{ $member->prenom }} {{ $member->name }} ({{ $member->type }})
                        </span>
                        <div class="flex items-center gap-2">
                            
                            <input type="checkbox"
                                wire:model="selectedMembers.{{ $member->id }}"
                                value="parent">

                            <select
                                wire:model="selectedMembers.{{ $member->id }}"
                                class="border rounded p-1 min-w-32"
                            >
                                <option value="">--</option>
                                <option value="{{ \App\Enums\MemberRelation::PARENT }}">{{ __('Parent') }}</option>
                                <option value="{{ \App\Enums\MemberRelation::SELF }}">{{ __('Self') }}</option>
                                <option value="{{ \App\Enums\MemberRelation::COACH }}">{{ __('Coach') }}</option>
                            </select>
                        </div>
                    </div>

                @endforeach

            </div>

        </div>

        <x-primary-button>
            {{ $editingId ? __('Update') : __('Create') }}
        </x-primary-button>

        @if(!$editingId)
        <x-button wire:click="invit" >
            {{ __('Generate an invitation') }}
        </x-button>
        <div>{{ $link }}</div>
        @endif

    </form>

    <!-- LIST -->
    <div class="space-y-3">

        @foreach($users as $user)

            <div class="bg-white p-4 rounded-xl shadow flex justify-between">

                <div>
                    <p class="font-semibold">{{ $user->firstname }} {{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>

                    <div class="text-xs text-gray-400 mt-1">
                        {{ __('Members') }} :
                        {{ $user->members->pluck('prenom')->join(', ') }}
                    </div>
                    @if($user->invitations->count()>0)
                    <div class="text-xs text-gray-400 mt-1">
                        {{ __('Invitations') }} :
                        {{ $user->invitations->pluck('token')->join(', ') }}                        
                    </div>
                    @endif
                </div>

                <div class="space-x-2">

                    <button wire:click="edit({{ $user->id }})"
                        class="text-blue-600">
                        {{ __('Edit') }}
                    </button>

                    <button wire:click="delete({{ $user->id }})"
                        class="text-red-600">
                        {{ __('Delete') }}
                    </button>

                </div>

            </div>

        @endforeach

    </div>

</div>