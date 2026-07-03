<div>
    <table class="w-full text-left border-collapse text-sm">
        <thead>
            <tr class="bg-black text-yellow-400">
                <th class="p-2">{{ __('global.name') }}</th>
                <th class="p-2">{{ __('team.param.type') }}</th>
                <th class="p-2">{{ __('team.param.order') }}</th>
                <th class="p-2">{{ __('team.param.display') }}</th>
                <th class="p-2"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($gameoptions as $key => $option)
                <tr wire:key="game-option-{{ $key }}" class="border-b">
                    <td class="p-1">
                        <input type="text" wire:model="gameoptions.{{ $key }}.name"
                            class="w-full text-sm rounded border-gray-300 focus:border-yellow-400 focus:ring-yellow-400" />
                        @error("gameoptions.{$key}.name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </td>
                    <td class="p-1">
                        <select wire:model="gameoptions.{{ $key }}.type"
                            class="w-full rounded border-gray-300 focus:border-yellow-400 focus:ring-yellow-400 text-sm">
                            <option value="">{{ __('team.param.select') }}</option>
                            @foreach($typeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error("gameoptions.{$key}.type") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </td>
                    <td class="p-1">
                        <input type="number" wire:model="gameoptions.{{ $key }}.order"
                            class="w-20 rounded border-gray-300 focus:border-yellow-400 focus:ring-yellow-400 text-sm" />
                        @error("gameoptions.{$key}.order") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </td>
                    <td class="p-1">
                        <select wire:model="gameoptions.{{ $key }}.display"
                            class="w-full rounded border-gray-300 focus:border-yellow-400 focus:ring-yellow-400 text-sm">
                            <option value="">{{ __('team.param.select') }}</option>
                            @foreach($displayOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-1">
                        <button wire:click="delete('{{ $key }}')" type="button"
                            wire:confirm="{{ __('team.param.suppopt') }}"
                            class="hover:scale-110 transition">
                            🗑️
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3 flex gap-2">
        <button wire:click="addRow" type="button"
            class="bg-black text-yellow-400 font-semibold px-4 py-2 rounded hover:bg-gray-800">
            + {{ __('team.param.add') }}
        </button>

        <button wire:click="saveAll" type="button"
            class="bg-yellow-400 text-black font-semibold px-4 py-2 rounded hover:bg-yellow-500"
            wire:loading.attr="disabled" wire:target="saveAll">
            💾 {{ __('global.save') }}
        </button>
    </div>

    <div x-data="{ show: false }"
        x-on:saved.window="show = true; setTimeout(() => show = false, 2000)"
        x-show="show" x-transition
        class="mt-2 text-sm text-green-600">
        {{ __('global.savesuccess') }}
    </div>
</div>