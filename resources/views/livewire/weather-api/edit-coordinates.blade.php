<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.parameters.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar coordenadas de localización
            </h2>
            <div>

            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-10 py-6 bg-white rounded-lg">

        <span class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Localidades usualmente utilizadas para la API de clima
            <hr class="my-2">
        </span>

        <div class="mb-4">
            <x-jet-label class="mb-2">Listado de ciudades</x-jet-label>
            <select wire:model="city_id" class="input-control w-full">
                <option value="">Seleccione una opción</option>
                @foreach ($usual_coordinates as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->city }}
                        ({{ $item->lat }}, {{ $item->lon }})
                    </option>
                @endforeach
            </select>
            <x-jet-input-error class="mt-2 text-xs font-semibold" for="city_id" />
        </div>

        <span class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Coordenadas personalizadas
            <hr class="my-2">
        </span>

        <div class="grid grid-cols-2 gap-3">
            <div class="mb-4">
                <x-jet-label class="mb-2">Latitud</x-jet-label>
                <x-jet-input wire:model="editForm.lat" type="text" class="w-full"
                    placeholder="Ingrese la latitud geográfica"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.lat" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">Longitud</x-jet-label>
                <x-jet-input wire:model="editForm.lon" type="text" class="w-full" placeholder="Ingrese la longitud">
                </x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.lon" />
            </div>
        </div>

        <div class="flex mt-2 justify-end">
            <x-jet-button wire:click='presave'>
                Guardar cambios
            </x-jet-button>
        </div>
    </div>
</div>


@push('script')
    <script>
        Livewire.on('warning', message => {
            Swal.fire({
                title: '¿Ciudad correcta?',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Sí, guardar cambios',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.emitTo('weather-api.edit-coordinates', 'save');

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
                }
            })
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
