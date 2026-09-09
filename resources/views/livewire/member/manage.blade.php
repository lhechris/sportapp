<div class="space-y-6 p-0 sm:p-6">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ __('team.member.manage_members') }}
        </h1>
        @if(!$creating)
            <button wire:click="startCreate" type="button" class="rounded-lg bg-yellow-400 px-3 py-2 font-semibold text-black hover:bg-yellow-300">
                {{ __('team.member.add') }}
            </button>
        @endif
    </div>

    @if (session('success'))
        <p class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800" role="status">
            {{ session('success') }}
        </p>
    @endif

    @if($creating)
        <div class="rounded-xl border border-gray-800 bg-yellow-100 p-4 shadow text-black">
            <h2 class="text-lg font-semibold ">{{ __('team.member.add') }}</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="new-member-prenom" value="{{ __('user.firstname') }}" />
                    <x-text-input wire:model="newMember.prenom" id="new-member-prenom" type="text" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.prenom')" />
                </div>
                <div>
                    <x-input-label for="new-member-name" value="{{ __('user.name') }}" />
                    <x-text-input wire:model="newMember.name" id="new-member-name" type="text" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.name')" />
                </div>
                <div>
                    <x-input-label for="new-member-type" value="{{ __('team.param.type') }}"  />
                    <select wire:model="newMember.type" id="new-member-type" class="mt-1 w-full rounded-md border-gray-700  bg-yellow-200 focus:border-yellow-500 focus:ring-yellow-500">
                        <option value="player">{{ __('team.player') }}</option>
                        <option value="coach">{{ __('team.coach') }}</option>
                        <option value="staff">{{ __('team.staff') }}</option>
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.type')" />
                </div>
                <div>
                    <x-input-label for="new-member-number" value="{{ __('team.member.number') }}"  />
                    <x-text-input wire:model="newMember.numero" id="new-member-number" type="number" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.numero')" />
                </div>
                <div>
                    <x-input-label for="new-member-birthdate" value="{{ __('team.member.birthdate') }}"  />
                    <x-text-input wire:model="newMember.birthdate" id="new-member-birthdate" type="date" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.birthdate')" />
                </div>
                <div>
                    <x-input-label for="new-member-licence" value="{{ __('team.member.licence') }}"  />
                    <x-text-input wire:model="newMember.licence" id="new-member-licence" type="text" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.licence')" />
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button wire:click="cancelCreate" type="button" class="rounded-lg border border-gray-500 px-3 py-2 font-semibold text-gray-500 hover:bg-gray-700">
                    {{ __('global.cancel') }}
                </button>
                <button wire:click="createMember" type="button" class="rounded-lg bg-yellow-400 px-3 py-2 font-semibold text-black hover:bg-yellow-300">
                    {{ __('global.save') }}
                </button>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900 shadow">
        <table class="w-full text-left text-sm text-white">
            <thead class="bg-black text-xs uppercase tracking-wide text-yellow-400">
                <tr>
                    <th class="px-4 py-3">{{ __('team.members') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('global.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $index => $member)
                    <tr wire:key="member-{{ $member['id'] }}" class="border-b border-gray-800 last:border-0">
                        <td class="px-4 py-4 font-semibold">
                            {{ $member['prenom'] }} {{ $member['name'] }}
                        </td>
                        <td class="px-4 py-4 text-right">
                            <button wire:click="editMember({{ $index }})" type="button" class="ml-2 rounded-lg border border-yellow-400 bg-yellow-300 px-3 py-2 font-semibold text-black hover:bg-yellow-300">
                                <img src="{{ asset('images/parametres.png') }}" class="max-w-4"/>
                            </button>
                            <button wire:click="delete({{ $member['id'] }})" wire:confirm="{{ __('Confirmer la suppression de ce membre ?') }}" type="button" class="ml-2 rounded-lg border border-red-400 px-3 py-2 font-semibold text-red-400 hover:bg-red-950">
                                <img src="{{ asset('images/supprimer.png') }}" class="max-w-4" />
                            </button>
                        </td>
                    </tr>
                    @if($editingIndex === $index)
                        <tr wire:key="member-edit-{{ $member['id'] }}" >
                            <td colspan="2">
                                <div class="px-4 py-4 m-2 border border-black bg-yellow-100 text-black">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <x-input-label for="member-prenom-{{ $member['id'] }}" value="{{ __('user.firstname') }}" class="text-gray-200" />
                                            <x-text-input wire:model="members.{{ $index }}.prenom" id="member-prenom-{{ $member['id'] }}" type="text" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                                            <x-input-error class="mt-1" :messages="$errors->get('members.' . $index . '.prenom')" />
                                        </div>
                                        <div>
                                            <x-input-label for="member-name-{{ $member['id'] }}" value="{{ __('user.name') }}" class="text-gray-200" />
                                            <x-text-input wire:model="members.{{ $index }}.name" id="member-name-{{ $member['id'] }}" type="text" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                                            <x-input-error class="mt-1" :messages="$errors->get('members.' . $index . '.name')" />
                                        </div>
                                        <div>
                                            <x-input-label for="member-type-{{ $member['id'] }}" value="{{ __('Type') }}" class="text-gray-200" />
                                            <select wire:model="members.{{ $index }}.type" id="member-type-{{ $member['id'] }}" class="mt-1 w-full rounded-md border-gray-700 bg-yellow-200 focus:border-yellow-500 focus:ring-yellow-500">
                                                <option value="player">{{ __('team.player') }}</option>
                                                <option value="coach">{{ __('team.coach') }}</option>
                                                <option value="staff">{{ __('team.staff') }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="member-number-{{ $member['id'] }}" value="{{ __('team.member.number') }}" class="text-gray-200" />
                                            <x-text-input wire:model="members.{{ $index }}.numero" id="member-number-{{ $member['id'] }}" type="text" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                                        </div>
                                        <div>
                                            <x-input-label for="member-birthdate-{{ $member['id'] }}" value="{{ __('team.member.birthdate') }}" class="text-gray-200" />
                                            <x-text-input wire:model="members.{{ $index }}.birthdate" id="member-birthdate-{{ $member['id'] }}" type="date" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                                        </div>
                                        <div>
                                            <x-input-label for="member-licence-{{ $member['id'] }}" value="{{ __('team.member.licence') }}" class="text-gray-200" />
                                            <x-text-input wire:model="members.{{ $index }}.licence" id="member-licence-{{ $member['id'] }}" type="text" class="mt-1 w-full border-gray-700 bg-yellow-200" />
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button wire:click="cancelEdit" type="button" class="rounded-lg border border-gray-500 px-3 py-2 font-semibold text-gray-500 hover:bg-gray-700">
                                            {{ __('global.cancel') }}
                                        </button>
                                        <button wire:click="saveMember({{ $index }})" type="button" class="rounded-lg bg-yellow-400 px-3 py-2 font-semibold text-black hover:bg-yellow-300">
                                            {{ __('global.save') }}
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">{{ __('No members found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>