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
            Cálculo de producción necesaria
        </h2>

        <div class="mt-6">
            <p class="font-mono font-bold text-xl">Datos de la orden</p>
            <hr class="w-full">
            <div class="flex justify-between my-2">
                <div class="space-y-2">
                    <p class="text-sm font-mono font-bold">
                        Orden de venta N° {{ $saleOrder->id }}
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Razón social:
                        <span class="font-normal">
                            {{ $saleOrder->client->business_name }}
                        </span>
                    </p>
                    <p class="text-sm font-mono font-bold">
                        Fecha comprobante:
                        <span class="font-normal"> {{ Date::parse($saleOrder->registration_date)->format('d-m-Y') }} </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Detalle de la compra de la compra --}}
        <div class="mt-6">
            <p class=" font-mono font-bold text-xl">Detalle de productos</p>
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
                                Cantidad requerida
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="uppercase text-sm font-mono">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <p>
                                        {{ $product['name'] }}
                                    </p>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-center">
                                    <p>
                                        {{ $product['quantity'] }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if (isset($previousProducts))
            <div class="mt-6">
                <p class=" font-mono font-bold text-xl">Producción necesaria</p>
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
                                    Stock actual
                                </th>
                                <th scope="col" class="px-6 py-1">
                                    Stock a producir
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($previousProducts as $product)
                                <tr class="uppercase text-sm font-mono">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        {{ $product['name'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        {{ $product['quantity'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        {{ $product['needed'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="mt-8">
                <p class="font-mono font-bold">¡No se pudo calcular la producción necesaria! Consulte a soporte técnico.
                </p>
            </div>
        @endif
    </div>
</div>
