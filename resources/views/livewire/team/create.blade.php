<div class="p-4 rounded shadow">
    <h2 class="text-lg font-bold mb-4">{{ __('team.create') }}</h2>

    @if(session()->has('success'))
        <div class="text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <input 
        type="text" 
        wire:model="name" 
        placeholder="{{ __('team.name') }}"
        class="border p-2 w-full mb-2"
    >

    <input 
        type="text" 
        wire:model="whatsapp" 
        placeholder="{{ __('team.whatsapp') }}"
        class="border p-2 w-full mb-2"
    >

    <input 
        type="text" 
        wire:model="msg_convocation" 
        placeholder="{{ __('team.convocation') }}"
        class="border p-2 w-full mb-2"
    >

    @error('name') 
        <div class="text-red-500">{{ $message }}</div> 
    @enderror

    <button 
        wire:click="create"
        class="bg-blue-500 text-white px-4 py-2 mt-2"
    >
        {{ __('team.create') }}
    </button>
</div>