<div class="max-w-6xl mx-auto bg-white p-10 my-6 rounded-lg">

    <x-slot name="header">
        <a href="{{ route('admin.tenderings.index') }}">
            <x-jet-button>
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </x-jet-button>
        </a>
    </x-slot>

    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
            <h2 class="font-bold text-xl text-gray-800 leading-tight mb-1 uppercase">Registrar una nueva licitación</h2>
            <hr>
        </div>

        <div class="col-span-3 flex items-center">
            <x-jet-label class="mr-2" value="Usuario que registra la licitación:" />
            <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
        </div>

        <br>

        <div class="col-span-3">
            <x-jet-label class="mb-2 font-bold" value="Fecha inicio de licitación" />
            <x-jet-input type="datetime-local" class="mt-1 block w-full" wire:model.defer="createForm.start_date" />
            <x-jet-input-error for="createForm.start_date" class="mt-2" />
        </div>

        <div class="col-span-3">
            <x-jet-label class="mb-2  font-bold" value="Fecha fin de licitación" />
            <x-jet-input type="datetime-local" class="mt-1 block w-full" wire:model.defer="createForm.end_date" />
            <x-jet-input-error for="createForm.end_date" class="mt-2" />
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Detalle de productos</h2>
            <hr class="mt-2">
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
            <hr class="my-2">
        </div>

        {{-- Observations --}}
        <div class="col-span-6">
            <x-jet-label class="mb-2  font-bold" for="observations" value="Observaciones" />
            <textarea id="observations" class="input-control w-full" wire:model.defer="createForm.observations"
                placeholder="Aquí puede escribir las observaciones que considere necesarias..."></textarea>
            <x-jet-input-error for="createForm.observations" class="mt-2" />
        </div>
    </div>

    <div class="flex justify-end mt-6" wire:click="save">
        <x-jet-button class="px-6 col-span-2">
            Registrar licitación
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
