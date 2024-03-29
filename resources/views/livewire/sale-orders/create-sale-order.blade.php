<div class="max-w-7xl mx-auto bg-white p-10 my-6 rounded-lg">

    <x-slot name="header">
        <a href="{{ route('admin.sale-orders.index') }}">
            <x-jet-button>
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </x-jet-button>
        </a>
    </x-slot>


    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
            <h2 class="font-bold text-xl text-gray-800 leading-tight mb-1 uppercase">Registrar una nueva orden de venta
            </h2>
            <hr>
        </div>
        {{-- Supplier id --}}
        <div class="col-span-3">
            <x-jet-label class="mb-2" for="client_id" value="Cliente (*)" />
            <select id="client_id" class="input-control w-full" wire:model="createForm.client_id">
                <option value="" disabled>Seleccione un cliente</option>
                @foreach ($clients as $client)
                    <option value="{{ $client['id'] }}">{{ $client['name'] }}
                        &nbsp;
                        [{{ $client['iva_condition'] }} - {{ $client['discriminates_iva'] }}]</option>
                @endforeach
            </select>
            <x-jet-input-error for="createForm.client_id" class="mt-2" />
        </div>

        <div class="col-span-3">
            <x-jet-label class="mb-2" for="date" value="Fecha de la orden de venta (*)" />
            <x-jet-input id="date" type="date" class="mt-1 block w-full"
                wire:model.defer="createForm.registration_date" />
            <x-jet-input-error for="createForm.registration_date" class="mt-2" />
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Detalle de compra</h2>
            <hr>
            @if ($client_discriminates_iva)
                <span class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    El cliente discrimina IVA, por lo tanto, el precio de los productos debe ser el precio sin IVA.
                </span>
            @else
                <span class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    El cliente no discrimina IVA, por lo tanto, el precio de los productos debe ser el precio final.
                </span>
            @endif
        </div>

        <div class="col-span-6">
            @if ($orderProducts)
                <div class="grid grid-cols-8 w-full text-center text-sm uppercase font-bold text-gray-600">
                    <div class="col-span-3 py-1">Producto</div>
                    <div class="py-1">M2 unitario</div>
                    <div class="py-1">Unidades</div>
                    <div class="py-1">M2 totales</div>
                    <div class="py-1">
                        @if ($client_discriminates_iva)
                            Precio M2
                        @else
                            Precio M2 (+ IVA)
                        @endif
                    </div>
                    <div class="py-1">Subtotal</div>
                </div>

                <div class="grid grid-cols-8 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                    @foreach ($orderProducts as $index => $orderProduct)
                        <div class="col-span-3 flex">
                            <button type="button" wire:click.prevent="removeProduct({{ $index }})">
                                <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                            </button>
                            <select name="orderProducts[{{ $index }}][product_id]"
                                wire:model.lazy="orderProducts.{{ $index }}.product_id"
                                wire:change="updatePrice({{ $index }})" class="input-control w-full p-1 pl-3">
                                <option disabled value="">Seleccione un producto</option>
                                @foreach ($allProducts as $product)
                                    {{-- Disable product options that are already in the $orderProducts[][] --}}
                                    <option value="{{ $product->id }}"
                                        {{ $this->isProductInOrder($product->id) ? 'disabled' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1">
                            <x-jet-input readonly disabled type="number" min="1"
                                name="orderProducts[{{ $index }}][m2_unitary]"
                                wire:model.lazy="orderProducts.{{ $index }}.m2_unitary"
                                class="border-none shadow-none w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1">
                            <x-jet-input type="number" min="1"
                                name="orderProducts[{{ $index }}][quantity]"
                                wire:model.lazy="orderProducts.{{ $index }}.quantity"
                                class="input-control w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1">
                            <x-jet-input readonly disabled type="number" min="1"
                                name="orderProducts[{{ $index }}][m2_total]"
                                wire:model.lazy="orderProducts.{{ $index }}.m2_total"
                                class="border-none shadow-none w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1">
                            <x-jet-input type="number" min="1"
                                name="orderProducts[{{ $index }}][m2_price]"
                                wire:model="orderProducts.{{ $index }}.m2_price"
                                class="input-control w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1 flex items-center">
                            $
                            <x-jet-input disabled readonly type="number" min="1"
                                name="orderProducts[{{ $index }}][subtotal]"
                                wire:model.lazy="orderProducts.{{ $index }}.subtotal"
                                class="input-control w-full p-1 text-center border-none shadow-none" />
                        </div>
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
            <hr>
            <div class="grid grid-cols-8 gap-2">

                {{-- PARTE IZQUIERDA - BOTÓN AGREGAR PRODUCTO --}}
                <div
                    class="{{ $orderProducts ? 'col-span-6' : 'col-span-8' }}  mt-4 flex justify-center items-center gap-2">
                    <div>
                        <x-jet-button type="button" wire:click.prevent="addProduct" class="px-3">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar producto
                        </x-jet-button>
                    </div>
                    {{-- @if ($orderProducts)
                        <div>
                            <x-jet-secondary-button type="button" wire:click="showProducts" class="px-3">
                                <i class="fas fa-cogs"></i>
                            </x-jet-secondary-button>
                        </div>
                    @endif --}}
                </div>


                {{-- PARTE DERECHA --}}
                @if ($orderProducts)
                    <div
                        class="col-span-1 flex flex-col justify-between text-center my-1 py-1 text-sm uppercase font-bold text-gray-600">
                        @if ($client_discriminates_iva)
                            <span>Subtotal</span>
                            <span>IVA</span>
                        @endif
                        <span>Total</span>
                    </div>

                    <div class="col-span-1 text-sm uppercase text-gray-600">
                        {{-- Subtotal --}}
                        @if ($client_discriminates_iva)
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
                @endif
            </div>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Información adicional</h2>
            <hr>
        </div>

        {{-- Observations --}}
        <div class="col-span-6">
            <x-jet-label class="mb-2" for="observations" value="Observaciones" />
            <textarea id="observations" class="input-control w-full" wire:model.defer="createForm.observations"
                placeholder="Aquí puede escribir las observaciones que considere necesarias..."></textarea>
            <x-jet-input-error for="createForm.observations" class="mt-2" />
        </div>

    </div>

    <div class="flex justify-end mt-6" wire:click="save">
        <x-jet-button class="px-6 col-span-2">
            Registrar orden de venta
        </x-jet-button>
    </div>

</div>

@push('script')
    <script>
        Livewire.on('error', message => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#1f2937',
            });
        });
    </script>
@endpush
