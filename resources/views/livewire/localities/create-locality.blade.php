<div>
    <x-jet-secondary-button wire:click="createLocality">
        Crear nueva localidad
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Crear nueva localidad
        </x-slot>

        <x-slot name="content">
            {{-- Country --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">País</x-jet-label>
                <select class="input-control w-full" wire:model="createForm.country_id">
                    <option value="" disabled selected>Seleccione el país al que pertenece</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.country_id" />
            </div>

            {{-- Province --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Provincia</x-jet-label>
                <select class="input-control w-full" wire:model="createForm.province_id">
                    <option value="" disabled selected>Seleccione la provincia a la que pertenece</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.province_id" />
            </div>

            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Localidad</x-jet-label>
                <x-jet-input wire:model.defer="createForm.name" type="text" class="w-full" placeholder="Ingrese el nombre de la localidad"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.name" />
            </div>

            {{-- Postal code --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Código postal</x-jet-label>
                <x-jet-input wire:model.defer="createForm.postal_code" type="text" class="w-full" placeholder="Ingrese el código postal de la localidad"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.postal_code" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="save">
                    Crear localidad
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
