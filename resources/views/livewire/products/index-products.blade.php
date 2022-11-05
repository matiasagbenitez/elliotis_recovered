<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Productos</h2>
            {{-- @livewire('products.create-product') --}}
        </div>
    </x-slot>

    <x-responsive-table>

        {{-- Buscador --}}
        <div class="px-6 py-4 flex gap-2 bg-white">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
            <x-jet-button wire:click="toggleFiltersDiv">
                <span class="text-xs mr-1">Filtros</span>
                <i class="fas fa-filter"></i>
            </x-jet-button>
            <x-jet-secondary-button>
                <span class="text-xs mr-1">Reporte</span>
                <i class="fas fa-file"></i>
            </x-jet-secondary-button>
        </div>

        @if ($filtersDiv)
            <div class="px-6 pb-4 grid grid-cols-4 gap-3 bg-white">
                {{-- Filtro por clasificación de producto --}}
                <div class="col-span-1 rounded-lg gap-2">
                    <x-jet-label class="mb-1">Clasificación del producto</x-jet-label>
                    <select class="input-control w-full" wire:model="product_name">
                        <option value="" class="text-md">Todos</option>
                        @foreach ($product_names as $product_name)
                            <option value="{{ $product_name->name }}" class="text-md">{{ $product_name->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por medidas --}}
                <div class="col-span-1 rounded-lg bg-white gap-2">
                    <x-jet-label class="mb-1">Medidas</x-jet-label>
                    <select class="input-control w-full" wire:model="measure">
                        <option value="" class="text-md">Todos</option>
                        @foreach ($measures as $measure)
                            <option value="{{ $measure->name }}" class="text-md">{{ $measure->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por especie --}}
                <div class="col-span-1 rounded-lg bg-white gap-2">
                    <x-jet-label class="mb-1">Especie</x-jet-label>
                    <select class="input-control w-full" wire:model="wood_type">
                        <option value="" class="text-md">Todos</option>
                        @foreach ($wood_types as $wood_type)
                            <option value="{{ $wood_type->name }}" class="text-md">{{ $wood_type->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por especie --}}
                <div class="col-span-1 rounded-lg bg-white gap-2">
                    <x-jet-label class="mb-1">Nivel de stock</x-jet-label>
                    <select class="input-control w-full" wire:model="stock_parameter">
                        <option value="" class="text-md">Todos</option>
                        <option value=">=" class="text-md">Stock normal</option>
                        <option value="<" class="text-md">Bajo stock</option>
                    </select>
                </div>
            </div>
        @endif

        @if ($products->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr>
                        <th scope="col"
                            class="px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col"
                            class="w-2/6 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Denominación
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Especie
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Nivel stock
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Stock real
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            M2
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $product->id }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <p class="font-bold text-sm uppercase">
                                    {{ $product->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $product->woodType->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    @if ($product->real_stock > $product->minimum_stock)
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Stock normal
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Bajo stock
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <p class="text-sm uppercase text-center">
                                    {{ $product->real_stock }}
                                    <span class="text-xs italic">{{ $product->productType->unity->name }}</span>
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <p class="text-sm text-center">
                                    @if ($product->productType->measure->is_trunk)
                                        N/A
                                    @else
                                        {{ $product->productType->unity->unities * ($product->real_stock * $product->productType->measure->m2) }} m²
                                    @endif
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

        @if ($products->hasPages())
            <div class="px-6 py-3">
                {{ $products->links() }}
            </div>
        @endif

    </x-responsive-table>

</div>
