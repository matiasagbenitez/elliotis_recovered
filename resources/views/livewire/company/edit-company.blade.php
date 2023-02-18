<div class="container py-6">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Información de la empresa</h2>
        </div>
    </x-slot>

    <x-jet-form-section class="mb-6" submit="save">

        <x-slot name="title">
            <p class="font-bold text-gray-700">Actualizar información de la empresa</p>
        </x-slot>

        <x-slot name="description">
            <p class="text-justify text-sm text-gray-600">
                Lea detenidamente la información solicitada y rellene los campos requeridos para registrar los cambios de la información de la empresa.
            </p>
        </x-slot>

        <x-slot name="form">

            <div class="col-span-6">
                <h2 class="font-bold">Información de la empresa</h2>
                <hr>
            </div>

            {{-- Nombre --}}
            <div class="col-span-4">
                <x-jet-label class="mb-2">Nombre de la empresa</x-jet-label>
                <x-jet-input wire:model="editForm.name" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.name" />
            </div>

            <div class="col-span-2">
                <x-jet-label class="mb-2">CUIT</x-jet-label>
                <x-jet-input wire:model="editForm.cuit" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.cuit" />
            </div>
            <div class="col-span-6">
                <x-jet-label class="mb-2">Slogan (opcional)</x-jet-label>
                <x-jet-input wire:model="editForm.slogan" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.slogan" />
            </div>
            <div class="col-span-3">
                <x-jet-label class="mb-2">Correo electrónico</x-jet-label>
                <x-jet-input wire:model="editForm.email" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.email" />
            </div>
            <div class="col-span-3">
                <x-jet-label class="mb-2">Teléfono</x-jet-label>
                <x-jet-input wire:model="editForm.phone" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.phone" />
            </div>
            <div class="col-span-4">
                <x-jet-label class="mb-2">Dirección</x-jet-label>
                <x-jet-input wire:model="editForm.address" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.address" />
            </div>
            <div class="col-span-2">
                <x-jet-label class="mb-2">Código postal</x-jet-label>
                <x-jet-input wire:model="editForm.cp" type="text" class="w-full"
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.cp" />
            </div>

            <div class="col-span-2">
                <x-jet-label class="mb-2">Logo actual</x-jet-label>
                <img src="{{ asset('storage/img' . $editForm['logo']) }}" alt="Logo actual" class="w-32 h-32">
            </div>

            <div class="col-span-2">
                <x-jet-label class="mb-2">Reemplazar logo actual</x-jet-label>
                <x-jet-input wire:model="newLogo" type="file" accept="image/*"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="newLogo" />
            </div>

            @if ($newLogo)
                <div class="col-span-2">
                    <x-jet-label class="mb-2">Logo nuevo</x-jet-label>
                    <img src="{{ $newLogo->temporaryUrl() }}" alt="Logo nuevo" class="w-32 h-32">
                </div>
            @endif

        </x-slot>

        <x-slot name="actions">
            <x-jet-button class="px-6">
                Actualizar información
            </x-jet-button>
        </x-slot>

    </x-jet-form-section>

</div>

@push('script')
    <script>
        Livewire.on('error', message => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#1f2937',
            });
        });

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
