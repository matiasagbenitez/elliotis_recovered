<div>
    <x-jet-secondary-button wire:click="createPaymentCondition">
        Crear nueva condición de pago
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Crear nueva condición de pago
        </x-slot>

        <x-slot name="content">
            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Nombre</x-jet-label>
                <x-jet-input wire:model.defer="createForm.name" type="text" class="w-full"
                    placeholder="Ingrese el nombre de la condición de pago"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.name" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">¿Es diferido?</x-jet-label>
                <select wire:model.defer="createForm.is_deferred" class="input-control w-full">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.is_deferred" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="save">
                    Crear condición de pago
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
