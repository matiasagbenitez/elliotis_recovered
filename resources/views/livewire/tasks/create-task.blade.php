{{-- <div class="container py-6"> --}}
<div class="max-w-5xl mx-auto bg-white px-8 py-4 my-6 rounded-lg">

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.tasks.manage', $task_type_id) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Finalizar tarea de <span class="uppercase font-bold">{{ $task_type_name }}</span>
            </h2>

            {{-- PDF BUTTON --}}
            <x-jet-secondary-button>
                <i class="fas fa-info-circle mr-2"></i>
                Ayuda
                </x-jet-seconda-button>
        </div>
    </x-slot>

    {{-- DETALLE TAREA --}}
    <div class="grid grid-cols-6 gap-4 mb-10">
        <div class="col-span-6">
            <h1 class="font-bold text-lg">Detalle de tarea #{{ $task_id }}</h1>
            <hr class="mt-1">
        </div>

        <div class="col-span-2">
            <x-jet-label class="mb-2" value="Tipo de tarea" />
            <x-jet-input type="text" class="w-full text-gray-500" disabled value="{{ $task_type_name }}" />
            <x-jet-input-error for="createForm.task_type_id" class="mt-2" />
        </div>

        <div class="col-span-2">
            <x-jet-label class="mb-2" value="Usuario que inició la tarea" />
            <x-jet-input type="text" class="w-full text-gray-500" disabled value="{{ $user_who_started }}" />
            <x-jet-input-error for="createForm.date" class="mt-2" />
        </div>

        <div class="col-span-2">
            <x-jet-label class="mb-2" value="Fecha y hora de inicio" />
            <x-jet-input type="datetime-local" class="w-full text-gray-500" wire:model="started_at" />
            <x-jet-input-error for="createForm.started_at" class="mt-2" />
        </div>
    </div>

    {{-- PRODUCTOS ENTRADA --}}
    <div class="grid grid-cols-6 gap-4 mb-10">

        <div class="col-span-6">
            <h1 class="font-bold text-lg">Detalle productos de entrada</h1>
            <hr class="mt-1">
            <span class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Dado que el tipo de tarea es
                <span class="uppercase font-bold">{{ $task_type_name }}</span>,
                los productos de entrada serán de tipo
                <span class="uppercase font-bold">{{ $initial_phase_name }}</span>
            </span>
        </div>

        {{-- PARA CORTE DE ROLLOS --}}
        @if ($task_type_id == 1)
            <div class="col-span-6">
                @if ($taskInputProducts)
                    <div class="grid grid-cols-6 w-full text-center text-sm uppercase font-bold text-gray-600">
                        <div class="col-span-5 py-1">Detalle de sublotes a tomar de entrada</div>
                        <div class="col-span-1 py-1">Cantidad</div>
                    </div>

                    <div class="grid grid-cols-6 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                        @foreach ($taskInputProducts as $index => $taskInputProduct)
                            <div class="col-span-5 flex">
                                <button type="button" wire:click.prevent="removeInputProduct({{ $index }})">
                                    <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                                </button>
                                <select name="taskInputProducts[{{ $index }}][trunk_lot_id]"
                                    wire:model.lazy="taskInputProducts.{{ $index }}.trunk_lot_id"
                                    class="input-control w-full p-1 pl-3">
                                    <option disabled value="">Seleccione un producto</option>
                                    @foreach ($allInputProducts as $lot)
                                        <option value="{{ $lot->id }}""
                                            {{ $this->isProductInInputOrder($lot->id) ? 'disabled' : '' }}>
                                            x {{ $lot->actual_quantity }}
                                            &ensp;
                                            {{ $lot->product->name }}
                                            &ensp;
                                            Sublote: {{ $lot->code }}
                                            &ensp;
                                            Lote: {{ $lot->trunk_purchase->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <x-jet-input type="number" min="1" {{-- max="{{ $allInputProducts[$index]->actual_quantity }}" --}}
                                    name="taskInputProducts[{{ $index }}][consumed_quantity]"
                                    wire:model.lazy="taskInputProducts.{{ $index }}.consumed_quantity"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                        @endforeach

                    </div>
                    <x-jet-input-error for="taskInputProducts.*.trunk_lot_id" class="mt-2" />
                @else
                    <p class="text-center">
                        ¡No hay productos de entrada! Intenta agregar alguno con el botón
                        <span class="font-bold">"Agregar producto"</span>.
                    </p>
                @endif
                {{-- BOTÓN AGREGAR PRODUCTOS --}}
                <div
                    class="{{ $taskInputProducts ? 'col-span-4' : 'col-span-6' }}  mt-4 flex justify-center items-center gap-2">
                    <div>
                        <x-jet-button type="button" wire:click.prevent="addInputProduct" class="px-3">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar producto
                        </x-jet-button>
                    </div>
                </div>
            </div>

        {{-- PARA EL RESTO DE TAREAS --}}
        @else
            <div class="col-span-6">
                @if ($taskInputProducts)
                    <div class="grid grid-cols-6 w-full text-center text-sm uppercase font-bold text-gray-600">
                        <div class="col-span-5 py-1">Detalle de sublotes a tomar de entrada</div>
                        <div class="col-span-1 py-1">Cantidad</div>
                    </div>

                    <div class="grid grid-cols-6 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                        @foreach ($taskInputProducts as $index => $taskInputProduct)
                            <div class="col-span-5 flex">
                                <button type="button" wire:click.prevent="removeInputProduct({{ $index }})">
                                    <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                                </button>
                                <select name="taskInputProducts[{{ $index }}][sublot_id]"
                                    wire:model.lazy="taskInputProducts.{{ $index }}.sublot_id"
                                    class="input-control w-full p-1 pl-3">
                                    <option disabled value="">Seleccione un producto</option>
                                    @foreach ($allInputProducts as $sublot)

                                        <option value="{{ $sublot['id'] }}""
                                            {{ $this->isProductInInputOrder($sublot['id']) ? 'disabled' : '' }}>
                                            x {{ $sublot['actual_quantity'] }}
                                            &ensp;
                                            {{ $sublot['product_name'] }}
                                            &ensp;
                                            Sublote: {{ $sublot['sublot_code'] }}
                                            &ensp;
                                            Lote: {{ $sublot['lot_code'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <x-jet-input type="number" min="1" {{-- max="{{ $allInputProducts[$index]->actual_quantity }}" --}}
                                    name="taskInputProducts[{{ $index }}][consumed_quantity]"
                                    wire:model.lazy="taskInputProducts.{{ $index }}.consumed_quantity"
                                    class="input-control w-full p-1 text-center" />
                            </div>
                        @endforeach

                    </div>
                    <x-jet-input-error for="taskInputProducts.*.sublot_id" class="mt-2" />
                @else
                    <p class="text-center">
                        ¡No hay productos de entrada! Intenta agregar alguno con el botón
                        <span class="font-bold">"Agregar producto"</span>.
                    </p>
                @endif
                {{-- BOTÓN AGREGAR PRODUCTOS --}}
                <div
                    class="{{ $taskInputProducts ? 'col-span-4' : 'col-span-6' }}  mt-4 flex justify-center items-center gap-2">
                    <div>
                        <x-jet-button type="button" wire:click.prevent="addInputProduct" class="px-3">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar producto
                        </x-jet-button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- PRODUCTOS SALIDA --}}
    <div class="grid grid-cols-6 gap-4 mb-10">
        <div class="col-span-6">
            <h1 class="font-bold text-lg">Detalle productos de salida</h1>
            <hr class="my-1">
            <span class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Dado que el tipo de tarea es
                <span class="uppercase font-bold">{{ $task_type_name }}</span>,
                los productos de salida serán de tipo
                <span class="uppercase font-bold">{{ $final_phase_name }}</span>
            </span>
        </div>

        <div class="col-span-6">

            @if ($taskOutputProducts)
                <div class="grid grid-cols-6 w-full text-center text-sm uppercase font-bold text-gray-600">
                    <div class="col-span-5 py-1">Detalle de sublotes a generar de salida</div>
                    <div class="col-span-1 py-1">Cantidad</div>
                </div>

                <div class="grid grid-cols-6 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                    @foreach ($taskOutputProducts as $index => $taskInputProduct)
                        <div class="col-span-5 flex">
                            <button type="button" wire:click.prevent="removeOutputProduct({{ $index }})">
                                <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                            </button>
                            <select name="taskOutputProducts[{{ $index }}][product_id]"
                                wire:model.lazy="taskOutputProducts.{{ $index }}.product_id"
                                class="input-control w-full p-1 pl-3">
                                <option disabled value="">Seleccione un producto</option>
                                @foreach ($allOutputProducts as $product)
                                    <option value="{{ $product->id }}""
                                        {{ $this->isProductInOutputOrder($product->id) ? 'disabled' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1">
                            <x-jet-input type="number" min="1" {{-- max="{{ $allOutputProducts[$index]->actual_quantity }}" --}}
                                name="taskOutputProducts[{{ $index }}][produced_quantity]"
                                wire:model.lazy="taskOutputProducts.{{ $index }}.produced_quantity"
                                class="input-control w-full p-1 text-center" />
                        </div>
                    @endforeach

                </div>
                <x-jet-input-error for="taskOutputProducts.*.product_id" class="mt-2" />
            @else
                <p class="text-center">
                    ¡No hay productos de entrada! Intenta agregar alguno con el botón
                    <span class="font-bold">"Agregar producto"</span>.
                </p>
            @endif
            {{-- BOTÓN AGREGAR PRODUCTOS --}}
            <div
                class="{{ $taskOutputProducts ? 'col-span-4' : 'col-span-6' }}  mt-4 flex justify-center items-center gap-2">
                <div>
                    <x-jet-button type="button" wire:click.prevent="addOutputProduct" class="px-3">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar producto
                    </x-jet-button>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTÓN GUARDAR --}}
    <div class="flex justify-end mt-6" wire:click="$emit('saveTask')">
        <x-jet-button class="px-6 col-span-2 bg-emerald-800">
            Registrar tarea
        </x-jet-button>
    </div>

</div>

@push('script')
    <script>
        Livewire.on('saveTask', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Sí, registrar tarea',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.emitTo('tasks.create-task', 'save');

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
                }
            })
        });
    </script>
@endpush
