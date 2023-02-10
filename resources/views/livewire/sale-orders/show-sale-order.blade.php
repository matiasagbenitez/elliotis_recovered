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
            <a href="{{ route('admin.sale-order-detail.pdf', $saleOrder) }}">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    @if (!$saleOrder->is_active)
        <div class="max-w-6xl mx-auto bg-red-100 flex justify-center items-center text-red-700 px-4 py-3 rounded relative gap-4 mb-5"
            role="alert">
            {{-- <div> --}}
            <i class="fas fa-ban text-5xl"></i>
            {{-- </div> --}}
            <div class="flex flex-col">
                <p class="font-bold font-mono uppercase">Orden de venta anulada</p>
                <p class="font-mono text-sm">
                    La presente orden de venta no es válida ya que fue anulada por {{ $user_who_cancelled }}
                    el día {{ $saleOrder->updated_at->format('d-m-Y') }} a las
                    {{ $saleOrder->updated_at->format('H:i') }}.
                </p>
            </div>
        </div>
    @elseif ($saleOrder->its_done)
        <div class="max-w-6xl mx-auto bg-green-100 flex justify-center items-center text-green-700 px-4 py-3 rounded relative gap-4 mb-5""
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

    {{-- Purchase detail --}}
    <div class="max-w-6xl mx-auto bg-white p-10 rounded-lg border-2">
        <h2 class=" font-mono font-semibold text-2xl text-gray-800 leading-tight mb-4 uppercase text-center">
            Detalle de orden de venta N° {{ $saleOrder->id }}
        </h2>

        {{-- Datos del proveedor --}}
        <div class="mt-6">
            <p class="font-mono font-bold text-xl">Datos de la orden</p>
            <hr class="w-full">
            <div class="flex justify-between my-2">
                <div class="space-y-2">
                    <p class="text-sm font-mono font-bold">
                        Razón social:
                        <span class="font-normal">
                            {{ $saleOrder->client->business_name }}
                            ({{ $saleOrder->client->iva_condition->name }})
                        </span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        CUIT:
                        <span class="font-normal">
                            {{ $saleOrder->client->cuit }}
                        </span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Fecha comprobante:
                        <span class="font-normal"> {{ Date::parse($saleOrder->registration_date)->format('d-m-Y') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Detalle de la compra de la compra --}}
        <div class="mt-6">
            <p class=" font-mono font-bold text-xl">Detalle de la orden</p>
            <hr class="w-full">

            {{-- Table for product_purchase --}}
            <div class="mt-5">
                <x-responsive-table>
                    <table class="min-w-full divide-y border">
                        <thead>
                            <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                                <th scope="col" class="px-3 py-3 whitespace-nowrap">
                                    Producto
                                </th>
                                <th scope="col" class="px-3 py-3 whitespace-nowrap">
                                    M2 unitario
                                </th>
                                <th scope="col" class="px-3 py-3 whitespace-nowrap">
                                    Unidades
                                </th>
                                <th scope="col" class="px-3 py-3 whitespace-nowrap">
                                    M2 totales
                                </th>
                                <th scope="col" class="px-3 py-3 whitespace-nowrap">
                                    @if ($client_discriminates_iva)
                                        Precio M2
                                    @else
                                        Precio M2 (+ IVA)
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stats as $stat)
                                <tr class="text-sm font-mono">
                                    <td class="px-6 py-3">
                                        <p>
                                            {{ $stat['name'] }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-3 text-center whitespace-nowrap">
                                        <p>
                                            {{ $stat['m2_unitary'] }} m²
                                        </p>
                                    </td>
                                    <td class="px-6 py-3 text-center whitespace-nowrap">
                                        <p>
                                            {{ $stat['quantity'] }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-3 text-center whitespace-nowrap">
                                        <p>
                                            {{ $stat['m2_total'] }} m²
                                        </p>
                                    </td>
                                    <td class="px-6 py-3 text-center whitespace-nowrap">
                                        <p>
                                            ${{ number_format($stat['m2_price'], 2, ',', '.') }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-3 text-center whitespace-nowrap">
                                        <p>
                                            ${{ number_format($stat['subtotal'], 2, ',', '.') }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-responsive-table>
            </div>

            @if ($client_discriminates_iva)
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
