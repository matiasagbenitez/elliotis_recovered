<div class="container py-6">
    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.tenderings.show-detail', $tender->id) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de
                <span class="font-bold">Concurso #{{ $tender->id }}</span>
                finalizado
            </h2>

            {{-- PDF BUTTON --}}
            <a href="#">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>


    <div class="px-6 py-6 mb-6 bg-white rounded-lg shadow">
        @livewire('ranking.prueba-ranking', ['tendering' => $tender])
    </div>

    <div class="px-6 py-6 bg-white rounded-lg shadow">

        {{-- ESTADÍSTICAS --}}
        <div class="grid grid-cols-4 gap-4">

            {{-- Solicitudes enviadas --}}
            <div class="bg-slate-200 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('requested')">
                <p class="text-center text-xl">
                    {{ $requestedSuppliers }}
                </p>
                <p class="text-center uppercase font-bold">Solicitudes enviadas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-business-time"></i>
                </p>
            </div>

            {{-- Solicitudes vistas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('seen')">
                <p class="text-center text-xl">
                    {{ $seenRequests }}
                </p>
                <p class="text-center uppercase font-bold">Solicitudes vistas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-eye"></i>
                </p>
            </div>

            {{-- Ofertas enviadas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('answered')">
                <p class="text-center text-xl">
                    {{ $answeredRequests }}
                </p>
                <p class="text-center uppercase font-bold">Ofertas válidas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-check-circle"></i>
                </p>
            </div>

            {{-- Ofertas canceladas --}}
            <div class="bg-slate-400 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('cancelled')">
                <p class="text-center text-xl">
                    {{ $cancelledOffers }}
                </p>
                <p class="text-center uppercase font-bold">Ofertas canceladas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-ban"></i>
                </p>
            </div>

        </div>
    </div>

    {{-- DETALLES DE PROVEEDORES --}}
    <div class="px-6 py-6 bg-white rounded-lg shadow mt-6">
        <span class="font-bold">{{ $title }}</span>
        <hr class="my-1">
        <ul class="list-disc list-inside ml-4">
            @foreach ($suppliers as $supplier)
                <li class="text-sm">
                    {{ $supplier['business_name'] }}
                </li>
            @endforeach
        </ul>
    </div>

    <div class="px-6 py-6 mt-6 bg-white rounded-lg shadow">

        {{-- ESTADÍSTICAS --}}
        <div class="grid grid-cols-5 gap-4 text-sm ">

            {{-- Todas las oferas --}}
            <div class="bg-slate-200 rounded-lg p-6 hover:cursor-pointer flex flex-col justify-center"
                wire:click="filterOffers('all')">
                <p class="text-center text-xl mb-5">
                    {{ $totalOffers }}
                </p>
                <p class="text-center uppercase font-bold">Todas las ofertas recibidas</p>
            </div>

            {{-- Solicitudes enviadas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer flex flex-col justify-center"
                wire:click="filterOffers('productsOkQuantitiesOk')">
                <p class="text-center text-xl mb-5">
                    {{ $productsOkQuantitiesOk }}
                </p>
                <p class="text-center uppercase font-bold">
                    Productos
                    <i class="fas fa-check ml-1"></i>
                </p>
                <p class="text-center uppercase font-bold">
                    Cantidades
                    <i class="fas fa-check ml-1"></i>
                </p>
            </div>

            {{-- Solicitudes vistas --}}
            <div class="bg-slate-400 rounded-lg p-6 hover:cursor-pointer flex flex-col justify-center"
                wire:click="filterOffers('productsOkQuantitiesNo')">
                <p class="text-center text-xl mb-5">
                    {{ $productsOkQuantitiesNo }}
                </p>
                <p class="text-center uppercase font-bold">
                    Productos
                    <i class="fas fa-check ml-1"></i>
                </p>
                <p class="text-center uppercase font-bold">
                    Cantidades
                    <i class="fas fa-times ml-1"></i>
                </p>
            </div>

            {{-- Ofertas enviadas --}}
            <div class="bg-slate-500 rounded-lg p-6 hover:cursor-pointer flex flex-col justify-center"
                wire:click="filterOffers('productsNoQuantitiesOk')">
                <p class="text-center text-xl mb-5">
                    {{ $productsNoQuantitiesOk }}
                </p>
                <p class="text-center uppercase font-bold">
                    Productos
                    <i class="fas fa-times ml-1"></i>
                </p>
                <p class="text-center uppercase font-bold">
                    Cantidades
                    <i class="fas fa-check ml-1"></i>
                </p>
            </div>

            {{-- Ofertas canceladas --}}
            <div class="bg-slate-600 rounded-lg p-6 hover:cursor-pointer flex flex-col justify-center"
                wire:click="filterOffers('productsNoQuantitiesNo')">
                <p class="text-center text-xl mb-5">
                    {{ $productsNoQuantitiesNo }}
                </p>
                <p class="text-center uppercase font-bold">
                    Productos
                    <i class="fas fa-times ml-1"></i>
                </p>
                <p class="text-center uppercase font-bold">
                    Cantidades
                    <i class="fas fa-times ml-1"></i>
                </p>
            </div>

        </div>
    </div>

    {{-- DETALLES DE PROVEEDORES --}}
    <div class="px-6 py-6 bg-white rounded-lg shadow mt-6">
        <span class="font-bold">{{ $offerTitle }}</span>
        <hr class="my-1">
        @if ($offersList->count())
            @foreach ($offersList as $offer)
                <div class="p-4 my-4 rounded-lg shadow border border-gray-200">
                    <p class="font-bold">
                        Oferta #{{ $offer->id }}
                    </p>
                    <div class="flex justify-between mb-3">
                        <p>
                            <span class="font-bold">Proveedor: </span>
                            {{ $offer->hash->supplier->business_name }}
                        </p>
                        <p>
                            <span class="font-bold">Hash: </span>
                            @php
                                $hash = $offer->hash->hash;
                                $tendering = $offer->hash->tendering->id;
                            @endphp
                            <a href="{{ route('admin.tenderings.show-offer-detail', ['tendering' => $offer->hash->tendering->id, 'hash' => $offer->hash]) }}" class="hover:text-blue-500 hover:underline">

                                {{ $offer->hash->hash }}
                            </a>
                        </p>
                    </div>
                    <x-responsive-table>
                        <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                            <thead
                                class="text-xs text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                                <tr class="text-center">
                                    <th scope="col" class="w-1/4 px-4 py-2">
                                        Producto
                                    </th>
                                    <th scope="col" class="w-1/4 px-4 py-2">
                                        Cantidad
                                    </th>
                                    <th scope="col" class="w-1/4 py-2 px-4">
                                        Precio unitario
                                    </th>
                                    <th scope="col" class="w-1/4 py-2 px-4">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($offer->products as $product)
                                    <tr class="bg-gray-50 text-center">
                                        <td class="px-6 py-2">
                                            <p class="text-xs uppercase">
                                                {{ $product->name }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-2">
                                            <p class="text-xs uppercase">
                                                {{ $product->pivot->quantity }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-2">
                                            <p class="text-xs uppercase">
                                                ${{ number_format($product->pivot->price, 2, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-2">
                                            <p class="text-xs uppercase">
                                                ${{ number_format($product->pivot->subtotal, 2, ',', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-responsive-table>

                    <br>

                    <div class="text-xs">
                        <p class="font-bold">Subtotal =
                            <span class="font-normal">
                                ${{ number_format($offer->subtotal, 2, ',', '.') }}
                            </span>
                        </p>
                        <p class="font-bold">IVA =
                            <span class="font-normal">
                                ${{ number_format($offer->iva, 2, ',', '.') }}
                            </span>
                        </p>
                        <p class="font-bold">Total =
                            <span class="font-normal">
                                ${{ number_format($offer->total, 2, ',', '.') }}
                            </span>
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="mt-5">
                <p class="text-center text-lg">
                    Ninguna oferta coincide con los criterios de búsqueda.
                </p>
            </div>
        @endif
    </div>
</div>
