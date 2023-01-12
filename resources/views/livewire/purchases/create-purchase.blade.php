<div class="max-w-7xl mx-auto bg-white p-10 my-6 rounded-lg">

    <x-slot name="header">
        <a href="{{ route('admin.purchases.index') }}">
            <x-jet-button>
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </x-jet-button>
        </a>
    </x-slot>


    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
            <h2 class="font-bold text-xl text-gray-800 leading-tight mb-1 uppercase">Registrar una nueva compra</h2>
            <hr>
        </div>
        {{-- FECHA --}}
        <div class="col-span-2">
            <x-jet-label class="mb-2" for="date" value="Fecha de la compra (*)" />
            <x-jet-input id="date" type="date" class="mt-1 block w-full" wire:model.defer="createForm.date" />
            <x-jet-input-error for="createForm.date" class="mt-2" />
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Datos del proveedor</h2>
            <hr>
        </div>

        {{-- Supplier id --}}
        <div class="col-span-2">
            <x-jet-label class="mb-2" for="supplier_id" value="Proveedor (*)" />
            <select id="supplier_id" class="input-control w-full" wire:model="createForm.supplier_id">
                <option value="" disabled>Seleccione un proveedor</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}
                        &nbsp;
                        [{{ $supplier['iva_condition'] }} - {{ $supplier['discriminates_iva'] }}]</option>
                @endforeach
            </select>
            <x-jet-input-error for="createForm.supplier_id" class="mt-2" />
        </div>

        {{-- Has order associated? --}}
        <div class="col-span-2">
            <x-jet-label class="mb-2" value="¿Tiene orden asociada?" />
            <select id="" class="input-control w-full" wire:model="has_order_associated">Seleccione una
                opción</option>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </div>

        {{-- Order id --}}
        <div class="col-span-2">
            <x-jet-label class="mb-2" for="order_id" value="Orden asociada" />
            <select id="order_id" class="input-control w-full" wire:model="createForm.supplier_order_id"
                {{ $has_order_associated ? '' : 'disabled' }}>
                <option value="">Seleccione una orden</option>
                @foreach ($supplier_orders as $order)
                    <option value="{{ $order->id }}">Orden #{{ $order->id }} ({{ Date::parse($order->registration_date)->format('d-m-Y') }})</option>
                @endforeach
            </select>
            <x-jet-input-error for="createForm.supplier_order_id" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-jet-label value="Tipo de compra a realizar" class="mb-2" />
            <select wire:model="type_of_purchase" class="input-control w-full">
                <option value="1">Compra detallada</option>
                <option value="2">Compra mixta</option>
            </select>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Detalle de orden</h2>
            <hr>
            @if (!$supplier_discriminates_iva)
                <span class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    El cliente no discrimina IVA, por lo tanto, el precio de los productos debe ser el precio final.
                </span>
            @else
                <span class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    El cliente discrimina IVA, por lo tanto, el precio de los productos debe ser el precio sin IVA.
                </span>
            @endif
        </div>

        <div class="col-span-6">
            @if ($orderProducts)
                <div class="grid grid-cols-8 w-full text-center text-sm uppercase font-bold text-gray-600">
                    <div class="col-span-4 py-1">Producto</div>
                    @if ($type_of_purchase == 1)
                        <div class="py-1">Unidades</div>
                        <div class="py-1">Toneladas (TN)</div>
                        <div class="py-1">
                            @if ($supplier_discriminates_iva)
                                Precio TN
                            @else
                                Precio TN (+ IVA)
                            @endif
                        </div>
                        <div class="py-1">Subtotal</div>
                    @elseif ($type_of_purchase == 2)
                        <div class="col-span-4 py-1">Unidades</div>
                    @endif
                </div>

                <div class="grid grid-cols-8 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                    @foreach ($orderProducts as $index => $orderProduct)
                        <div class="col-span-4 flex">
                            <button type="button" wire:click.prevent="removeProduct({{ $index }})">
                                <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                            </button>
                            <select name="orderProducts[{{ $index }}][product_id]"
                                wire:model.lazy="orderProducts.{{ $index }}.product_id"
                                class="input-control w-full p-1 pl-3">
                                <option disabled value="">Seleccione un producto</option>
                                @foreach ($allProducts as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $this->isProductInOrder($product->id) ? 'disabled' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($type_of_purchase == 1)
                            <div class="col-span-1">
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][quantity]"
                                    wire:model.lazy="orderProducts.{{ $index }}.quantity"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                            <div class="col-span-1">
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][tn_total]"
                                    wire:model.lazy="orderProducts.{{ $index }}.tn_total"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                            <div class="col-span-1">
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][tn_price]"
                                    wire:model="orderProducts.{{ $index }}.tn_price"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                            <div class="col-span-1 flex items-center">
                                $
                                <x-jet-input disabled readonly type="number" min="1"
                                    name="orderProducts[{{ $index }}][subtotal]"
                                    wire:model.lazy="orderProducts.{{ $index }}.subtotal"
                                    class="input-control w-full p-1 text-center border-none shadow-none" />
                            </div>
                        @elseif ($type_of_purchase == 2)
                            <div class="col-span-4">
                                <x-jet-input type="number" min="1"
                                    name="orderProducts[{{ $index }}][quantity]"
                                    wire:model.lazy="orderProducts.{{ $index }}.quantity"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                        @endif
                    @endforeach
                </div>
                <x-jet-input-error for="orderProducts.*.product_id" class="mt-2" />
            @else
                <p class="text-center">¡No hay productos! Intenta agregar alguno con el botón <span
                        class="font-bold">"Agregar producto"</span>.</p>
            @endif


        </div>

        {{-- PARTE INFERIOR --}}
        <div class="col-span-6">
            <hr class="mb-2">
            <div class="grid grid-cols-8 gap-2">

                {{-- PARTE IZQUIERDA - BOTÓN AGREGAR PRODUCTO --}}
                <div
                    class="{{ $orderProducts ? 'col-span-4' : 'col-span-8' }}  mt-4 flex justify-center items-center gap-2">
                    <div>
                        <x-jet-button type="button" wire:click.prevent="addProduct" class="px-3">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar producto
                        </x-jet-button>
                    </div>
                </div>


                {{-- PARTE DERECHA --}}
                @if ($orderProducts)
                    @if ($type_of_purchase == 1)
                        <div
                            class="col-span-1 flex flex-col justify-between text-center my-1 py-1 text-sm uppercase font-bold text-gray-600">
                            <span>Total TN</span>
                        </div>

                        <div class="col-span-1">
                            <x-jet-input readonly disabled type="number" min="1" name="tn_final"
                                wire:model="tn_final"
                                class="border-none shadow-none input-control w-full p-1 text-center" />
                        </div>

                        <div
                            class="col-span-1 flex flex-col justify-between text-center my-1 py-1 text-sm uppercase font-bold text-gray-600">
                            @if ($supplier_discriminates_iva)
                                <span>Subtotal</span>
                                <span>IVA</span>
                            @endif
                            <span>Total</span>
                        </div>

                        <div class="col-span-1 text-sm uppercase text-gray-600">
                            {{-- Subtotal --}}
                            @if ($supplier_discriminates_iva)
                                <div>
                                    <div class="flex items-center">
                                        <span>$</span>
                                        <x-jet-input readonly disabled id="subtotal" type="number"
                                            class="border-none shadow-none p-1 w-full text-center"
                                            wire:model.defer="createForm.subtotal" />
                                    </div>
                                    <x-jet-input-error for="createForm.subtotal" class="mt-2" />
                                </div>

                                {{-- IVA --}}
                                <div>
                                    <div class="flex items-center">
                                        <span>$</span>
                                        <x-jet-input readonly disabled id="iva" type="number"
                                            class="border-none shadow-none p-1 w-full text-center"
                                            wire:model.defer="createForm.iva" />
                                    </div>
                                    <x-jet-input-error for="createForm.iva" class="mt-2" />
                                </div>
                            @endif

                            {{-- Total --}}
                            <div>
                                <div class="flex items-center font-bold">
                                    <span>$</span>
                                    <x-jet-input readonly disabled id="total" type="number"
                                        class="border-none shadow-none p-1 w-full text-center"
                                        wire:model.defer="createForm.total" />
                                </div>
                                <x-jet-input-error for="createForm.total" class="mt-2" />
                            </div>
                        </div>
                    @elseif($type_of_purchase == 2)
                        <div
                            class="col-span-2 flex flex-col gap-2 text-right justify-evenly my-1 pr-3 py-1 text-sm uppercase font-bold text-gray-600">
                            <span>Total toneladas (TN)</span>
                            @if ($supplier_discriminates_iva)
                                <span>Precio tonelada</span>
                                <span>Subtotal</span>
                                <span>IVA</span>
                            @else
                                <span>Precio tonelada (+ IVA)</span>
                            @endif
                            <span>Total</span>
                        </div>
                        <div
                            class="col-span-2 flex flex-col gap-2 text-right justify-between my-1 py-1 text-sm uppercase text-gray-600">
                            <div class="col-span-1">
                                <x-jet-input type="number" min="1" wire:model="weightForm.totalTn"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                            <div class="col-span-1">
                                <x-jet-input type="number" min="1" wire:model="weightForm.priceTn"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                            @if ($supplier_discriminates_iva)
                                <div class="col-span-1 flex items-center">
                                    <span>$</span>
                                    <x-jet-input readonly disabled type="number" min="1"
                                        name="createForm.subtotal"
                                        wire:model="createForm.subtotal"
                                        class="border-none shadow-none input-control w-full p-1 text-center" />
                                </div>
                                <div class="col-span-1 flex items-center">
                                    <span>$</span>
                                    <x-jet-input readonly disabled type="number" min="1"
                                        name="createForm.iva"
                                        wire:model="createForm.iva"
                                        class="border-none shadow-none input-control w-full p-1 text-center" />
                                </div>
                            @endif
                            <div class="col-span-1 flex items-center">
                                <span>$</span>
                                <x-jet-input readonly disabled type="number" min="1"
                                    name="createForm.total"
                                    wire:model="createForm.total"
                                    class="border-none shadow-none input-control w-full p-1 text-center" />
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Información del pago</h2>
            <hr>
        </div>

        {{-- Select for payment_methods --}}
        <div class="col-span-3">
            <x-jet-label class="mb-2" for="payment_method" value="Método de pago (*)" />
            <select id="payment_method" class="input-control w-full" wire:model.defer="createForm.payment_method_id">
                <option value="">Seleccione un método de pago</option>
                @foreach ($payment_methods as $payment_method)
                    <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="createForm.payment_method_id" class="mt-2" />
        </div>

        {{-- Select for payment_conditions --}}
        <div class="col-span-3">
            <x-jet-label class="mb-2" for="payment_condition" value="Condición de pago (*)" />
            <select id="payment_condition" class="input-control w-full"
                wire:model.defer="createForm.payment_condition_id">
                <option value="">Seleccione una condición de pago</option>
                @foreach ($payment_conditions as $payment_condition)
                    <option value="{{ $payment_condition->id }}">{{ $payment_condition->name }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="createForm.payment_condition_id" class="mt-2" />
        </div>

        {{-- Select for voucher_type_id --}}
        <div class="col-span-3">
            <x-jet-label class="mb-2" for="voucher_type_id" value="Tipo de comprobante (*)" />
            <select id="voucher_type_id" class="input-control w-full" wire:model.defer="createForm.voucher_type_id">
                <option value="">Seleccione un tipo de comprobante</option>
                @foreach ($voucher_types as $voucher_type)
                    <option value="{{ $voucher_type->id }}">{{ $voucher_type->name }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="createForm.voucher_type_id" class="mt-2" />
        </div>

        {{-- Voucher number --}}
        <div class="col-span-3">
            <x-jet-label class="mb-2" for="voucher_number" value="Número de comprobante" />
            <x-jet-input id="voucher_number" type="number" class="mt-1 block w-full"
                placeholder="Ingrese el número de comprobante" wire:model.defer="createForm.voucher_number" />
            <x-jet-input-error for="createForm.voucher_number" class="mt-2" />
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Información adicional</h2>
            <hr>
        </div>

        {{-- Weight --}}
        <div class="col-span-3">
            <div>
                <x-jet-label class="mb-2" for="weight" value="Peso neto" />
                <x-jet-input id="weight" type="number" class="mt-1 block w-full"
                    placeholder="Ingrese el peso neto de la compra" wire:model.defer="createForm.weight" />
                <x-jet-input-error for="createForm.weight" class="mt-2" />
            </div>

            {{-- Input type file for weight_voucher --}}
            <div>
                <x-jet-label class="my-2" for="weight_voucher" value="Comprobante de peso" />
                <input type="file" id="weight_voucher" class="input-control w-full"
                    wire:model.defer="createForm.weight_voucher" accept="image/*">
                <x-jet-input-error for="createForm.weight_voucher" class="mt-2" />
            </div>
        </div>

        {{-- Image preview --}}
        {{-- @if ($createForm['weight_voucher'])
            <div class="col-span-3 items-center justify-center">
                <img class="mt-2 h-48" src="{{ $createForm['weight_voucher']->temporaryUrl() }}"
                    alt="Comprobante de peso">
            </div>
        @endif --}}

        {{-- Observations --}}
        <div class="col-span-6">
            <x-jet-label class="mb-2" for="observations" value="Observaciones" />
            <textarea id="observations" class="input-control w-full" wire:model.defer="createForm.observations"
                placeholder="Aquí puede escribir las observaciones que considere necesarias..."></textarea>
            <x-jet-input-error for="createForm.observations" class="mt-2" />
        </div>

    </div>

    {{-- <div class="flex justify-end mt-6" wire:click="save"> --}}
    <div class="flex justify-end mt-6" wire:click="save">
        <x-jet-button class="px-6 col-span-2">
            Registrar compra
        </x-jet-button>
    </div>

</div>
