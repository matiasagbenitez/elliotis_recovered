<div class="max-w-7xl mx-auto bg-white p-10 my-6 rounded-lg">

    <x-slot name="header">
        <a href="{{ route('admin.sales.index') }}">
            <x-jet-button>
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </x-jet-button>
        </a>
    </x-slot>


    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
            <h2 class="font-bold text-xl text-gray-800 leading-tight mb-1 uppercase">Registrar una nueva venta</h2>
            <hr>
        </div>
        {{-- FECHA --}}
        <div class="col-span-2">
            <x-jet-label class="mb-2" for="date" value="Fecha de la venta (*)" />
            <x-jet-input id="date" type="date" class="mt-1 block w-full" wire:model.defer="createForm.date" />
            <x-jet-input-error for="createForm.date" class="mt-2" />
        </div>

        <div class="col-span-6">
            <h2 class="font-bold">Datos del cliente</h2>
            <hr>
        </div>

        {{-- Supplier id --}}
        <div class="col-span-2">
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

        {{-- Has order associated? --}}
        <div class="col-span-2">
            <x-jet-label class="mb-2" value="¿Tiene orden asociada?" />
            <select id="" class="input-control w-full" wire:model="has_order_associated">Seleccione una
                opción</option>
                <option value="" disabled>Seleccione una opción</option>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </div>

        {{-- Order id --}}
        <div class="col-span-2">
            <x-jet-label class="mb-2" for="order_id" value="Orden asociada" />
            <select id="order_id" class="input-control w-full" wire:model="createForm.client_order_id"
                {{ $has_order_associated ? '' : 'disabled' }}>
                <option value="">Seleccione una orden</option>

                @foreach ($client_orders as $order)
                    <option value="{{ $order->id }}">Orden #{{ $order->id }}
                        ({{ Date::parse($order->registration_date)->format('d-m-Y') }})
                    </option>
                @endforeach
            </select>
            <x-jet-input-error for="createForm.client_order_id" class="mt-2" />
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
            @if ($orderSublots)
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
                    @foreach ($orderSublots as $index => $orderProduct)
                        <div class="col-span-3 flex">
                            <button type="button" wire:click.prevent="removeProduct({{ $index }})">
                                <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                            </button>
                            <select name="orderSublots[{{ $index }}][sublot_id]"
                                wire:model.lazy="orderSublots.{{ $index }}.sublot_id"
                                wire:model.lazy="orderSublots.{{ $index }}.product_id"
                                wire:change="updatePrice({{ $index }})" class="input-control w-full p-1 pl-3">
                                <option disabled value="">Seleccione un producto</option>
                                @foreach ($allSublotsFormated as $sublot)
                                    <option value="{{ $sublot['id'] }}"
                                        {{ $this->isSublotInOrder($sublot['id']) ? 'disabled' : '' }}>
                                        {{ $sublot['text'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1">
                            <x-jet-input readonly disabled type="number" min="1"
                                name="orderSublots[{{ $index }}][m2_unitary]"
                                wire:model.lazy="orderSublots.{{ $index }}.m2_unitary"
                                class="border-none shadow-none w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1">
                            <x-jet-input type="number" min="1"
                                name="orderSublots[{{ $index }}][quantity]"
                                wire:model.lazy="orderSublots.{{ $index }}.quantity"
                                class="input-control w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1">
                            <x-jet-input readonly disabled type="number" min="1"
                                name="orderSublots[{{ $index }}][m2_total]"
                                wire:model.lazy="orderSublots.{{ $index }}.m2_total"
                                class="border-none shadow-none w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1">
                            <x-jet-input type="number" min="1"
                                name="orderSublots[{{ $index }}][m2_price]"
                                wire:model="orderSublots.{{ $index }}.m2_price"
                                class="input-control w-full p-1 text-center" />
                        </div>
                        <div class="col-span-1 flex items-center">
                            $
                            <x-jet-input disabled readonly type="number" min="1"
                                name="orderSublots[{{ $index }}][subtotal]"
                                wire:model.lazy="orderSublots.{{ $index }}.subtotal"
                                class="input-control w-full p-1 text-center border-none shadow-none" />
                        </div>
                    @endforeach
                </div>
                <x-jet-input-error for="orderSublots.*.sublot_id" class="mt-2" />
            @else
                <p class="text-center">¡No hay productos! Intenta agregar alguno con el botón <span
                        class="font-bold">"Agregar producto"</span>.</p>
            @endif


        </div>

        {{-- PARTE INFERIOR --}}
        <div class="col-span-6">
            <hr>
            <div class="grid grid-cols-8 gap-2">
                <div
                    class="{{ $orderSublots ? 'col-span-6' : 'col-span-8' }}  mt-4 flex justify-center items-center gap-2">
                    <div>
                        <x-jet-button type="button" wire:click.prevent="addProduct" class="px-3">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar producto
                        </x-jet-button>
                    </div>
                </div>


                {{-- PARTE DERECHA --}}
                @if ($orderSublots)
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
            Registrar venta
        </x-jet-button>
    </div>

</div>

@push('script')
    <script>
        Livewire.on('stockError', message => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#1f2937',
            });
        });
    </script>

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

    <script>
        Livewire.on('quantitiesError', message => {
            Swal.fire({
                title: '¡No hay suficiente stock!',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Sí, completar con lo disponible',
                cancelButtonText: 'Cancelar venta'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.emitTo('sales.create-sale', 'bestTry');

                    Livewire.on('success', message => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: message
                        });
                    });

                    Livewire.on('error', message => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                            showConfirmButton: true,
                            confirmButtonColor: '#1f2937',
                        });
                    });
                } else {
                    Livewire.emitTo('sales.create-sale', 'cancelSale');
                }
            })
        });
    </script>
@endpush
