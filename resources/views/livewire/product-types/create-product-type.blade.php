<div>
    <x-jet-secondary-button wire:click="createProductType">
        Crear nuevo tipo de producto
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Crear nuevo tipo de producto
        </x-slot>

        <x-slot name="content">

            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Clasificación</x-jet-label>
                <select class="input-control w-full" wire:model.defer="createForm.product_name_id">
                    <option value="" disabled selected>Seleccione la clasificación</option>
                    @foreach ($productNames as $productName)
                        <option value="{{ $productName->id }}">{{ $productName->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.product_name_id" />
            </div>

            {{-- Measure --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Medida</x-jet-label>
                <select class="input-control w-full" wire:model.defer="createForm.measure_id">
                    <option value="" disabled selected>Seleccione la medida</option>
                    @foreach ($measures as $measure)
                        <option value="{{ $measure->id }}">{{ $measure->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.measure_id" />
            </div>

            {{-- Unidad --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Unidad</x-jet-label>
                <select class="input-control w-full" wire:model.defer="createForm.unity_id">
                    <option value="" disabled selected>Seleccione la unidad de referencia </option>
                    @foreach ($unities as $unity)
                        <option value="{{ $unity->id }}">{{ $unity->unities }} ({{ $unity->name }})</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.unity_id" />
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="save">
                    Crear tipo de producto
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
                timer: 3000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: 'success',
                title: message
            });
        });

        Livewire.on('error', message => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#1f2937',
            });
        });
    </script>
@endpush
