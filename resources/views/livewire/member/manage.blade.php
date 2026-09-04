<div class="space-y-6 p-0 sm:p-6">
    <h1 class="text-2xl font-bold text-gray-900">
        {{ __('Manage members') }}
    </h1>

    @if (session('success'))
        <p class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800" role="status">
            {{ session('success') }}
        </p>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900 shadow">
        <table class="w-full text-left text-sm text-white">
            <thead class="bg-black text-xs uppercase tracking-wide text-yellow-400">
                <tr>
                    <th class="px-4 py-3">{{ __('Member') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
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
                                Edit
                            </button>
                            <button wire:click="delete({{ $member['id'] }})" type="button" class="ml-2 rounded-lg border border-red-400 px-3 py-2 font-semibold text-red-400 hover:bg-red-950">
                                Delete
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
                                            <option value="player">{{ __('Player') }}</option>
                                            <option value="coach">{{ __('Coach') }}</option>
                                            <option value="staff">{{ __('Staff') }}</option>
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
                                        Cancel
                                    </button>
                                    <button wire:click="saveMember({{ $index }})" type="button" class="rounded-lg bg-yellow-400 px-3 py-2 font-semibold text-black hover:bg-yellow-300">
                                        Save
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