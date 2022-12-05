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

            {{-- PDF BUTTON --}}
            <a href="#">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    @if ($offer->hash->cancelled)
        <div
            class="flex items-center p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800 border-2 border-red-600">
            <i class="fas fa-info-circle text-lg mr-2"></i>
            <div>
                <span class="font-bold uppercase">Atención!</span>
                Esta oferta fue cancelada por el proveedor el día
                {{ Date::parse($offer->hash->canceled_at)->format('d-m-Y') }}
                a las
                {{ Date::parse($offer->hash->canceled_at)->format('H:i') }}.
            </div>
        </div>
    @endif

    {{-- DETALLE DE HASHES --}}
    <div class="px-6 py-3 bg-white rounded-lg shadow mt-4 font-mono uppercase">
        <p class="font-bold uppercase text-center text-2xl my-1">Oferta de {{ $supplier->business_name }}</p>
        <hr class="mt-1 mb-2">
        <div class="grid grid-cols-2">
            <div class="space-y-3">
                <p class="text-sm font-bold my-1">Concurso visto por primera vez el:
                    <span class="font-normal">
                        @if ($hash->seen)
                            {{ Date::parse($offer->hash->seen_at)->format('d-m-Y H:i') }} hs
                        @else
                            No visto
                        @endif
                    </span>
                </p>
                <p class="text-sm font-bold my-1">Fecha de respuesta inicial:
                    <span class="font-normal">
                        @if ($hash->answered)
                            {{ Date::parse($offer->hash->answered_at)->format('d-m-Y H:i') }} hs
                        @else
                            No respondido
                        @endif
                    </span>
                </p>
                <p class="text-sm font-bold my-1">Fecha última modificación:
                    <span class="font-normal">
                        @if ($hash->answered)
                            {{ Date::parse($offer->updated_at)->format('d-m-Y H:i') }} hs
                        @else
                            No modificado
                        @endif
                    </span>
                </p>
                <p class="text-sm font-bold my-1">Hash:
                    <span class="font-normal">{{ $offer->hash->hash }}</span>
                </p>
            </div>
        </div>

        <p class="font-bold mt-5 mb-2">Detalle última oferta</p>
        {{-- <hr class="mt-1 mb-2"> --}}

        {{-- DETALLE DE OFERTA --}}
        {{-- <x-responsive-table> --}}
            <table class="min-w-full divide-y border">
                <thead>
                    <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Producto
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Cantidad
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Precio unitario
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($offer->products as $product)
                    <tr class="uppercase text-sm font-mono">
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product->name }}
                                </p>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product->pivot->quantity }}
                                </p>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    ${{ number_format($product->pivot->price, 2, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    ${{ number_format($product->pivot->subtotal, 2, ',', '.') }}
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        {{-- </x-responsive-table> --}}
        <div class="mt-5 space-y-3">
            <div>
                <p class="text-sm font-bold my-1">Subtotal =
                    <span class="font-normal">${{ number_format($offer->subtotal, 2, ',', '.') }}</span>
                </p>
            </div>
            <div>
                <p class="text-sm font-bold my-1">IVA =
                    <span class="font-normal">${{ number_format($offer->iva, 2, ',', '.') }}</span>
                </p>
            </div>
            <div>
                <p class="text-sm font-bold my-1">Total final =
                    <span class="font-normal">${{ number_format($offer->total, 2, ',', '.') }}</span>
                </p>
            </div>
        </div>

    </div>
</div>
