<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Producción necesaria</h2>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4 bg-gray-200">
            <h1 class="mb-2 text-lg font-bold text-gray-500">Listado de órdenes de venta</h1>
            <select wire:model='filter' class="input-control w-full">
                <option value="">Todas las órdenes de venta</option>
                @foreach ($saleOrders as $saleOrder)
                    <option value="{{ $saleOrder->id }}">
                        Orden de venta # {{ $saleOrder->id }} - {{ $saleOrder->client->business_name }}
                        ({{ Date::parse($saleOrder->created_at)->format('d-m-Y') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="px-6 py-4">
            <h1 class="font-bold mb-1 text-xl text-gray-700">Resumen de orden(es) de venta</h1>
            <ul class="ml-10">
                @foreach ($resume as $item)
                    <li class="list-disc text-gray-600 font-semibold text-lg">x {{ $item['quantity'] }}
                        {{ $item['name'] }}</li>
                @endforeach
            </ul>
        </div>

        @if ($production)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase whitespace-nowrap">
                        <th scope="col" class="px-6 py-2 w-1/2">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-2 w-1/8">
                            Unidades stock
                        </th>
                        <th scope="col" class="px-6 py-2 w-1/8">
                            Unidades necesarias
                        </th>
                        <th scope="col" class="px-6 py-2 w-1/8">
                            M2 stock
                        </th>
                        <th scope="col" class="px-6 py-2 w-1/8">
                            M2 necesarios
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($productionFormated as $product)
                        <tr class="bg-gray-50 whitespace-nowrap">
                            <td class="px-10 py-3">
                                <p class="text-sm uppercase">
                                    {{ $product['name'] }}
                                </p>
                            </td>
                            <td class="px-10 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['quantity'] }}
                                </p>
                            </td>
                            <td class="px-10 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['quantity_needed'] }}
                                    {{-- {{ $product['needed'] }} --}}
                                </p>
                            </td>
                            <td class="px-10 py-3 text-right font-bold">
                                <p class="text-sm">
                                    {{ $product['m2'] }}
                                </p>
                            </td>
                            <td class="px-10 py-3 text-right font-bold">
                                <p class="text-sm">
                                    {{ $product['m2_needed'] }}
                                </p>
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
