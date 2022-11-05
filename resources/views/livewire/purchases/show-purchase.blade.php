<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.purchases.index') }}">
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
            Detalle de compra N° {{ $purchase->id }}
        </h2>

        {{-- Datos del proveedor --}}
        <div class="mt-6">
            <p class="font-mono font-bold text-xl">Datos del proveedor</p>
            <hr class="w-full">
            <div class="flex justify-between my-2">
                <div class="w-1/2 space-y-2">
                    <p class="text-sm  font-mono font-bold">
                        Razón social:
                        <span class="font-normal">{{ $purchase->supplier->business_name }}</span>
                    </p>
                    <p class="text-sm  font-mono font-bold">
                        CUIT:
                        <span class="font-normal">{{ $purchase->supplier->cuit }}</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Condición ante IVA:
                        <span class="font-normal">{{ $purchase->supplier->iva_condition->name }}</span>
                    </p>
                </div>
                {{-- <div class="w-1/2 space-y-2">
                    <p class="text-sm font-mono font-bold">Dirección: <span class=" font-mono font-normal">{{ $purchase->supplier->adress }}, {{ $purchase->supplier->locality->name }}</span></p>
                    <p class="text-sm font-mono font-bold">Teléfono de contacto: <span class=" font-mono font-normal">{{ $purchase->supplier->phone }}</span></p>
                    <p class="text-sm font-mono font-bold">Correo electrónico: <span class=" font-mono font-normal">{{ $purchase->supplier->email }}</span></p>
                </div> --}}
                @if (!$purchase->is_active)
                    <div class="w-1/2 flex justify-center items-center border border-red-700 text-red-700 px-4 py-3 rounded relative gap-4"
                        role="alert">
                        {{-- <div> --}}
                        <i class="fas fa-ban text-5xl"></i>
                        {{-- </div> --}}
                        <div class="flex flex-col">
                            <p class="font-bold font-mono uppercase">Compra anulada</p>
                            <p class="font-mono text-sm">
                                La presente compra no es válida ya que fue anulada por {{ $user_who_cancelled }}
                                el día {{ $purchase->updated_at->format('d-m-Y') }} a las
                                {{ $purchase->updated_at->format('H:i:s') }} hs
                            </p>
                            <p class="font-bold font-mono uppercase mt-2">Motivo</p>
                            <p class="font-mono text-sm">
                                {{ $purchase->cancel_reason }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Datos de la compra --}}
        <div class="mt-6">
            <p class=" font-mono font-bold text-xl">Datos de la compra</p>
            <hr class="w-full">
            <div class="flex justify-between my-2">
                <div class="w-1/2 space-y-2">
                    <p class="text-sm  font-mono font-bold">Fecha de compra: <span
                            class="font-normal">{{ Date::parse($purchase->date)->format('d-m-Y') }}</span></p>
                    <p class="text-sm  font-mono font-bold">Método de pago: <span
                            class="font-normal">{{ $purchase->payment_method->name }}</span></p>
                    <p class="text-sm  font-mono font-bold">Condición de pago: <span
                            class="font-normal">{{ $purchase->payment_condition->name }}</span></p>
                    @if ($purchase->supplier_order_id)
                        <p class="text-sm  font-mono font-bold">
                            Orden de compra:
                            <span class="font-normal">#{{ $purchase->supplier_order_id }}</span>
                            <a href="{{ route('admin.purchase-orders.show-detail', $purchase->supplier_order_id) }}" class="text-xs italic font-normal text-blue-500">(ver detalle)</a>
                        </p>
                    @endif
                </div>
                <div class="w-1/2 space-y-2">
                    <p class="text-sm font-mono font-bold">Tipo de comprobante: <span
                            class="font-normal">{{ $purchase->voucher_type->name }}</span></p>
                    <p class="text-sm font-mono font-bold">Número de comprobante: <span
                            class="font-normal">{{ $purchase->voucher_number }}</span></p>
                    <p class="text-sm  font-mono font-bold">Peso neto:
                        @if ($purchase->weight)
                            <span class="font-normal">{{ $purchase->weight }} Kgs.</span>
                        @else
                            <span class="font-normal">N/A</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Detalle de la compra de la compra --}}
        <div class="mt-6">
            <p class=" font-mono font-bold text-xl">Detalle de la compra</p>
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
                        @foreach ($purchase->products as $product)
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
                                            // $price_with_iva = $product->pivot->price * 1.21;
                                            // $price_with_iva = number_format($price_with_iva, 2, ',', '.');
                                        @endphp

                                        @if ($purchase->supplier->iva_condition->discriminate)
                                            ${{ number_format($product->pivot->price, 2, ',', '.') }}
                                        @else
                                            ${{ number_format($product->pivot->price, 2, ',', '.') }}
                                            {{-- ${{ $price_with_iva }} --}}
                                        @endif
                                    </p>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-right">
                                    <p>
                                        @php
                                            // $subtotal_with_iva = $product->pivot->quantity * $product->pivot->price * 1.21;
                                            // $subtotal_with_iva = number_format($subtotal_with_iva, 2, ',', '.');
                                        @endphp

                                        @if ($purchase->supplier->iva_condition->discriminate)
                                            ${{ number_format($product->pivot->quantity * $product->pivot->price, 2, ',', '.') }}
                                        @else
                                            ${{ number_format($product->pivot->quantity * $product->pivot->price, 2, ',', '.') }}
                                            {{-- ${{ $subtotal_with_iva }} --}}
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($purchase->supplier->iva_condition->discriminate)
                {{-- Totales --}}
                <div class="mt-5 flex flex-col items-end px-6 space-y-2">
                    <p class="text-sm font-mono font-bold">
                        Subtotal:
                        <span class="font-normal">${{ number_format($purchase->subtotal, 2, ',', '.') }}</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        IVA:
                        <span class="font-normal">${{ number_format($purchase->iva, 2, ',', '.') }}</span>
                    </p>
                    <p class="font-mono font-bold text-lg">
                        Total:
                        <span>${{ number_format($purchase->total, 2, ',', '.') }}</span>
                    </p>
                </div>
            @else
                {{-- Totales --}}
                <div class="mt-5 flex flex-col items-end px-6 space-y-2">
                    <p class="font-mono font-bold text-lg">
                        Total:
                        <span>${{ number_format($purchase->total, 2, ',', '.') }}</span>
                    </p>
                </div>
            @endif

            <div class="mt-2 p-2 text-xs border-1 border uppercase text-center">
                Detalle de compra para uso interno. Documento no válido como factura.
            </div>

        </div>

    </div>
