<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.lots.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            {{-- PDF BUTTON --}}
            <a href="{{ route('admin.lot-detail.pdf', $lot) }}">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-2 bg-gray-200">
            <div class="my-3">
                <p class="font-bold text-gray-700 text-lg">
                    <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                    Detalle del lote
                    <span class="uppercase">{{ $lot->code }}</span>
                </p>
            </div>

            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/2">
                    <p class="font-bold">
                        Tarea generadora:
                        <span class="font-normal">{{ $lotData['taskName'] }}</span>
                    </p>
                    <p class="font-bold">
                        Inicio tarea:
                        <span class="font-normal">{{ $lotData['startedBy'] }} el {{ $lotData['startedAt'] }}</span>
                    </p>
                    <p class="font-bold">
                        Fin tarea
                        <span class="font-normal">{{ $lotData['startedBy'] }} el {{ $lotData['startedAt'] }}</span>
                    </p>
                </div>

                <div class="w-full md:w-1/2">
                    <p class="font-bold">
                        Sublotes producidos:
                        <span class="font-normal">{{ $lotData['sublots_count'] }}</span>
                    </p>
                    <p class="font-bold">
                        Producción inicial:
                        <span class="font-normal">{{ $lotData['initial_production'] }}</span>
                    </p>
                    <p class="font-bold">
                        Stock actual:
                        <span class="font-normal">{{ $lotData['actual_m2'] }}</span>
                    </p>
                </div>
            </div>

            <div class="mt-4 mb-1">
                <p class="font-bold text-gray-700 text-md">
                    <i class="fas fa-boxes text-gray-700 text-md mr-2"></i>
                    Sublotes asociados
                </p>
            </div>
        </div>

        @if ($lot != null)
            <x-responsive-table>
                <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                    <thead class="border-b border-gray-300 bg-gray-200">
                        <tr class="text-center text-sm text-gray-500 uppercase whitespace-nowrap">
                            <th scope="col" class="px-4 py-2 ">
                                Código
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Producto
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Ubicación
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Cantidad inicial
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Cantidad actual
                            </th>
                            <th scope="col" class="px-4 py-2">
                                M2 inicial
                            </th>
                            <th scope="col" class="px-4 py-2">
                                M2 actual
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Disponibilidad
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($lot->sublots as $sublot)
                            <tr>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $sublot->code }}
                                    </p>
                                </td>
                                <td class="px-6 py-2">
                                    <p class="text-sm uppercase">
                                        {{ $sublot->product->name }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $sublot->area->name }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $sublot->initial_quantity }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $sublot->actual_quantity }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center  whitespace-nowrap">
                                    <p class="text-sm">
                                        {{ $sublot->initial_m2 }} m2
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center whitespace-nowrap font-bold">
                                    <p class="text-sm">
                                        {{ $sublot->actual_m2 }} m2
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        @if ($sublot->available)
                                            <span
                                                class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disponible
                                            </span>
                                        @else
                                            <span
                                                class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Sin stock
                                            </span>
                                        @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-responsive-table>
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

    </x-responsive-table>

</div>
