<x-filament::modal
    id="identity-{{ $record->id }}"
    width="xl"
>
    <x-slot name="trigger">
        <img
            src="{{ Storage::disk('public')->url($record->identity_card_file) }}"
            class="w-6 h-6 object-cover rounded cursor-pointer
                ring-1 ring-gray-300
                hover:scale-105 transition"
        />
    </x-slot>

    <img
        src="{{ Storage::disk('public')->url($record->identity_card_file) }}"
        class="w-full rounded"
    />
</x-filament::modal>
