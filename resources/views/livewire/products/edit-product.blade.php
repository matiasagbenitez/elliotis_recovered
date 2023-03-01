<div class="container py-6">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.products.show', $product) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar producto</h2>
            <span></span>
        </div>
    </x-slot>

    <x-jet-form-section class="mb-6" submit="save">

        <x-slot name="title">
            <p class="font-bold text-gray-700">Guía para la edición de un producto</p>
        </x-slot>

        <x-slot name="description">
            <p class="text-justify text-sm text-gray-600">
                Lea detenidamente la información solicitada y rellene los campos requeridos para editar un
                producto en el sistema.
            </p>
            <br>
            <p class="text-justify text-gray-600 text-sm">
                Deberá seleccionar un
                <span class="font-bold">tipo de producto</span>
                creado
                <span class="underline" title="Crear tipo de producto">
                    <a href="{{ route('admin.product-types.index') }}">anteriormente</a>
                </span>
                en base a la clasificación, medida y unidad de trabajo deseada. Lo propio se deberá hacer con el
                atributo <span class="font-bold">tipo de madera.</span>
            </p>
            <br>
            <p class="text-justify text-sm text-gray-600">
                Una vez seleccionados los atributos anteriormente mencionados, el <span class="font-bold">nombre del producto</span> se formará de manera automática.
            </p>
            <br>
            <p class="text-justify text-sm text-gray-600">
                La <span class="font-bold">fase de producción</span> del producto, como su nombre lo indica, servirá para transformar el producto a lo largo del ciclo productivo, es decir, a medida que se relicen tareas sobre él.
            </p>
            <br>
            <p class="text-justify text-sm text-gray-600">
                Si el producto se utiliza en m², entonces el
                <span class="font-bold">precio final</span>
                se calculará de manera automática en base al <span class="font-bold">precio por m²</span>
                del producto y los <span class="font-bold">m² / unidad</span> con que cuenta.
                En caso contrario, es decir, cuando el producto no se utilice por m², entonces el precio final
                será igual al <span class="font-bold">precio unitario</span>.
            </p>
            <br>
            <span class="font-bold text-sm">(*) Campos obligatorios.</span>
        </x-slot>

        <x-slot name="form">

            <div class="col-span-6">
                <h2 class="font-bold">Información del tipo de producto</h2>
                <hr>
            </div>

            {{-- Tipo de producto --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Tipo de producto *</x-jet-label>
                <select class="input-control w-full" wire:model="editForm.product_type_id">
                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($types_of_product as $item)
                        <option value="{{ $item->id }}">{{ $item->product_name->name }} {{ $item->measure->name }}
                        </option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.product_type_id" />
            </div>

            {{-- Tipo de madera --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Tipo de madera *</x-jet-label>
                <select class="input-control w-full" wire:model="editForm.wood_type_id">
                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($types_of_wood as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.wood_type_id" />
            </div>

            {{-- Nombre --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Nombre del producto *</x-jet-label>
                <x-jet-input wire:model.defer="editForm.name" type="text" class="w-full" readonly
                    placeholder="Nombre del producto"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.name" />
            </div>

            {{-- Fase de producción --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Fase de producción *</x-jet-label>
                <select class="input-control w-full" wire:model.defer="editForm.phase_id">
                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($phases as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.phase_id" />
            </div>

            {{-- M2 --}}
            <div class="col-span-1">
                <x-jet-label class="mb-2">m² / unidad</x-jet-label>
                <x-jet-input wire:model="editForm.m2" type="text" class="w-full text-gray-500" readonly>
                </x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.m2" />
            </div>

            <div class="col-span-1">
                <x-jet-label class="mb-2">
                    {{ $editForm['m2'] != 0 ? 'Precio m²' : 'Precio unitario' }}
                </x-jet-label>
                <x-jet-input wire:model="editForm.m2_price" type="number" class="w-full"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.m2_price" />
            </div>

            <div class="col-span-1">
                <x-jet-label class="mb-2">Precio final</x-jet-label>
                <x-jet-input wire:model.defer="editForm.selling_price" type="text" class="w-full" readonly>
                </x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.selling_price" />
            </div>

            <div class="col-span-6">
                <h2 class="font-bold text-sm">Información de stock (expresado en unidades)</h2>
                <hr>
            </div>

            {{-- Stock real --}}
            <div class="col-span-1">
                <x-jet-label class="mb-2">Stock real</x-jet-label>
                <x-jet-input wire:model="editForm.real_stock" type="number" class="w-full"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.real_stock" />
            </div>

            <div class="col-span-1 flex items-end pb-3 text-gray-500">
                <p>{{ $stock['real_stock'] }}</p>
            </div>

            {{-- Stock mínimo --}}
            <div class="col-span-1">
                <x-jet-label class="mb-2">Stock mínimo</x-jet-label>
                <x-jet-input wire:model="editForm.minimum_stock" type="number" class="w-full"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.minimum_stock" />
            </div>

            <div class="col-span-1 flex items-end pb-3 text-gray-500">
                <p>{{ $stock['minimum_stock'] }}</p>
            </div>

            {{-- M2 --}}
            <div class="col-span-1">
                <x-jet-label class="mb-2">Reposición</x-jet-label>
                <x-jet-input wire:model="editForm.reposition" type="number" class="w-full"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.reposition" />
            </div>

            <div class="col-span-1 flex items-end pb-3 text-gray-500">
                <p>{{ $stock['reposition'] }}</p>
            </div>

            <div class="col-span-6">
                <h2 class="font-bold text-sm">Información de compra y venta</h2>
                <hr>
            </div>

            {{-- Radiobutton SI/NO synchronyzed with $editForm['is_salable'] --}}
            <div class="col-span-1">
                <x-jet-label class="mb-2">Artículo comprable</x-jet-label>
                <div class="flex items-center">
                    <input type="radio" class="form-radio" name="is_buyable" value="1"
                        wire:model="editForm.is_buyable" />
                    <label for="yes" class="ml-2 mr-3">Sí</label>

                    <input type="radio" class="form-radio" name="is_buyable" value="0"
                        wire:model="editForm.is_buyable" />
                    <label for="no" class="ml-2">No</label>
                </div>
            </div>

            {{-- Radiobutton SI/NO synchronyzed with $editForm['is_salable'] --}}
            <div class="col-span-1">
                <x-jet-label class="mb-2">Artículo vendible</x-jet-label>
                <div class="flex items-center">
                    <input type="radio" class="form-radio" name="is_salable" value="1"
                        wire:model="editForm.is_salable" />
                    <label for="yes" class="ml-2 mr-3">Sí</label>

                    <input type="radio" class="form-radio" name="is_salable" value="0"
                        wire:model="editForm.is_salable" />
                    <label for="no" class="ml-2">No</label>
                </div>
            </div>


        </x-slot>

        <x-slot name="actions">
            <x-jet-button class="px-6">
                Guardar cambios
            </x-jet-button>
        </x-slot>

    </x-jet-form-section>

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
