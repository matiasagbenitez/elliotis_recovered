<div>
    <x-jet-secondary-button wire:click="createIvaType">
        Crear nuevo tipo de IVA
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Crear nuevo tipo de IVA
        </x-slot>

        <x-slot name="content">
            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Nombre</x-jet-label>
                <x-jet-input wire:model="createForm.name" type="text" class="w-full" placeholder="Ingrese el nombre del tipo de IVA"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.name" />
            </div>

            {{-- Percentage --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Porcentaje</x-jet-label>
                <x-jet-input wire:model="createForm.percentage" type="number" class="w-full" placeholder="Ingrese el porcentaje de la condición ante IVA"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.percentage" />
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="save">
                    Crear tipo de IVA
                </x-jet-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>
</div>
