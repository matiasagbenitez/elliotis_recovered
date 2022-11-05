<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Órdenes de venta</h2>
            <a href="{{ route('admin.sale-orders.create') }}">
                <x-jet-secondary-button>
                    Registrar nueva orden de venta
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <x-responsive-table>

        {{-- TABLA --}}
        @if ($saleOrders->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                    <tr>
                        <th scope="col" wire:click="order('id')" class="px-4 py-2 cursor-pointer flex items-center">
                            <i class="fas fa-sort mr-2"></i>
                            ID
                        </th>
                        <th scope="col" wire:click="order('registration_date')"
                            class="w-1/5 px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Alta sistema
                        </th>
                        <th scope="col" class="w-1/5 py-2 px-4">
                            Cliente
                        </th>
                        <th scope="col" wire:click="order('total')" class="w-1/5 px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Monto total
                        </th>
                        <th scope="col" class="w-1/5 py-2 px-4">
                            Estado orden
                        </th>
                        <th scope="col" class="w-1/5 py-2 px-4">
                            Estado venta
                        </th>
                        <th scope="col" class="py-2 px-4">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($saleOrders as $saleOrder)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-2">
                                <p class="text-sm uppercase">
                                    {{ $saleOrder->id }}
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{-- Format date to Y-m-d --}}
                                    {{ Date::parse($saleOrder->created_at)->format('d-m-Y') }}
                                </p>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-sm uppercase text-center">
                                    {{ $saleOrder->client->business_name }}
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    ${{ number_format($saleOrder->total, 2, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    @if ($saleOrder->is_active == 1)
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            VÁLIDA
                                        </span>
                                    @else
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            ANULADA
                                        </span>
                                    @endif
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    @if ($saleOrder->is_active == 1)
                                        @if ($saleOrder->its_done == 1)
                                            <span
                                                class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                COMPLETADA
                                            </span>
                                        @else
                                            <span
                                                class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                PENDIENTE
                                            </span>
                                        @endif
                                    @endif
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($saleOrder->is_active && $saleOrder->its_done == 0)
                                        <button title="Anular venta"
                                            wire:click="$emit('disableSaleOrder', '{{ $saleOrder->id }}')">
                                            <i class="fas fa-ban mr-1"></i>
                                        </button>
                                    @endif
                                    <a title="Ver detalle" href="{{ route('admin.sale-orders.show-detail', $saleOrder) }}">
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

        @if ($saleOrders->hasPages())
            <div class="px-6 py-3">
                {{ $saleOrders->links() }}
            </div>
        @endif

    </x-responsive-table>

</div>

@push('script')
    <script>
        Livewire.on('disableSaleOrder', async (saleId) => {

            const {
                value: reason
            } = await Swal.fire({
                title: 'Anular orden de venta',
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
                    title: '¿Anular orden de venta?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Sí, anular venta',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('sale-orders.sale-orders-index', 'disable', saleId, reason);
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
