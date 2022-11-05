<div>
    <x-jet-button wire:click="editProductName">
        <i class="fas fa-edit"></i>
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Editar nombre de producto
        </x-slot>

        <x-slot name="content">
            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Nombre del producto</x-jet-label>
                <x-jet-input wire:model.defer="editForm.name" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.name" />
            </div>

            {{-- Margen --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Margen</x-jet-label>
                <x-jet-input wire:model.defer="editForm.margin" step="0.01" min="1" type="number"
                    class="w-full" placeholder="1%"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.margin" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="update">
                    Guardar cambios
                </x-jet-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>
</div>

@push('script')
    <script>
        Livewire.on('success', message => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: 'success',
                title: message
            });
        });
    </script>
@endpush
