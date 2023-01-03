<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sublotes por producto</h2>
        </div>
    </x-slot>

    <x-responsive-table>
        <div class="px-4 py-3">
            <div class="grid grid-cols-6 gap-4">
                <div class="col-span-6">
                    <x-jet-label class="mb-1">Producto</x-jet-label>
                    <select wire:model='filter' class="input-control w-full">
                        <option value="">Todos los productos</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if ($sublotStats)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th scope="col" class="px-4 py-2">
                            ID
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Código
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Producto
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Área
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Stock
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($sublotStats as $stat)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['id'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['code'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['product'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['area'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['actual_quantity'] }}
                                </p>
                            </td>
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
