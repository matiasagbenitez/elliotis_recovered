<div class="container py-8">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg">

        <div class="mb-4">
            <x-jet-label value="Tipo de compra a realizar" class="mb-2" />
            <select wire:model="type_of_purchase" class="input-control w-full">
                <option value="1">Compra detallada</option>
                <option value="2">Compra mixta</option>
            </select>
        </div>

        @if ($orderProducts && $type_of_purchase == 1)
            <table class="text-gray-600 min-w-full table-fixed" id="products_table">
                <thead>
                    <tr class="text-sm uppercase py-3 text-left">
                        <th scope="col" class="w-2/6 text-center">Producto</th>
                        <th scope="col" class="w-1/6 text-center">Unidades</th>
                        <th scope="col" class="w-1/6 text-center">Toneladas (TN)</th>
                        <th scope="col" class="w-1/6 text-center">Precio x TN</th>
                        <th scope="col" class="w-1/6 text-center">Subtotal</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orderProducts as $index => $orderProduct)
                        <tr>
                            <td>
                                <select name="orderProducts[{{ $index }}][product_id]"
                                    wire:model="orderProducts.{{ $index }}.product_id"
                                    class="input-control w-full">
                                    <option disabled value="">Seleccione un producto</option>
                                    @foreach ($allSublotsFormated as $sublot)
                                        <option value="{{ $sublot['id'] }}">
                                            {{ $sublot['text'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][quantity]"
                                    wire:model="orderProducts.{{ $index }}.unities"
                                    class="input-control w-full text-center" />
                            </td>
                            <td>
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][quantity]"
                                    wire:model="orderProducts.{{ $index }}.quantity"
                                    class="input-control w-full text-center" />
                            </td>
                            <td>
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][price_quantity]"
                                    wire:model="orderProducts.{{ $index }}.price_quantity"
                                    class="input-control w-full text-center" />
                            </td>
                            <td>
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][subtotal]"
                                    wire:model="orderProducts.{{ $index }}.subtotal"
                                    class="input-control w-full text-center" disabled />
                            </td>
                            <td>
                                <x-jet-danger-button type="button"
                                    wire:click.prevent="removeProduct({{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </x-jet-danger-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="w-2/6 my-5 items-center gap-3">
                <p class="text-gray-600 text-sm uppercase font-bold">Peso total en toneladas (TN)</p>
                <x-jet-input type="number" min="1" wire:model="totalWeight" class="input-control text-center w-full"/>
            </div>

            <hr>
            <div class="grid grid-cols-6 gap-2">

                {{-- PARTE DERECHA --}}
                @if ($orderProducts)
                    <div class="col-span-4">

                    </div>
                    <div
                        class="col-span-1 flex flex-col justify-between text-left my-1 py-1 text-sm uppercase font-bold text-gray-600">
                        <span>Subtotal sin IVA</span>
                        <span>IVA</span>
                        <span>Total</span>
                    </div>

                    <div class="col-span-1 text-sm uppercase text-gray-600">
                        {{-- Subtotal --}}
                        <div>
                            <div class="flex items-center">
                                <span>$</span>
                                <x-jet-input readonly disabled id="subtotal" type="number"
                                    class="border-none shadow-none p-1 w-full text-center"
                                    wire:model="createForm.subtotal" />
                            </div>
                            <x-jet-input-error for="createForm.subtotal" class="mt-2" />
                        </div>

                        {{-- IVA --}}
                        <div>
                            <div class="flex items-center">
                                <span>$</span>
                                <x-jet-input readonly disabled id="iva" type="number"
                                    class="border-none shadow-none p-1 w-full text-center"
                                    wire:model="createForm.iva" />
                            </div>
                            <x-jet-input-error for="createForm.iva" class="mt-2" />
                        </div>

                        {{-- Total --}}
                        <div>
                            <div class="flex items-center font-bold">
                                <span>$</span>
                                <x-jet-input readonly disabled id="total" type="number"
                                    class="border-none shadow-none p-1 w-full text-center"
                                    wire:model="createForm.total" />
                            </div>
                            <x-jet-input-error for="createForm.total" class="mt-2" />
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if ($orderProducts && $type_of_purchase == 2)
            <table class="text-gray-600 min-w-full table-fixed" id="products_table">
                <thead>
                    <tr class="text-sm uppercase py-3 text-left">
                        <th scope="col" class="w-2/3 text-center">Producto</th>
                        <th scope="col" class="w-1/3 text-center">Unidades</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orderProducts as $index => $orderProduct)
                        <tr>
                            <td>
                                <select name="orderProducts[{{ $index }}][product_id]"
                                    wire:model="orderProducts.{{ $index }}.product_id"
                                    class="input-control w-full">
                                    <option disabled value="">Seleccione un producto</option>
                                    @foreach ($allSublotsFormated as $sublot)
                                        <option value="{{ $sublot['id'] }}">
                                            {{ $sublot['text'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][quantity]"
                                    wire:model="orderProducts.{{ $index }}.unities"
                                    class="input-control w-full text-center" />
                            </td>
                            <td>
                                <x-jet-danger-button type="button"
                                    wire:click.prevent="removeProduct({{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </x-jet-danger-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="grid grid-cols-6 gap-2">
                <div class="col-span-2 my-5 items-center gap-3">
                    <p class="text-gray-600 text-sm uppercase text-center font-bold">Peso total en toneladas (TN)</p>
                    <x-jet-input type="number" min="1" wire:model="weightForm.totalWeight" class="input-control text-center w-full"/>
                </div>

                <div class="col-span-2 my-5 items-center gap-3">
                    <p class="text-gray-600 text-sm uppercase text-center font-bold">Precio por tonelada (TN)</p>
                    <x-jet-input type="number" min="1" wire:model="weightForm.price" class="input-control text-center w-full"/>
                </div>

                <div class="col-span-2 my-5 items-center gap-3">
                    <p class="text-gray-600 text-sm uppercase text-center font-bold">Subtotal carga</p>
                    <x-jet-input type="number" min="1" wire:model="weightForm.subtotal" class="input-control text-center w-full"/>
                </div>

                <div class="col-span-4"></div>

                <div class="col-span-2 flex items-center">
                    <span>$</span>
                    <x-jet-input readonly disabled id="iva" type="number"
                        class="p-1 w-full text-center"
                        wire:model="createForm.iva" />
                </div>
                <x-jet-input-error for="createForm.iva" class="mt-2" />

                <div class="col-span-4"></div>

                <div class="col-span-2 flex items-center">
                    <span>$</span>
                    <x-jet-input readonly disabled id="total" type="number"
                        class="p-1 w-full text-center"
                        wire:model="createForm.total" />
                </div>
                <x-jet-input-error for="createForm.total" class="mt-2" />
            </div>

            <hr>
        @endif

        <div class="flex flex-col {{ $orderProducts ? '' : 'items-center' }} gap-2 my-2">
            <div>
                <x-jet-secondary-button type="button" wire:click.prevent="addProduct">
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
