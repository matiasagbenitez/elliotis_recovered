<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tipos de comprobante</h2>
            @livewire('voucher-types.create-voucher-type')
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
        </div>

        @if ($voucher_types->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                        <th scope="col"
                            class="px-4 py-2">
                            ID
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-full">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-4 py-2">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($voucher_types as $item)
                        <tr class="bg-gray-50 text-center">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $item->id }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $item->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    @livewire('voucher-types.edit-voucher-type', ['voucherType' => $item], key($item->id))
                                    <x-jet-danger-button wire:click="$emit('deleteVoucherType', '{{ $item->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </x-jet-danger-button>
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

        @if ($voucher_types->hasPages())
            <div class="px-6 py-3">
                {{ $voucher_types->links() }}
            </div>
        @endif

    </x-responsive-table>

    @push('script')
        <script>
            Livewire.on('deleteVoucherType', voucherTypeId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Sí, eliminar tipo de comprobante',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('voucher-types.index-voucher-types', 'delete', voucherTypeId);

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

</div>
