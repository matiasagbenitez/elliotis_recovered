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
            Productos siguientes de
            <span class="uppercase">{{ $product->name }}</span>
        </p>

        <hr class="my-3">

        <p class="text-justify text-gray-600 mb-3">

            <span class="font-bold">LEA CON ATENCIÓN.</span>
            En el siguiente listado se muestran todos los productos siguientes asociados a
            <span class="uppercase">{{ $product->name }}</span>.
           <span class="font-bold">
            Tanto si desea actualizar los productos siguientes como si desea agregar nuevos,
            debe seleccionarlos en la columna "¿Es producto siguiente?" y luego presionar el botón "Guardar productos siguientes".
           </span>
            El sistema únicamente sincronizará los productos seleccionados, elimnando los que no estén seleccionados.
        </p>

        <x-responsive-table>
            <table class="table-auto w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Producto</th>
                        <th class="py-2 px-4 whitespace-nowrap">Estado actual</th>
                        <th class="py-2 px-4 whitespace-nowrap">¿Es producto siguiente?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="border px-4">{{ $product['id'] }}</td>
                            <td class="border px-4 py-2 w-full">{{ $product['name'] }}</td>
                            <td class="border px-4 py-2 w-full whitespace-nowrap">
                                <div class="flex items-center justify-center px-4">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product['exists'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product['exists'] ? 'Producto siguiente' : 'No es producto siguiente' }}
                                    </span>
                                </div>
                            </td>
                            <td class="border px-4">
                                <div class="flex items-center justify-center">
                                    <input wire:model="selected" value="{{ $product['id'] }}" type="checkbox">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-responsive-table>

        <div class="flex justify-end mt-4">
            <x-jet-button wire:click="save">
                <i class="fas fa-save mr-2"></i>
                Guardar productos siguientes
            </x-jet-button>
        </div>

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
