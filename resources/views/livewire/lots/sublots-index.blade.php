<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sublotes</h2>
        </div>
    </x-slot>

    <div class="bg-white px-8 pt-4 pb-8 my-4 rounded-lg">
        <div class="my-3">
            <span class="font-bold text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Lote {{ $lot->code }}
            </span>
            <hr class="my-2">
        </div>

        <div class="grid grid-cols-6 gap-4">
            <div class="col-span-2">
                <x-jet-label class="mb-2 font-bold" value="Tipo de tarea que generó el lote" />
                <x-jet-input type="text" class="w-full text-gray-500" disabled
                    value="{{ $lot->task->typeOfTask->name }}" />
            </div>

            <div class="col-span-2">
                <x-jet-label class="mb-2 font-bold" value="Lote generado por" />
                <x-jet-input type="text" class="w-full text-gray-500" disabled value="#" />
            </div>

            <div class="col-span-2">
                <x-jet-label class="mb-2 font-bold" value="Lote generado el" />
                <x-jet-input type="text" class="w-full text-gray-500" disabled value="{{ $lot->created_at }}" />
            </div>
        </div>
    </div>

    <div class="bg-white px-8 pt-4 pb-8 my-4 rounded-lg">
        <div class="my-3">
            <span class="font-bold text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Detalle de sublotes
            </span>
            <hr class="my-2">
        </div>


        <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
            <thead class="text-sm text-center text-gray-700 uppercase border-b bg-gray-50">
                <tr>
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

    </div>

</div>
