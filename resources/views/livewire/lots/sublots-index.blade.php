<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sublotes</h2>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="bg-gray-100 px-4 mt-4 mb-6 rounded-lg">
            <div class="my-3">
                <span class="font-bold text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Lote {{ $lot->code }}
                </span>
            </div>

            <div class="md:grid md:grid-cols-6 gap-4">
                <div class="md:col-span-3">
                    <x-jet-label class="mb-2 font-bold text-gray-600" value="Tipo de tarea que generó el lote" />
                    <x-jet-input type="text" class="w-full text-gray-500" disabled
                        value="{{ $lotData['taskName'] }}" />
                </div>

                <div class="md:col-span-1">
                    <x-jet-label class="mb-2 font-bold text-gray-600" value="Usuario generador" />
                    <x-jet-input type="text" class="w-full text-gray-500" disabled value="{{ $lotData['finishedBy'] }}" />
                </div>

                <div class="md:col-span-1">
                    <x-jet-label class="mb-2 font-bold text-gray-600" value="Fecha generación" />
                    <x-jet-input type="text" class="w-full text-gray-500" disabled value="{{ $lotData['finishedAt']  }}" />
                </div>

                <div class="md:col-span-1">
                    <x-jet-label class="mb-2 font-bold text-gray-600" value="Tarea generadora" />
                    <x-jet-input type="text" class="w-full text-gray-500" disabled value="{{ $lotData['taskId']  }}" />
                </div>
            </div>
        </div>

        @if ($lot != null)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th scope="col" class="px-4 py-2 whitespace-nowrap">
                            ID sublote
                        </th>
                        <th scope="col" class="px-4 py-2">
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
                            Disponibilidad
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($lot->sublots as $sublot)
                    <tr>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $sublot->id }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $sublot->code }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
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
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

    </x-responsive-table>

</div>
