<div class="max-w-5xl mx-auto bg-white py-6 px-10 my-6 rounded-lg">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl text-gray-800 leading-tight">¡Bienvenido a un nuevo concurso privado de precios, <span class="font-bold">{{ $supplier->business_name }}</span>!</h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
            <h2 class="font-bold text-xl text-gray-800 leading-tight mb-1 uppercase">Formulario de oferta</h2>
            <span class="text-xs text-gray-700 italic">
                Estimado proveedor: el envío de una oferta es considerado un compromiso de venta a la fecha acordada. Revise los precios y condiciones propuestas antes de enviar la oferta.
            </span>
            <br>
            <hr>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold mb-1">Detalle de lo requerido</h2>
            {{-- Products of tender --}}
            <ul class="list-disc list-inside ml-4">
                @foreach ($tender->products as $product)
                    <li class="text-sm italic">{{ $product->name }} (x {{ $product->pivot->quantity }})</li>
                @endforeach
            </ul>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Su oferta</h2>
            <span class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                El detalle discrimina IVA, descuentos, etc. Puede quitar de la lista los productos que no tiene disponible, como así también modificar la cantidad de cada uno.
            </span>
        </div>

        <div class="col-span-6">
            @if ($orderProducts)
                <div class="grid grid-cols-6 w-full text-center text-sm uppercase font-bold text-gray-600">
                    <div class="col-span-2 py-1">Producto</div>
                    <div class="col-span-1 py-1">Cantidad</div>
                    <div class="col-span-1 py-1">Costo unitario</div>
                    <div class="col-span-1 py-1"></div>
                    <div class="col-span-1 py-1">Subtotal</div>
                </div>

                <div class="grid grid-cols-6 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                    @foreach ($orderProducts as $index => $orderProduct)
                        <div class="col-span-2 flex">
                            <button type="button" wire:click.prevent="removeProduct({{ $index }})">
                                <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                            </button>
                            <select name="orderProducts[{{ $index }}][product_id]"
                                wire:model.lazy="orderProducts.{{ $index }}.product_id"
                                class="input-control w-full p-1 pl-3">
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
                            <x-jet-input type="number" min="1"
                                name="orderProducts[{{ $index }}][quantity]"
                                wire:model.lazy="orderProducts.{{ $index }}.quantity"
                                class="input-control w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1">
                            <x-jet-input type="number" min="1" name="orderProducts[{{ $index }}][price]"
                                wire:model.lazy="orderProducts.{{ $index }}.price"
                                class="input-control w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1 text-xs">
                            <span>
                                IVA
                            </span>
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
            <div class="grid grid-cols-6 gap-2">

                {{-- PARTE IZQUIERDA - BOTÓN AGREGAR PRODUCTO --}}
                <div
                    class="{{ $orderProducts ? 'col-span-4' : 'col-span-6' }}  mt-4 flex justify-center items-center gap-2">
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
                        class="col-span-1 flex flex-col justify-between text-left my-1 py-1 text-sm uppercase font-bold text-gray-600">
                        <span>Subtotal</span>
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

        <div class="col-span-3">
            <x-jet-label class="mb-2" value="Fecha de entrega" />
            <x-jet-input type="datetime-local" class="mt-1 block w-full"
                wire:model.defer="createForm.delivery_date" />
            <x-jet-input-error for="createForm.delivery_date" class="mt-2" />
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
            Registrar oferta
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
