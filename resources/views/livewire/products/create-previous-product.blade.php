<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.products.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de producto
            </h2>
            <x-jet-secondary-button>
                <i class="fas fa-info-circle mr-2"></i>
                Ayuda
                </x-jet-seconda-button>
        </div>
    </x-slot>

    {{-- CONTENIDO --}}
    <div class="max-w-7xl mx-auto bg-white px-12 py-8 my-4 rounded-lg">

        <p class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Producto anterior de
            <span class="uppercase">{{ $product->name }}</span>
        </p>
        <hr class="my-3">

        <div class="flex flex-col md:flex-row gap-2">
            <select wire:model="previous_product_id" class="input-control w-full">
                <option value="" selected>Seleccione un producto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <div class="whitespace-nowrap flex justify-center items-center">
                <x-jet-button wire:click="create">
                    <i class="fas fa-save mr-2"></i>
                    Guardar producto anterior
                </x-jet-button>
            </div>
        </div>


    </div>

</div>
