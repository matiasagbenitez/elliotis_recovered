<div>
    <x-jet-button wire:click="editIvaCondition">
        <i class="fas fa-edit"></i>
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Editar condición ante IVA
        </x-slot>

        <x-slot name="content">
            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Nombre</x-jet-label>
                <x-jet-input wire:model.defer="editForm.name" type="text" class="w-full" placeholder="Ingrese el nombre de la condición ante IVA"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.name" />
            </div>

             {{-- Discriminate --}}
             <div class="mb-4">
                <x-jet-label class="mb-2">¿Discrimina IVA?</x-jet-label>
                    <div class="flex gap-4">
                        <div class="flex space-x-2">
                            <x-jet-label class="mb-2">Sí</x-jet-label>
                            <x-jet-input wire:model="editForm.discriminate" type="radio" value="1"></x-jet-input>
                        </div>
                        <div class="flex space-x-2">
                            <x-jet-label class="mb-2">No</x-jet-label>
                            <x-jet-input wire:model="editForm.discriminate" type="radio" value="0"></x-jet-input>
                        </div>
                    </div>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.discriminate" />
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
