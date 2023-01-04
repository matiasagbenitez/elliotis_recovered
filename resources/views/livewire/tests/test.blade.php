<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Tests</h1>
        </div>
    </x-slot>

    <div>
        <p class="font-bold uppercase">Producto de la orden: <span class="font-normal normal-case">{{ $purchaseProduct->name }}</span></p>
        <p class="font-bold uppercase">Cantidad: <span class="font-normal normal-case">{{ $purchaseQuantity }}</span></p>
    </div>

    <div>
        <p class="font-bold uppercase">Productos anteriores:</p>
        <ul class="ml-10">
            @foreach ($previousProducts as $product)
                <li class="list-disc">{{ $product['name'] }}</li>
            @endforeach
        </ul>
    </div>

    <table class="min-w-full divide-y border">
        <thead>
            <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                <th scope="col" class="w-1/2 px-4 py-2">
                    Producto
                </th>
                <th scope="col" class="w-1/4 px-4 py-2">
                    Stock actual
                </th>
                <th scope="col" class="w-1/4 px-4 py-2">
                    Producción necesaria
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($previousProducts as $product)
                <tr class="uppercase text-sm font-mono">
                    <td class="px-6 py-2 text-center">
                        <p class="text-sm uppercase">
                            {{ $product['name'] }}
                        </p>
                    </td>
                    <td class="px-6 py-2 text-center">
                        <p class="text-sm uppercase">
                            {{ $product['quantity'] }}
                        </p>
                    </td>
                    <td class="px-6 py-2 text-center">
                        <p class="text-sm uppercase">
                            {{ $product['needed'] }}
                        </p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
