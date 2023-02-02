<div class="max-w-6xl mx-auto bg-white p-10 my-6 rounded-lg">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl text-gray-800 leading-tight">¡Bienvenido a un nuevo concurso privado de precios, <span
                    class="font-bold">{{ $supplier->business_name }}</span>!</h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
            <h2 class="font-bold text-xl text-gray-800 leading-tight mb-1 uppercase">Formulario de oferta para la
                licitación</h2>
            <hr>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold mb-1">Detalle de productos requeridos</h2>
            {{-- Products of tender --}}
            <ul class="list-disc list-inside ml-4">
                @foreach ($tender->products as $product)
                    <li class="text-gray-500 font-semibold">x {{ $product->pivot->quantity }} unidades de <span
                            class="font-bold">{{ $product->name }}</span></li>
                @endforeach
            </ul>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Detalle de oferta</h2>
            <span class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Puede quitar de la lista los productos que no tiene disponible, como así también modificar la cantidad
                de cada uno.
            </span>
        </div>

        <div class="col-span-6">
            @if ($orderProducts)
                <div class="grid grid-cols-8 w-full text-center text-sm uppercase font-bold text-gray-600">
                    <div class="col-span-4 py-1">Producto</div>
                    <div class="col-span-4 py-1">Unidades</div>
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

                        <div class="col-span-4">
                            <x-jet-input type="number" min="1"
                                name="orderProducts[{{ $index }}][quantity]"
                                wire:model.lazy="orderProducts.{{ $index }}.quantity"
                                class="input-control w-full p-1 text-center" />
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
            <div class="col-span-6 mt-4 flex justify-center items-center gap-2">
                <x-jet-button type="button" wire:click.prevent="addProduct" class="px-3">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar producto
                </x-jet-button>
            </div>
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Información adicional</h2>
            <hr>
        </div>

        <div class="col-span-3">
            <x-jet-label class="mb-2" value="Fecha de entrega" />
            <x-jet-input type="datetime-local" class="mt-1 block w-full" wire:model.defer="createForm.delivery_date" />
            <x-jet-input-error for="createForm.delivery_date" class="mt-2" />
        </div>

        <div class="col-span-1">
            <x-jet-label class="mb-2" value="Peso estimado (TN)" />
            <x-jet-input type="number" class="w-full text-center" wire:model="createForm.tn_total" />
            <x-jet-input-error for="createForm.tn_total" class="mt-2" />
        </div>

        <div class="col-span-1">
            <x-jet-label class="mb-2" value="Peso TN (IVA incluido)" />
            <div class="flex items-center text-gray-600 gap-1">
                $
                <x-jet-input type="number" class="w-full text-center text-gray-700"
                    wire:model="createForm.tn_price" />
                <x-jet-input-error for="createForm.tn_price" class="mt-2" />
            </div>
        </div>

        <div class="col-span-1">
            <x-jet-label class="mb-2" value="Total final" />
            <div class="flex items-center text-gray-600 gap-1">
                $
                <x-jet-input type="number" class="w-full text-center text-gray-700" readonly
                    wire:model="createForm.total" />
                <x-jet-input-error for="createForm.total" class="mt-2" />
            </div>
        </div>


        {{-- Observations --}}
        <div class="col-span-6">
            <x-jet-label class="mb-2" for="observations" value="Observaciones" />
            <textarea id="observations" class="input-control w-full" wire:model="createForm.observations"
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
