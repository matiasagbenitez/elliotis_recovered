<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.tenderings.show-detail', ['tendering' => $hash->tendering]) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

        </div>
    </x-slot>

    @if ($offer->hash->cancelled)
        <div class="max-w-6xl mx-auto bg-red-100 flex justify-center items-center text-red-700 px-4 py-3 rounded relative gap-4 mb-5"
            role="alert">
            {{-- <div> --}}
            <i class="fas fa-ban text-5xl"></i>
            {{-- </div> --}}
            <div class="flex flex-col">
                <p class="font-bold font-mono uppercase">Atención!</p>
                <p class="font-mono text-sm">
                    Esta oferta fue cancelada por el proveedor el día
                    {{ Date::parse($offer->hash->cancelled_at)->format('d-m-Y') }}
                    a las
                    {{ Date::parse($offer->hash->cancelled_at)->format('H:i') }}.
                </p>
            </div>
        </div>
    @endif

    {{-- DETALLE DE HASHES --}}
    <div class="max-w-6xl mx-auto px-10 pt-3 pb-8 bg-white rounded-lg shadow mt-4 font-mono">
        <h1 class="font-bold uppercase text-center text-2xl my-4">Detalle oferta de {{ $supplier->business_name }}</h1>

        <p class="font-bold text-xl">Detalle de la oferta</p>
        <hr class="mt-1 mb-2">
        <div class="grid grid-cols-2">
            <div class="space-y-2">
                <p class="text-sm font-bold">
                    Proveedor:
                    <span class="font-normal">
                        {{ $stats['supplier_name'] }}
                    </span>
                </p>
                <p class="text-sm font-bold">
                    Fecha oferta vista:
                    <span class="font-normal">
                        {{ $stats['offer_seen_at'] }}
                    </span>
                </p>
                <p class="text-sm font-bold">
                    Fecha primera respuesta:
                    <span class="font-normal">
                        {{ $stats['offer_answered_at'] }}
                    </span>
                </p>
                <p class="text-sm font-bold">
                    Fecha última modificación:
                    <span class="font-normal">
                        {{ $stats['last_updated_at'] }}
                    </span>
            </div>
        </div>

        <p class="font-bold my-8 mb-2">Detalle productos</p>

        {{-- DETALLE DE OFERTA --}}
        <x-responsive-table>
            <table class="min-w-full divide-y border">
                <thead>
                    <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Producto solicitado
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Cantidad solicitada
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Producto ofertado
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Cantidad ofertada
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr class="uppercase text-sm font-mono">
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['name'] }}
                                </p>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['quantity_required'] }}
                                </p>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['is_offered'] }}
                                </p>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['quantity_offered'] }}
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-responsive-table>

        <div class="mt-5 space-y-2">
            <div>
                <p class="text-sm font-bold my-1"> Peso estimado (TN) =
                    <span class="font-normal">{{ $stats['tn_total'] }}</span>
                </p>
            </div>
            <div>
                <p class="text-sm font-bold my-1">Peso TN (IVA incluido) =
                    <span class="font-normal">{{ $stats['tn_price'] }}</span>
                </p>
            </div>
            <div>
                <p class="text-sm font-bold my-1">Total final =
                    <span class="font-normal">{{ $stats['total'] }}</span>
                </p>
            </div>
        </div>

        <p class="font-bold  mt-8 mb-2">Información adicional</p>
        <hr class="mt-1 mb-2">
        <span class="font-normal text-sm">
            {{ $stats['observations'] }}
        </span>
    </div>
</div>
