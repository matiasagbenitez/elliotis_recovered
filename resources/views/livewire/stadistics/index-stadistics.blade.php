<div class="container py-6">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Estadísticas</h2>
        </div>
    </x-slot>

    <div class="px-10 py-6 bg-white rounded-lg">

        {{-- FILTROS --}}
        <div class="grid grid-cols-9 gap-3">
            <div class="col-span-9">
                <span class="font-bold text-gray-700 text-lg">
                    <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                    Generación de estadísticas
                    <hr class="my-2">
                </span>
            </div>

            <div class="col-span-3 rounded-lg gap-2">
                <x-jet-label class="mb-1">Tipo de estadística a generar</x-jet-label>
                <select class="input-control w-full" wire:model="stadistic_type">
                    @foreach ($stadistics_types as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Fecha desde</x-jet-label>
                <x-jet-input wire:model="from_datetime" type="datetime-local" class="w-full" />
            </div>

            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Fecha hasta</x-jet-label>
                <x-jet-input wire:model="to_datetime" type="datetime-local" class="w-full" />
            </div>

            <div class="col-span-2 rounded-lg flex items-end justify-center pb-1 space-x-3">
                <x-jet-button wire:click='generate'>
                    <i class="fas fa-calculator mr-2"></i>
                    Calcular
                </x-jet-button>
                <x-jet-secondary-button wire:click="resetFilters">
                    <i class="fas fa-eraser mr-2"></i>
                    Limpiar
                </x-jet-secondary-button>
            </div>
        </div>

        <div class="mt-6">
            <span class="font-bold text-gray-700 text-lg">
                <i class="fas fa-chart-line text-gray-700 text-lg mr-2"></i>
                Resultados
                <hr class="my-2">
            </span>
        </div>

        {{-- <p class="font-semibold">No hay resultados que mostrar.</p> --}}

        @if ($filters['stadistic_type'] == 1)
            @livewire('stadistics.produccion-linea-corte', ['filters' => $filters], key('produccion_linea_corte'))
        @elseif ($filters['stadistic_type'] == 2)
            @livewire('stadistics.produccion-machimbradora', ['filters' => $filters], key('produccion_machimbradora'))
        @elseif ($filters['stadistic_type'] == 3)
            @livewire('stadistics.produccion-empaquetadora', ['filters' => $filters], key('produccion_empaquetadora'))
        @else
            <p class="font-semibold">No hay resultados que mostrar.</p>
        @endif

    </div>

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
    </script>
@endpush
