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
                <li class="list-disc">{{ $product->name }}</li>
            @endforeach
        </ul>
    </div>

</div>
