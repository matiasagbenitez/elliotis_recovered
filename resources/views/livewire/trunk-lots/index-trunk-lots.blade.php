<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Rollos para producción</h1>
        </div>
    </x-slot>

    @if ($trunk_lots->count())
        <div class="py-3 {{ $resume_view ? 'pb-0 bg-gray-50' : '' }} bg-white rounded-lg shadow mb-5">
            <button wire:click='toggleResumeView'
                class="font-semibold text-lg text-gray-800 leading-tight hover:font-bold pl-3">
                Resumen de disponibilidad
            </button>
            @if ($resume_view)
                <div class="my-2">
                    <x-responsive-table>
                        <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                            <thead
                                class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                                <tr>
                                    <th scope="col" class="w-1/3 px-4 py-2">
                                        Producto
                                    </th>
                                    <th scope="col" class="w-1/3 px-4 py-2">
                                        Cantidad de sublotes disponibles
                                    </th>
                                    <th scope="col" class="w-1/3 px-4 py-2">
                                        Cantidad total actual
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($products as $product)
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-2 text-center">
                                            <p class="text-sm uppercase">
                                                {{ $product['product_name'] }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <p class="text-sm uppercase">
                                                {{ $product['lots_count'] }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <p class="text-sm uppercase">
                                                {{ $product['actual_quantity'] }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-responsive-table>
                </div>
            @endif
        </div>

        <h1 class="font-bold p-3 text-xl text-gray-800 leading-tight text-center uppercase mb-3">LOTES DE ROLLOS PARA
            PRODUCCIÓN</h1>

        @foreach ($trunk_lots as $trunk_lot)
            <div class="pt-4 bg-gray-50 rounded-lg shadow mb-5">

                <div class="flex justify-between items-center px-6 mb-2">
                    <span class="font-bold text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Lote {{ $trunk_lot->code }}
                    </span>
                    <a href="{{ route('admin.purchases.show-detail', $trunk_lot->purchase_id) }}"
                        class="hover:underline" title="Ver detalle de compra">
                        Compra #{{ $trunk_lot->purchase->id }}
                        ({{ $trunk_lot->purchase->supplier->business_name }})
                    </a>
                    <p class="font-bold">Alta producción:
                        <span class="font-normal">{{ Date::parse($trunk_lot->created_at)->format('d-m-Y H:i') }}
                        </span>
                    </p>
                </div>

                <x-responsive-table>
                    <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="text-sm text-center text-gray-700 uppercase border-b bg-gray-200">
                            <tr>
                                <th scope="col" class="px-4 py-2 whitespace-nowrap">
                                    ID sublote
                                </th>
                                <th scope="col" class="w-3/6 px-4 py-2">
                                    Producto
                                </th>
                                <th scope="col" class="w-1/6 px-4 py-2">
                                    Cantidad inicial
                                </th>
                                <th scope="col" class="w-1/6 px-4 py-2">
                                    Cantidad actual
                                </th>
                                <th scope="col" class="w-1/6 px-4 py-2">
                                    Disponibilidad
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($trunk_lot->trunkSublots as $lot)
                                <tr>
                                    <td class="px-6 py-2 text-center">
                                        <p class="text-sm uppercase">
                                            {{ $lot->id }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <p class="text-sm uppercase">
                                            {{ $lot->product->name }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <p class="text-sm uppercase">
                                            {{ $lot->initial_quantity }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <p class="text-sm uppercase">
                                            {{ $lot->actual_quantity }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <p class="text-sm uppercase">
                                            @if ($lot->available)
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
                                        </p>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-responsive-table>
            </div>
        @endforeach

        @if ($trunk_lots->hasPages())
            <div class="px-6 py-3">
                {{ $trunk_lots->links() }}
            </div>
        @endif

    @else
        <div class="px-6 py-4">
            <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
        </div>
    @endif

</div>
