<div>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">

            <a href="{{ route('admin.tasks.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Finalizar tarea de
                <span class="uppercase font-bold">{{ $info['task_name'] }}</span>
            </h2>

            <x-jet-secondary-button>
                <i class="fas fa-info-circle mr-2"></i>
                Ayuda
                </x-jet-seconda-button>
        </div>
    </x-slot>

    {{-- CONTENIDO --}}
    <div class="container flex flex-col md:flex-row gap-5 my-6 rounded-lg">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="w-full md:w-96 bg-white px-8 py-2 my-4 rounded-lg">
            <div class="my-3 text-justify">
                <span class="font-bold text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Información
                </span>
                <hr class="my-2">
                <div class="mt-2">
                    <p class="font-bold">Tipo de tarea:</p>
                    <p class="italic text-sm">{{ $info['task_name'] }}</p>
                </div>
                <div class="mt-2">
                    <p class="font-bold">Entrada:</p>
                    <p class="italic text-sm">{{ $info['initial_phase'] }}</p>
                </div>
                <div class="mt-2">
                    <p class="font-bold">Origen entrada:</p>
                    <p class="italic text-sm">{{ $info['origin_area'] }}</p>
                </div>
                <div class="mt-2">
                    <p class="font-bold">Salida:</p>
                    <p class="italic text-sm">{{ $info['final_phase'] }}</p>
                </div>
                <div class="mt-2">
                    <p class="font-bold">Destino salida:</p>
                    <p class="italic text-sm">{{ $info['destination_area'] }}</p>
                </div>
            </div>
        </div>

        {{-- DIV TAREA PRINCIPAL --}}
        <div class="bg-white px-8 py-2 my-4 rounded-lg">

            <div class="my-3">
                <span class="font-bold text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Detalle de la tarea #{{ $task->id }}
                </span>
                <hr class="my-2">
            </div>

            {{-- DETALLE TAREA --}}
            <div class="grid grid-cols-6 gap-4">
                <div class="col-span-2">
                    <x-jet-label class="mb-2 font-bold" value="Tipo de tarea" />
                    <x-jet-input type="text" class="w-full text-gray-500" disabled
                        value="{{ $info['task_name'] }}" />
                    <x-jet-input-error for="createForm.task_type_id" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-jet-label class="mb-2 font-bold" value="Usuario que inició la tarea" />
                    <x-jet-input type="text" class="w-full text-gray-500" disabled
                        value="{{ $info['user_who_started'] }}" />
                    <x-jet-input-error for="createForm.date" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-jet-label class="mb-2 font-bold" value="Fecha y hora de inicio" />
                    <x-jet-input type="text" class="w-full text-gray-500" disabled
                        value="{{ $info['started_at'] }}" />
                    <x-jet-input-error for="createForm.started_at" class="mt-2" />
                </div>
            </div>

            {{-- PRODUCTOS ENTRADA --}}
            <h1 class="font-bold text-lg mt-5">Detalle productos de entrada</h1>
            <hr class="mt-1">

            <div class="grid grid-cols-6 gap-4 mb-10">

                {{-- ENTRADA --}}
                <div class="col-span-6">
                    @if ($inputSelects)
                        <div class="grid grid-cols-6 w-full text-center text-sm uppercase font-bold text-gray-600">
                            <div class="col-span-5 py-2">Detalle de sublotes a tomar de entrada</div>
                            <div class="col-span-1 py-2">Cantidad</div>
                        </div>

                        <div
                            class="grid grid-cols-6 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                            @foreach ($inputSelects as $index => $inputSublot)
                                <div class="col-span-5 flex">
                                    <button type="button" wire:click.prevent="removeInputSelect({{ $index }})">
                                        <i class="fas fa-trash mx-4 hover:text-red-600" title="Eliminar producto"></i>
                                    </button>
                                    <select name="inputSelects[{{ $index }}][sublot_id]"
                                        wire:model.lazy="inputSelects.{{ $index }}.sublot_id"
                                        class="input-control w-full p-1 pl-3">
                                        <option disabled value="">Seleccione un producto</option>
                                        @foreach ($inputSublots as $sublot)
                                            <option value="{{ $sublot['id'] }}""
                                                {{ $this->isSublotInInputSelect($sublot['id']) ? 'disabled' : '' }}>
                                                x {{ $sublot['actual_quantity'] }}
                                                &ensp;
                                                {{ $sublot['product_name'] }}
                                                &ensp;
                                                {{ $sublot['lot_code'] }}
                                                &ensp;
                                                {{ $sublot['sublot_code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-1">
                                    <x-jet-input type="number" min="1" {{-- max="{{ $inputSublots[$index]->actual_quantity }}" --}}
                                        name="inputSelects[{{ $index }}][consumed_quantity]"
                                        wire:model.lazy="inputSelects.{{ $index }}.consumed_quantity"
                                        class="input-control w-full p-1 text-center" />
                                </div>
                            @endforeach

                        </div>
                        <x-jet-input-error for="inputSelects.*.sublot_id" class="mt-2" />
                    @else
                        <p class="text-center mt-4">
                            ¡No hay sublotes de entrada! Intenta agregar alguno con el botón
                            <span class="font-bold">"Agregar sublote"</span>.
                        </p>
                    @endif
                    {{-- BOTÓN AGREGAR PRODUCTOS --}}
                    <div
                        class="{{ $inputSelects ? 'col-span-4' : 'col-span-6' }}  mt-4 flex justify-center items-center gap-2">
                        <div>
                            <x-jet-button type="button" wire:click.prevent="addInputSelect" class="px-3">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar sublote
                            </x-jet-button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRODUCTOS SALIDA --}}
            @if ($transformation == true)
                <h1 class="font-bold text-lg mt-5">Detalle productos de salida</h1>
                <hr class="mt-1">

                <div class="grid grid-cols-6 gap-4 mb-10">

                    {{-- SALIDA --}}
                    <div class="col-span-6">
                        @if ($outputSelects)
                            <div class="grid grid-cols-6 w-full text-center text-sm uppercase font-bold text-gray-600">
                                <div class="col-span-5 py-2">Detalle de productos a generar de salida</div>
                                <div class="col-span-1 py-2">Cantidad</div>
                            </div>

                            <div
                                class="grid grid-cols-6 w-full text-center text-sm uppercase text-gray-600 gap-2 items-center">
                                @foreach ($outputSelects as $index => $outputProduct)
                                    <div class="col-span-5 flex">
                                        <button type="button"
                                            wire:click.prevent="removeOutputSelect({{ $index }})">
                                            <i class="fas fa-trash mx-4 hover:text-red-600"
                                                title="Eliminar producto"></i>
                                        </button>
                                        <select name="outputSelects[{{ $index }}][product_id]"
                                            wire:model.lazy="outputSelects.{{ $index }}.product_id"
                                            class="input-control w-full p-1 pl-3">
                                            <option disabled value="">Seleccione un producto</option>
                                            @foreach ($outputProducts as $product)
                                                <option value="{{ $product['id'] }}""
                                                    {{ $this->isProductInOutputSelect($product['id']) ? 'disabled' : '' }}>
                                                    {{ $product['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <x-jet-input type="number" min="1" {{-- max="{{ $inputSublots[$index]->actual_quantity }}" --}}
                                            name="outputSelects[{{ $index }}][produced_quantity]"
                                            wire:model.lazy="outputSelects.{{ $index }}.produced_quantity"
                                            class="input-control w-full p-1 text-center" />
                                    </div>
                                @endforeach

                            </div>
                            <x-jet-input-error for="outputSelects.*.product_id" class="mt-2" />
                        @else
                            <p class="text-center mt-4">
                                ¡No hay productos de entrada! Intenta agregar alguno con el botón
                                <span class="font-bold">"Agregar producto"</span>.
                            </p>
                        @endif
                        {{-- BOTÓN AGREGAR PRODUCTOS --}}
                        <div
                            class="{{ $outputSelects ? 'col-span-4' : 'col-span-6' }}  mt-4 flex justify-center items-center gap-2">
                            <div>
                                <x-jet-button type="button" wire:click.prevent="addOutputSelect" class="px-3">
                                    <i class="fas fa-plus mr-2"></i>
                                    Agregar producto
                                </x-jet-button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- BOTÓN GUARDAR --}}
            <div class="flex justify-between items-center mt-6">
                <p class="text font-semibold">
                    Controle la información ingresada y luego presione el botón
                    <span class="font-bold">"Registrar tarea"</span>.
                </p>

                <div class="mt-6" wire:click="$emit('saveTask')">
                    <x-jet-button class="px-6 col-span-2 bg-emerald-800">
                        Registrar tarea
                    </x-jet-button>
                </div>
            </div>

        </div>

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

                    Livewire.emitTo('tasks.register-task', 'presave');

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
