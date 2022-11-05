<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Compras</h2>
            <a href="{{ route('admin.purchases.create') }}">
                <x-jet-secondary-button>
                    Registrar nueva compra
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4 grid grid-cols-9 gap-3 bg-grat-50">

            {{-- Proveedor --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Proveedor</x-jet-label>
                <select class="input-control w-full" wire:model="filters.supplier">
                    <option value="" class="text-md">Todos</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" class="text-md">{{ $supplier->business_name }}</option>
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
        @if ($purchases->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                    <tr>
                        <th scope="col" wire:click="order('id')" class="px-4 py-2 cursor-pointer flex items-center">
                            <i class="fas fa-sort mr-2"></i>
                            ID
                        </th>
                        <th scope="col" wire:click="order('date')" class="w-1/4 px-4 py-2 cursor-pointer">
                            <i class="fas fa-sort mr-2"></i>
                            Alta sistema
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2 cursor-pointer">
                            Proveedor
                        </th>
                        <th scope="col" wire:click="order('total')" class="w-1/4 px-4 py-2 cursor-pointer">
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
                    @foreach ($purchases as $purchase)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-2">
                                <p class="text-sm uppercase">
                                    {{ $purchase->id }}
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{-- Format date to Y-m-d --}}
                                    {{ Date::parse($purchase->created_at)->format('d-m-Y') }}
                                </p>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-sm uppercase text-center">
                                    {{ $purchase->supplier->business_name }}
                                </p>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    ${{ number_format($purchase->total, 2, ',', '.') }}
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
                                    @if ($purchase->is_active == 1)
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
                                    @if ($purchase->is_active)
                                        <button title="Anular compra"
                                            wire:click="$emit('disablePurchase', '{{ $purchase->id }}')">
                                            <i class="fas fa-ban mr-1 hover:text-red-600"></i>
                                        </button>
                                    @endif
                                    <a title="Ver detalle"
                                        href="{{ route('admin.purchases.show-detail', $purchase) }}">
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

        @if ($purchases->hasPages())
            <div class="px-6 py-3">
                {{ $purchases->links() }}
            </div>
        @endif

    </x-responsive-table>

    @push('script')
        <script>
            Livewire.on('disablePurchase', async (purchaseId) => {

                const {
                    value: reason
                } = await Swal.fire({
                    title: 'Anular compra',
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
                        title: '¿Anular compra?',
                        text: "Puedes anular la compra y su orden asociada o bien, solo la compra. ¡No podrás revertir esta acción!",
                        icon: 'warning',

                        confirmButtonColor: '#1f2937',
                        confirmButtonText: 'Anular compra y orden',

                        showDenyButton: true,
                        showCancelButton: true,
                        denyButtonText: 'Anular solo compra',
                        denyButtonColor: '#9ca3af',

                        cancelButtonColor: '#dc2626',
                        cancelButtonText: 'Cancelar'

                    }).then((result) => {

                        if (result.isConfirmed) {

                            Livewire.emitTo('purchases.index-purchases', 'disable', purchaseId, reason, disableOrder = true);
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

                            Livewire.emitTo('purchases.index-purchases', 'disable', purchaseId, reason, disableOrder = false);
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
