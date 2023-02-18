<div>
    <button wire:click="editConditions" title="Editar condiciones">
        <i class="fas fa-edit"></i>
    </button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Editar condiciones de alerta
        </x-slot>

        <x-slot name="content">
            <x-jet-label class="mb-2 font-semibold">
                Recuerde que las condiciones de alerta son las que se utilizarán para determinar si se activa o no la alarma.
                Las probabilidades deben ser valores entre 0 y 1, donde 0 es 0% y 1 es 100%.
            </x-jet-label>

            <div class="mb-4">
                <x-jet-label class="mb-2">CONDICIÓN 1: Temperatura mínima promedio (°C)</x-jet-label>
                <x-jet-input wire:model.defer="editForm.temp" type="number" class="w-full"
                    placeholder="Ingrese la temperatura mínima promedio"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.temp" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">CONDICIÓN 2: Probabilidad de lluvia promedio (0-1%)</x-jet-label>
                <x-jet-input wire:model.defer="editForm.rain_prob" type="number" class="w-full"
                    placeholder="Ingrese la probabilidad de lluvia promeido"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.rain_prob" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">CONDICIÓN 3: Milímetros promedio (mm)</x-jet-label>
                <x-jet-input wire:model.defer="editForm.rain_mm" type="number" class="w-full"
                    placeholder="Ingrese la cantidad de milímetros de lluvia promedio"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.rain_mm" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">CONDICIÓN 4: Humedad máxima promedio (0-1%)</x-jet-label>
                <x-jet-input wire:model.defer="editForm.humidity" type="number" class="w-full"
                    placeholder="Humedad máxima promedio (0-1%)"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.humidity" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">CONDICIÓN 5: Velocidad del viento (km/h)</x-jet-label>
                <x-jet-input wire:model.defer="editForm.wind_speed" type="number" class="w-full"
                    placeholder="elocidad del viento (km/h)<"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.wind_speed" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">Condiciones cumplidas necesarias</x-jet-label>
                <x-jet-input wire:model.defer="editForm.max_conditions" type="number" class="w-full"
                    placeholder="Condiciones cumplidas necesarias"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.max_conditions" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">Días seguidos a promediar</x-jet-label>
                <x-jet-input wire:model.defer="editForm.days_in_row" type="number" class="w-full"
                    placeholder="Días seguidos a promediar"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.days_in_row" />
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
        Livewire.on('success_update', message => {
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
