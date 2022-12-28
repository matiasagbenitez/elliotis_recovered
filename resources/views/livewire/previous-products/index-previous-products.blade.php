<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Productos de salida</h2>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <h1 class="text-lg pb-2 font-semibold">Seleccione un producto de entrada para ver su producto ideal anterior</h1>
            <select wire:model='filter' class="input-control w-full">
                <option disabled value="">Seleccione una opción</option>
                @foreach ($allProducts as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        @if ($previous_product != null)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th scope="col"
                            class="px-4 py-2">
                            ID
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-full">
                            Nombre
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="bg-gray-50">
                        <td class="px-6 py-3">
                            <p class="text-sm uppercase">
                                {{ $previous_product->id }}
                            </p>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <p class="text-sm uppercase">
                                {{ $previous_product->name }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

    </x-responsive-table>

</div>
