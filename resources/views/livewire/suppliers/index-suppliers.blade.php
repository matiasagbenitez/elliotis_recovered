<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proveedores</h2>
            <a href="{{ route('admin.suppliers.create') }}">
                <x-jet-secondary-button>
                    Crear nuevo proveedor
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
        </div>

        @if ($suppliers->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">

                        <th scope="col" wire:click="order('id')"
                            class="px-4 py-2 cursor-pointer flex items-center">
                            <i class="fas fa-sort mr-2"></i>
                            ID
                        </th>
                        <th scope="col" wire:click="order('business_name')"
                            class="w-1/4 px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Razón social
                        </th>
                        <th scope="col"
                            class="w-1/4 px-4 py-2">
                            CUIT
                        </th>
                        <th scope="col" wire:click="order('total_purchases')"
                            class="w-1/4 px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Total compras
                        </th>
                        <th scope="col"
                            class="w-1/4 px-4 py-2">
                            Estado
                        </th>
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Acción
                        </th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($suppliers as $supplier)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $supplier->id }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center ">
                                <p class="text-sm uppercase ">
                                    {{ $supplier->business_name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $supplier->cuit }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $supplier->total_purchases }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                @switch($supplier->active)
                                    @case(1)
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                        @break
                                    @case(0)
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactivo
                                        </span>
                                        @break
                                    @default
                                @endswitch
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    @livewire('suppliers.show-supplier', ['supplier' => $supplier], key($supplier->id))
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}">
                                        <x-jet-button>
                                            <i class="fas fa-edit"></i>
                                        </x-jet-button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

        @if ($suppliers->hasPages())
            <div class="px-6 py-3">
                {{ $suppliers->links() }}
            </div>
        @endif

    </x-responsive-table>

</div>
