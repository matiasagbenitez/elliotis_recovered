<div class="container py-8">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg">

        <div>
            <h1 class="text-2xl font-bold">Orden de venta</h1>
            <select name="" id="" wire:model="productSale">
                <option value="">Seleccione un cliente</option>
                @foreach ($productSales as $productSale)
                    <option value="{{ $productSale->sale_id }}">{{ $productSale->sale_id }}</option>
                @endforeach
            </select>
            {{-- <x-jet-button wire:click="$emit('updateItems', '{{ $productSale }}')">Agregar</x-jet-button> --}}
        </div>

        @if ($orderProducts)
            <table class="text-gray-600 min-w-full table-fixed" id="products_table">
                <thead>
                    <tr class="text-sm uppercase py-2 text-left">
                        <th scope="col" class="w-3/6">Producto</th>
                        <th scope="col" class="w-1/6">Cantidad</th>
                        <th scope="col" class="w-1/6">Precio</th>
                        <th scope="col" class="w-1/6">Subtotal</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orderProducts as $index => $orderProduct)
                        <tr>
                            <td>
                                <select name="orderProducts[{{ $index }}][product_id]"
                                    wire:model="orderProducts.{{ $index }}.product_id" class="input-control w-full">
                                    <option disabled value="">Seleccione un producto</option>
                                    @foreach ($allProducts as $product)

                                        {{-- Disable product options that are already in the $orderProducts[][] --}}
                                        <option value="{{ $product->id }}"
                                            {{ $this->isProductInOrder($product->id) ? 'disabled' : '' }}>
                                            {{ $product->name }}
                                        </option>


                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <x-jet-input type="number" min="1" name="orderProducts[{{ $index }}][quantity]"
                                    wire:model="orderProducts.{{ $index }}.quantity" class="input-control w-full" />
                            </td>
                            <td>
                                <x-jet-input type="number" min="1" name="orderProducts[{{ $index }}][price]"
                                    wire:model="orderProducts.{{ $index }}.price" class="input-control w-full" />
                            </td>
                            <td>
                                <x-jet-input type="number" min="1" name="orderProducts[{{ $index }}][subtotal]"
                                    wire:model="orderProducts.{{ $index }}.subtotal" class="input-control w-full" />
                            </td>
                            <td>
                                <x-jet-danger-button type="button" wire:click.prevent="removeProduct({{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </x-jet-danger-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="flex flex-col {{ $orderProducts ? '' : 'items-center' }} gap-2 my-2">
            <div>
                <x-jet-secondary-button type="button" wire:click="$emit('addProduct')">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar producto
                </x-jet-secondary-button>
            </div>

            @if ($orderProducts)
            <div>
                <x-jet-button type="button" wire:click="showProducts">
                    <i class="fas fa-save mr-2"></i>
                    Guardar
                </x-jet-button>
            </div>
            @endif
        </div>

    </div>

</div>
