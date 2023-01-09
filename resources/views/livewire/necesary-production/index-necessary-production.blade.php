<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Producción necesaria</h2>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4 bg-gray-200">
            <select wire:model='filter' class="input-control w-full">
                <option value="">Todas las órdenes de venta</option>
                @foreach ($saleOrders as $saleOrder)
                    <option value="{{ $saleOrder->id }}">
                        Orden de venta # {{ $saleOrder->id }} - {{ $saleOrder->client->business_name }} ({{ Date::parse($saleOrder->created_at)->format('d-m-Y') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="px-6 py-4">
            <h1 class="font-bold mb-1">Resumen de orden(es) de venta</h1>
            <ul class="ml-10">
                @foreach ($resume as $item)
                    <li class="list-disc">x {{$item['quantity']}} {{$item['name']}}</li>
                @endforeach
            </ul>
        </div>

        @if ($production)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th scope="col"
                            class="px-4 py-2 w-1/2">
                            Producto
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/4">
                            Cantidad en stock
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/4">
                            Cantidad a producir
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($production as $product)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $product['name'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['quantity'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['needed'] }}
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
