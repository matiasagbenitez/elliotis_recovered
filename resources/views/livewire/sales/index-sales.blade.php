<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ventas</h2>
            <a href="{{ route('admin.sales.create') }}">
                <x-jet-secondary-button>
                    Registrar nueva venta
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4 grid grid-cols-9 gap-3 bg-grat-50">

            {{-- Proveedor --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Cliente</x-jet-label>
                <select class="input-control w-full" wire:model="filters.client">
                    <option value="" class="text-md">Todos</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" class="text-md">{{ $client->business_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tipo de comprobante --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Tipo de comprobante</x-jet-label>
                <select class="input-control w-full" wire:model="filters.voucherType">
                    <option value="" class="text-md">Todos</option>
                    @foreach ($voucher_types as $voucher_type)
                        <option value="{{ $voucher_type->id }}" class="text-md">{{ $voucher_type->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Desde fecha --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Desde fecha</x-jet-label>
                <x-jet-input wire:model="filters.fromDate" type="date" class="w-full" />
            </div>

            {{-- Hasta fecha --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Hasta fecha</x-jet-label>
                <x-jet-input wire:model="filters.toDate" type="date" class="w-full" />
            </div>

            {{-- Limpiar filtros --}}
            <div class="col-span-1 rounded-lg flex items-end justify-center pb-1 space-x-3">
                <x-jet-button wire:click="resetFilters">
                    <i class="fas fa-eraser"></i>
                </x-jet-button>
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf"></i>
                </x-jet-danger-button>
            </div>
        </div>

        {{-- TABLA --}}
        @if ($sales->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                    <tr>
                        <th scope="col" wire:click="order('id')" class="px-4 py-2 cursor-pointer flex items-center">
                            <i class="fas fa-sort mr-2"></i>
                            ID
                        </th>
                        <th scope="col" wire:click="order('date')" class="px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Alta sistema
                        </th>
                        <th scope="col" class="w-1/4 py-2 px-4">
                            Cliente
                        </th>
                        <th scope="col" wire:click="order('total')" class="px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Monto total
                        </th>
                        {{-- <th scope="col" class="w-1/4 py-2 px-4">
                            Pedido asociado
                        </th> --}}
                        <th scope="col" class="w-1/4 py-2 px-4">
                            Estado
                        </th>
                        <th scope="col" class="py-2 px-4">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($sales as $sale)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-2">
                                <p class="text-sm uppercase">
                                    {{ $sale->id }}
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{-- Format date to Y-m-d --}}
                                    {{ Date::parse($sale->created_at)->format('d-m-Y') }}
                                </p>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-sm uppercase text-center">
                                    {{ $sale->client->business_name }}
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    ${{ number_format($sale->total, 2, ',', '.') }}
                                </p>
                            </td>
                            {{-- <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    @if ($purchase->supplier_order_if)
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            POSEE
                                        </span>
                                    @else
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            NO POSEE
                                        </span>
                                    @endif
                                </p>
                            </td> --}}
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    @if ($sale->is_active == 1)
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            VÁLIDO
                                        </span>
                                    @else
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            ANULADO
                                        </span>
                                    @endif
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($sale->is_active)
                                        <button title="Anular venta"
                                            wire:click="$emit('disableSale', '{{ $sale->id }}')">
                                            <i class="fas fa-ban mr-1"></i>
                                        </button>
                                    @endif
                                    <a title="Ver detalle" href="{{ route('admin.sales.show-detail', $sale) }}">
                                        <x-jet-secondary-button>
                                            <i class="fas fa-list"></i>
                                        </x-jet-secondary-button>
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

        @if ($sales->hasPages())
            <div class="px-6 py-3">
                {{ $sales->links() }}
            </div>
        @endif

    </x-responsive-table>

    @push('script')
        <script>
            Livewire.on('disableSale', async (saleId) => {

                const {
                    value: reason
                } = await Swal.fire({
                    title: 'Anular venta',
                    input: 'textarea',
                    inputPlaceholder: 'Especifique aquí el o los motivos de anulación',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                });

                if (reason) {

                    Swal.fire({
                        title: '¿Anular venta?',
                        text: "Puedes anular la venta y su orden asociada o bien, solo la venta. ¡No podrás revertir esta acción!",
                        icon: 'warning',

                        confirmButtonColor: '#1f2937',
                        confirmButtonText: 'Anular venta y orden',

                        showDenyButton: true,
                        showCancelButton: true,
                        denyButtonText: 'Anular solo venta',
                        denyButtonColor: '#9ca3af',

                        cancelButtonColor: '#dc2626',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {

                        if (result.isConfirmed) {
                            Livewire.emitTo('sales.index-sales', 'disable', saleId, reason, disableOrder = true);
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
                        } else if (result.isDenied) {
                            Livewire.emitTo('sales.index-sales', 'disable', saleId, reason, disableOrder = false);
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
                }
            });
        </script>
    @endpush

</div>
