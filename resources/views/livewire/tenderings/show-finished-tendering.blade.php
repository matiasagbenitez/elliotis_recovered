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

    @if ($bestOffer)
        <div class="px-10 py-6 bg-white rounded-lg shadow mb-8">

            <span class="font-bold text-gray-700 text-lg">
                <i class="fas fa-star text-yellow-400 text-lg mr-2"></i>
                Oferta ganadora
            </span>
            <hr class="my-2">

            <div class="md:grid md:grid-cols-2">
                <div class="space-y-1">
                    <p class="font-bold">
                        Proveedor:
                        <span class="uppercase font-normal">{{ $bestOfferStats['supplier'] }}</span>
                    </p>
                    <p class="font-bold">
                        {{ $bestOfferStats['products_quantities'] }}
                    </p>
                    <p class="font-bold">
                        {{ $bestOfferStats['summary'] }}
                    </p>
                    <p class="font-bold">
                        Importe total estimado:
                        <span class="uppercase font-normal">${{ $bestOfferStats['total'] }}</span>
                    </p>
                    <p class="font-bold">
                        Fecha de entrega:
                        <span class="uppercase font-normal">{{ $bestOfferStats['delivery_date'] }}</span>
                    </p>
                </div>

                <div class="flex items-end justify-end gap-2">
                    <a href="{{ route('admin.tenderings.show-offer-detail', ['tendering' => $bestOfferStats['tendering_id'], 'hash' => $bestOfferStats['hash']]) }}">
                        <x-jet-secondary-button class="mt-4" wire:click="showBestOffer">
                            Ver oferta
                        </x-jet-secondary-button>
                    </a>
                    <x-jet-button class="mt-4" wire:click="showBestOffer">
                        Generar orden de compra
                    </x-jet-button>
                </div>
            </div>

        </div>
    @endif

    <div class="px-10 py-8 bg-white rounded-lg shadow">

        {{-- ESTADÍSTICAS --}}
        <div class="grid grid-cols-4 gap-4">

            {{-- Solicitudes enviadas --}}
            <div class="bg-slate-200 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('requested')">
                <p class="text-center text-xl">
                    {{-- {{ $requestedSuppliers }} --}}
                </p>
                <p class="text-center uppercase font-bold">Solicitudes enviadas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-business-time"></i>
                </p>
            </div>

            {{-- Solicitudes vistas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('seen')">
                <p class="text-center text-xl">
                    {{-- {{ $seenRequests }} --}}
                </p>
                <p class="text-center uppercase font-bold">Solicitudes vistas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-eye"></i>
                </p>
            </div>

            {{-- Ofertas enviadas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('answered')">
                <p class="text-center text-xl">
                    {{-- {{ $answeredRequests }} --}}
                </p>
                <p class="text-center uppercase font-bold">Ofertas válidas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-check-circle"></i>
                </p>
            </div>

            {{-- Ofertas canceladas --}}
            <div class="bg-slate-400 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('cancelled')">
                <p class="text-center text-xl">
                    {{-- {{ $cancelledOffers }} --}}
                </p>
                <p class="text-center uppercase font-bold">Ofertas canceladas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-ban"></i>
                </p>
            </div>

        </div>
    </div>

    <div class="px-6 py-6 mt-6 bg-white rounded-lg shadow">

        {{-- ESTADÍSTICAS --}}
        <div class="grid grid-cols-5 gap-4 text-sm ">

            {{-- Todas las oferas --}}
            <div class="bg-slate-200 rounded-lg p-6 hover:cursor-pointer flex flex-col justify-center"
                wire:click="filterOffers('all')">
                <p class="text-center text-xl mb-5">
                    {{-- {{ $totalOffers }} --}}
                </p>
                <p class="text-center uppercase font-bold">Todas las ofertas recibidas</p>
            </div>

            {{-- Solicitudes enviadas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer flex flex-col justify-center"
                wire:click="filterOffers('productsOkQuantitiesOk')">
                <p class="text-center text-xl mb-5">
                    {{-- {{ $productsOkQuantitiesOk }} --}}
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
                    {{-- {{ $productsOkQuantitiesNo }} --}}
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
                    {{-- {{ $productsNoQuantitiesOk }} --}}
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
                    {{-- {{ $productsNoQuantitiesNo }} --}}
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


</div>
