<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.products.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de producto
            </h2>
            @can('admin.products.edit')
                <a href="{{ route('admin.products.edit', $product->id) }}">
                    <x-jet-secondary-button>
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                        </x-jet-seconda-button>
                </a>
            @endcan
        </div>
    </x-slot>

    {{-- CONTENIDO --}}
    <div class="max-w-7xl mx-auto bg-white px-12 py-8 my-4 rounded-lg">

        <p class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Información del producto
            <span class="uppercase">{{ $stats['product_name'] }}</span>
        </p>
        <hr class="my-2">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 py-3 mb-5">

            <div class="space-y-2">
                <p class="font-bold">Nombre del producto:
                    <span class="font-normal text-gray-600">{{ $stats['product_name'] }}</span>
                </p>
                <p class="font-bold">Clasificación:
                    <span class="font-normal text-gray-600">{{ $stats['category'] }}</span>
                </p>
                <p class="font-bold">Medida:
                    <span class="font-normal text-gray-600">{{ $stats['measure'] }}</span>
                </p>
                <p class="font-bold">Tamaño:
                    <span class="font-normal text-gray-600">{{ $stats['unit'] }}</span>
                </p>
                <p class="font-bold">Etapa de producción:
                    <span class="font-normal text-gray-600">{{ $stats['product_phase'] }}</span>
                </p>
                <p class="font-bold">Fecha creación:
                    <span class="font-normal text-gray-600">{{ $stats['created_at'] }}</span>
                </p>
            </div>

            <div class="space-y-2">
                <p class="font-bold">Producto utilizado en las siguientes tareas</p>
                <ul class="list-disc text-gray-600 ml-10">
                    @foreach ($tasks_names as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @if ($stats['previous_product'])
                    <p class="font-bold">Producto anterior:
                        <span class="font-normal text-gray-600">{{ $stats['previous_product'] }}</span>
                    </p>
                @endif
                @if ($following_products)
                    <p class="font-bold">Productos siguientes</p>
                    <ul class="list-disc text-gray-600 ml-10">
                        @foreach ($following_products as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>

        <p class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Información de stock
        </p>

        {{-- <hr class="mt-2 mb-4"> --}}

        <div class="grid md:grid-cols-4 gap-4 mt-3 mb-5">
            <div class="bg-gray-100 rounded-lg p-5 flex items-center justify-center font-semibold">
                {{ $stock['real_stock'] }}
                unidades
            </div>
            <div class="bg-gray-200 rounded-lg p-5 flex items-center justify-center font-semibold">
                {{ $stock['m2_stock'] }}
                m²
            </div>
            <div class="bg-gray-300 rounded-lg p-5 flex items-center justify-center font-semibold">
                <i class="fas fa-boxes mr-2"></i>
                {{ $stock['sublots_stock'] }}
                sublotes disponibles
            </div>
            <div class="bg-gray-300 rounded-lg p-5 flex items-center justify-center font-semibold">
                <i class="fas fa-map-marker-alt mr-2"></i>
                {{ $stock['areas'] }}
            </div>
        </div>

        <x-responsive-table>

            @if ($sublots->count())
                <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                    <thead class="border-b border-gray-300 bg-gray-200">
                        <tr class="text-center text-sm text-gray-500 uppercase">
                            <th scope="col" class="px-4 py-2">
                                Código
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
                            <th scope="col" class="px-4 whitespace-nowrap py-2">
                                M2 inicial
                            </th>
                            <th scope="col" class="px-4 whitespace-nowrap py-2">
                                M2 actual
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Disponibilidad
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($sublots as $sublot)
                            <tr>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $sublot->code }}
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
                                    </p>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-4">
                    <p class="text-center font-semibold">No se encontraron sublotes.</p>
                </div>
            @endif

        </x-responsive-table>
        <br>
        @can('admin.products.create-previous-product', 'admin.products.create-following-products')
            <p class="font-bold text-gray-700 text-lg">
                <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                Producto anterior y productos siguientes
            </p>
            <hr class="my-2">
        @endcan
        <span>
            @can('admin.products.create-previous-product')
                Un <span class="font-bold">producto anterior</span> es aquel a partir del cual se generó el producto actual.
                Puede configurar el producto anterior de este producto haciendo
                <a href="{{ route('admin.products.create-previous-product', $product) }}" class="hover:text-cyan-900">
                    <span class="font-bold">click aquí.</span>
                </a>
                <br>
            @endcan
            @can('admin.products.create-following-products')
                Los <span class="font-bold">productos siguientes </span> son aquellos que se generan a partir de este
                producto. Puede configurarlos haciendo
                <a href="{{ route('admin.products.create-following-products', $product) }}" class="hover:text-cyan-900">
                    <span class="font-bold">click aquí.</span>
                </a>
            @endcan
        </span>

    </div>

</div>
