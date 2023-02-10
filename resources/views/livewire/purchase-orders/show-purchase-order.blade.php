<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.purchase-orders.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            {{-- PDF BUTTON --}}
            <a href="{{ route('admin.purchase-order-detail.pdf', $purchaseOrder) }}">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    @if (!$purchaseOrder->is_active)
        <div class="max-w-5xl mx-auto bg-red-100 flex justify-center items-center text-red-700 px-4 py-3 rounded relative gap-4 mb-5"
            role="alert">
            {{-- <div> --}}
            <i class="fas fa-ban mr-5 text-5xl"></i>
            {{-- </div> --}}
            <div class="flex flex-col">
                <p class="font-bold font-mono uppercase">Orden de compra anulada</p>
                <p class="font-mono text-sm">
                    La presente orden de compra no es válida ya que fue anulada por {{ $user_who_cancelled }}
                    el día {{ $purchaseOrder->updated_at->format('d-m-Y') }} a las
                    {{ $purchaseOrder->updated_at->format('H:i') }}.
                </p>
            </div>
        </div>
    @elseif ($purchaseOrder->its_done)
        <div class="max-w-5xl mx-auto bg-green-100 flex justify-center items-center text-green-700 px-4 py-3 rounded relative gap-4 mb-5""
            role="alert">
            {{-- <div> --}}
            <i class="fas fa-check mr-5 text-5xl"></i>
            {{-- </div> --}}
            <div class="flex flex-col">
                <p class="font-bold font-mono uppercase">Orden de compra procesada correctamente</p>
                <a href="{{ route('admin.purchases.show-detail', $purchase->id) }}" class="font-mono text-sm">
                    La presente orden fue procesada correctamente y asociada a la compra #{{ $purchase->id }}
                </a>
            </div>
        </div>
    @endif

    {{-- Purchase detail --}}
    <div class="max-w-5xl mx-auto bg-white p-10 rounded-lg border-2">
        <h2 class=" font-mono font-semibold text-2xl text-gray-800 leading-tight mb-4 uppercase text-center">
            Detalle de orden de compra N° {{ $purchaseOrder->id }}
        </h2>

        {{-- Datos del proveedor --}}
        <div class="mt-6">
            <p class="font-mono font-bold text-xl">Datos de la orden</p>
            <hr class="w-full">
            <div class="flex justify-between my-2">
                <div class="space-y-1">
                    <p class="text-sm font-mono font-bold">
                        Razón social:
                        <span class="font-normal">{{ $data['supplier']}}</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Condición ante IVA:
                        <span class="font-normal">{{ $data['iva_condition'] }} ({{ $data['discriminate'] }})</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Fecha registro:
                        <span class="font-normal">{{ $data['date'] }}</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Peso total:
                        <span class="font-normal">{{ $data['total_weight'] }}</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Tipo de compra:
                        <span class="font-normal">{{ $data['type_of_purchase'] }}</span>
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
                            <tr
                                class="text-center text-gray-500 uppercase text-sm  font-mono font-thin whitespace-nowrap">
                                <th scope="col" class="px-6 py-2 w-1/5">
                                    {{ $titles['product'] }}
                                </th>
                                <th scope="col" class="px-6 py-2 w-1/5">
                                    {{ $titles['quantity'] }}
                                </th>
                                <th scope="col" class="px-6 py-2 w-1/5">
                                    {{ $titles['tn_total'] }}
                                </th>
                                <th scope="col" class="px-6 py-2 w-1/5">
                                    {{ $titles['tn_price'] }}
                                </th>
                                <th scope="col" class="px-6 py-2 w-1/5">
                                    {{ $titles['subtotal'] }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stats as $product)
                                <tr class="uppercase text-sm font-mono">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        {{ $product['name'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        {{ $product['quantity'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        {{ $product['tn_total'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        {{ $product['tn_price'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        {{ $product['subtotal'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-responsive-table>
            </div>

            @if ($supplier_discriminates_iva)
                {{-- Totales --}}
                <div class="mt-5 flex flex-col items-end px-6 space-y-2">
                    <p class="text-sm font-mono font-bold">
                        Subtotal:
                        <span class="font-normal">{{ $totals['subtotal'] }}</span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        IVA:
                        <span class="font-normal">{{ $totals['iva'] }}</span>
                    </p>
                    <p class="font-mono font-bold text-lg">
                        Total:
                        <span>{{ $totals['total'] }}</span>
                    </p>
                </div>
            @else
                {{-- Totales --}}
                <div class="mt-5 flex flex-col items-end px-6 space-y-2">
                    <p class="font-mono font-bold text-lg">
                        Total:
                        <span>{{ $totals['total'] }}</span>
                    </p>
                </div>
            @endif

            <div class="mt-2 p-2 text-xs border-1 border uppercase text-center">
                Detalle de orden compra para uso interno. Documento no válido como factura.
            </div>

        </div>

    </div>
