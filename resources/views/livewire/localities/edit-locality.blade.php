<div>
    <x-jet-button wire:click="editLocality">
        <i class="fas fa-edit"></i>
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Editar localidad
        </x-slot>

        <x-slot name="content">
            {{-- Country --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">País</x-jet-label>
                <select class="input-control w-full" wire:model="editForm.country_id">
                    @foreach ($countries as $country)
                        @if ($country_id == $locality->province->country_id)
                            <option value="{{ $country->id }}" selected>{{ $country->name }}</option>
                        @else
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endif
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.country_id" />
            </div>

            {{-- Province --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Provincia</x-jet-label>
                <select class="input-control w-full" wire:model="editForm.province_id">
                    <option value="" disabled selected>Seleccione la provincia a la que pertenece</option>
                    @foreach ($provinces as $province)
                        @if ($province_id == $locality->province_id)
                            <option value="{{ $province->id }}" selected>{{ $province->name }}</option>
                        @else
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endif
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.province_id" />
            </div>

            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Localidad</x-jet-label>
                <x-jet-input wire:model.defer="editForm.name" type="text" class="w-full" placeholder="Ingrese el nombre de la localidad"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.name" />
            </div>

            {{-- Postal code --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Código postal</x-jet-label>
                <x-jet-input wire:model.defer="editForm.postal_code" type="text" class="w-full" placeholder="Ingrese el código postal de la localidad"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.postal_code" />
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
