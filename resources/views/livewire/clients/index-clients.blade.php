<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
            <a href="{{ route('admin.clients.create') }}">
                <x-jet-secondary-button>
                    Crear nuevo cliente
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
        </div>

        @if ($clients->count())
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
                        <th scope="col" wire:click="order('total_sales')"
                            class="w-1/4 px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Total ventas
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
                    @foreach ($clients as $client)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $client->id }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center ">
                                <p class="text-sm uppercase ">
                                    {{ $client->business_name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $client->cuit }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{-- Total sales count --}}
                                    {{ $client->total_sales }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                @switch($client->active)
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
                                    @livewire('clients.show-client', ['client' => $client], key($client->id))
                                    <a href="{{ route('admin.clients.edit', $client) }}">
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

        @if ($clients->hasPages())
            <div class="px-6 py-3">
                {{ $clients->links() }}
            </div>
        @endif

    </x-responsive-table>

</div>
