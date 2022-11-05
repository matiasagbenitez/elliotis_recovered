<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.sale-orders.index') }}">
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

    {{-- Purchase detail --}}
    <div class="max-w-5xl mx-auto bg-white p-10 rounded-lg border-2">
        <h2 class=" font-mono font-semibold text-2xl text-gray-800 leading-tight mb-4 uppercase text-center">
            Detalle de orden de venta N° {{ $saleOrder->id }}
        </h2>

        {{-- Datos del proveedor --}}
        <div class="mt-6">
            <p class="font-mono font-bold text-xl">Datos de la orden</p>
            <hr class="w-full">
            <div class="flex justify-between my-2">
                <div class="w-1/2 space-y-2">
                    <p class="text-sm font-mono font-bold">
                        Razón social:
                        <span class="font-normal">
                            {{ $saleOrder->client->business_name }}
                            ({{ $saleOrder->client->iva_condition->name }})
                        </span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Fecha comprobante:
                        <span class="font-normal"> {{ Date::parse($saleOrder->registration_date)->format('d-m-Y') }} </span>
                    </p>
                </div>
                @if (!$saleOrder->is_active)
                    <div class="w-1/2 flex justify-center items-center border border-red-700 text-red-700 px-4 py-3 rounded relative gap-4"
                        role="alert">
                        {{-- <div> --}}
                        <i class="fas fa-ban text-5xl"></i>
                        {{-- </div> --}}
                        <div class="flex flex-col">
                            <p class="font-bold font-mono uppercase">Orden de venta anulada</p>
                            <p class="font-mono text-sm">
                                La presente orden de venta no es válida ya que fue anulada por {{ $user_who_cancelled }}
                                el día {{ $saleOrder->updated_at->format('d-m-Y') }} a las {{ $saleOrder->updated_at->format('H:i:s') }} hs
                            </p>
                        </div>
                    </div>
                    @elseif ($saleOrder->its_done)
                    <div class="w-1/2 flex justify-center items-center border border-green-700 text-green-700 px-4 py-3 rounded relative gap-4"
                        role="alert">
                        {{-- <div> --}}
                        <i class="fas fa-ban text-5xl"></i>
                        {{-- </div> --}}
                        <div class="flex flex-col">
                            <p class="font-bold font-mono uppercase">Orden de venta procesada correctamente</p>
                            <a href="{{ route('admin.sales.show-detail', $sale->id) }}" class="font-mono text-sm">
                                La presente orden fue procesada correctamente y asociada a la venta #{{ $sale->id }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Detalle de la compra de la compra --}}
        <div class="mt-6">
            <p class=" font-mono font-bold text-xl">Detalle de la orden</p>
            <hr class="w-full">

            {{-- Table for product_purchase --}}
            <div class="mt-5">
                <table class="min-w-full divide-y border">
                    <thead>
                        <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                            <th scope="col" class="px-6 py-1 text-left">
                                Producto
                            </th>
                            <th scope="col" class="px-6 py-1">
                                Cantidad
                            </th>
                            <th scope="col" class="px-6 py-1">
                                Precio unitario
                            </th>
                            <th scope="col" class="px-6 py-1 text-right">
                                Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saleOrder->products as $product)
                            <tr class="uppercase text-sm font-mono">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <p>
                                        {{ $product->name }}
                                    </p>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-center">
                                    <p>
                                        {{ $product->pivot->quantity }}
                                    </p>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-center">
                                    <p>
                                        {{-- Decimal format --}}
                                        @php
                                            $price_with_iva = $product->pivot->price * 1.21;
                                            $price_with_iva = number_format($price_with_iva, 2, ',', '.');
                                        @endphp

                                        @if ($saleOrder->client->iva_condition->discriminate)
                                            ${{ number_format($product->pivot->price, 2, ',', '.') }}
                                        @else
                                            ${{ $price_with_iva }}
                                        @endif
                                    </p>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-right">
                                    <p>
                                        @php
                                            $subtotal_with_iva = $product->pivot->quantity * $product->pivot->price * 1.21;
                                            $subtotal_with_iva = number_format($subtotal_with_iva, 2, ',', '.');
                                        @endphp

                                        @if ($saleOrder->client->iva_condition->discriminate)
                                            ${{ number_format($product->pivot->quantity * $product->pivot->price, 2, ',', '.') }}
                                        @else
                                            ${{ $subtotal_with_iva }}
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($saleOrder->client->iva_condition->discriminate)
                {{-- Totales --}}
                <div class="mt-5 flex flex-col items-end px-6 space-y-2">
                    <p class="text-sm font-mono font-bold">
                        Subtotal:
                        <span class="font-normal">${{ number_format($saleOrder->subtotal, 2, ',', '.') }}</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        IVA:
                        <span class="font-normal">${{ number_format($saleOrder->iva, 2, ',', '.') }}</span>
                    </p>
                    <p class="font-mono font-bold text-lg">
                        Total:
                        <span>${{ number_format($saleOrder->total, 2, ',', '.') }}</span>
                    </p>
                </div>
            @else
                {{-- Totales --}}
                <div class="mt-5 flex flex-col items-end px-6 space-y-2">
                    <p class="font-mono font-bold text-lg">
                        Total:
                        <span>${{ number_format($saleOrder->total, 2, ',', '.') }}</span>
                    </p>
                </div>
            @endif

            <div class="mt-2 p-2 text-xs border-1 border uppercase text-center">
                Detalle de orden venta para uso interno. Documento no válido como factura.
            </div>

        </div>

    </div>
