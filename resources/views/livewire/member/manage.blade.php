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
        <div class="rounded-xl border border-gray-800 bg-gray-900 p-4 shadow">
            <h2 class="text-lg font-semibold text-white">{{ __('team.member.add') }}</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="new-member-prenom" value="{{ __('First name') }}" class="text-white" />
                    <x-text-input wire:model="newMember.prenom" id="new-member-prenom" type="text" class="mt-1 w-full bg-gray-900 text-white" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.prenom')" />
                </div>
                <div>
                    <x-input-label for="new-member-name" value="{{ __('Name') }}" class="text-white" />
                    <x-text-input wire:model="newMember.name" id="new-member-name" type="text" class="mt-1 w-full bg-gray-900 text-white" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.name')" />
                </div>
                <div>
                    <x-input-label for="new-member-type" value="{{ __('Type') }}" class="text-white" />
                    <select wire:model="newMember.type" id="new-member-type" class="mt-1 w-full rounded-md border-gray-700 bg-gray-900 text-white focus:border-yellow-500 focus:ring-yellow-500">
                        <option value="player">{{ __('team.player') }}</option>
                        <option value="coach">{{ __('team.coach') }}</option>
                        <option value="staff">{{ __('team.staff') }}</option>
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.type')" />
                </div>
                <div>
                    <x-input-label for="new-member-number" value="{{ __('team.member.number') }}" class="text-white" />
                    <x-text-input wire:model="newMember.numero" id="new-member-number" type="number" class="mt-1 w-full bg-gray-900 text-white" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.numero')" />
                </div>
                <div>
                    <x-input-label for="new-member-birthdate" value="{{ __('team.member.birthdate') }}" class="text-white" />
                    <x-text-input wire:model="newMember.birthdate" id="new-member-birthdate" type="date" class="mt-1 w-full bg-gray-900 text-white" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.birthdate')" />
                </div>
                <div>
                    <x-input-label for="new-member-licence" value="{{ __('team.member.licence') }}" class="text-white" />
                    <x-text-input wire:model="newMember.licence" id="new-member-licence" type="text" class="mt-1 w-full bg-gray-900 text-white" />
                    <x-input-error class="mt-1" :messages="$errors->get('newMember.licence')" />
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button wire:click="cancelCreate" type="button" class="rounded-lg border border-gray-500 px-3 py-2 font-semibold text-gray-200 hover:bg-gray-700">
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
                            <button wire:click="editMember({{ $index }})" type="button" class="rounded-lg bg-yellow-400 px-3 py-2 font-semibold text-black hover:bg-yellow-300">
                                {{ __('global.edit') }}
                            </button>
                            <button wire:click="delete({{ $member['id'] }})" type="button" class="ml-2 rounded-lg border border-red-400 px-3 py-2 font-semibold text-red-400 hover:bg-red-950">
                                {{ __('global.delete') }}
                            </button>
                        </td>
                    </tr>
                    @if($editingIndex === $index)
                        <tr wire:key="member-edit-{{ $member['id'] }}" class="border-b border-gray-800 bg-gray-800/70">
                            <td colspan="2" class="px-4 py-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <x-input-label for="member-prenom-{{ $member['id'] }}" value="{{ __('First name') }}" class="text-gray-200" />
                                        <x-text-input wire:model="members.{{ $index }}.prenom" id="member-prenom-{{ $member['id'] }}" type="text" class="mt-1 w-full bg-gray-900 text-white" />
                                        <x-input-error class="mt-1" :messages="$errors->get('members.' . $index . '.prenom')" />
                                    </div>
                                    <div>
                                        <x-input-label for="member-name-{{ $member['id'] }}" value="{{ __('Name') }}" class="text-gray-200" />
                                        <x-text-input wire:model="members.{{ $index }}.name" id="member-name-{{ $member['id'] }}" type="text" class="mt-1 w-full bg-gray-900 text-white" />
                                        <x-input-error class="mt-1" :messages="$errors->get('members.' . $index . '.name')" />
                                    </div>
                                    <div>
                                        <x-input-label for="member-type-{{ $member['id'] }}" value="{{ __('Type') }}" class="text-gray-200" />
                                        <select wire:model="members.{{ $index }}.type" id="member-type-{{ $member['id'] }}" class="mt-1 w-full rounded-md border-gray-700 bg-gray-900 text-white focus:border-yellow-500 focus:ring-yellow-500">
                                            <option value="player">{{ __('team.player') }}</option>
                                            <option value="coach">{{ __('team.coach') }}</option>
                                            <option value="staff">{{ __('team.staff') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="member-number-{{ $member['id'] }}" value="{{ __('team.member.number') }}" class="text-gray-200" />
                                        <x-text-input wire:model="members.{{ $index }}.numero" id="member-number-{{ $member['id'] }}" type="text" class="mt-1 w-full bg-gray-900 text-white" />
                                    </div>
                                    <div>
                                        <x-input-label for="member-birthdate-{{ $member['id'] }}" value="{{ __('Birthdate') }}" class="text-gray-200" />
                                        <x-text-input wire:model="members.{{ $index }}.birthdate" id="member-birthdate-{{ $member['id'] }}" type="date" class="mt-1 w-full bg-gray-900 text-white" />
                                    </div>
                                    <div>
                                        <x-input-label for="member-licence-{{ $member['id'] }}" value="{{ __('License') }}" class="text-gray-200" />
                                        <x-text-input wire:model="members.{{ $index }}.licence" id="member-licence-{{ $member['id'] }}" type="text" class="mt-1 w-full bg-gray-900 text-white" />
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end gap-2">
                                    <button wire:click="cancelEdit" type="button" class="rounded-lg border border-gray-500 px-3 py-2 font-semibold text-gray-200 hover:bg-gray-700">
                                        {{ __('global.cancel') }}
                                    </button>
                                    <button wire:click="saveMember({{ $index }})" type="button" class="rounded-lg bg-yellow-400 px-3 py-2 font-semibold text-black hover:bg-yellow-300">
                                        {{ __('global.save') }}
                                    </button>
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