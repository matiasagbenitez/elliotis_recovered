<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Condiciones de pago</h2>
            @livewire('payment-conditions.create-payment-condition')
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
        </div>

        @if ($payment_conditions->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                        <th scope="col"
                            class="px-4 py-2">
                            ID
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/2">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/2">
                            Tipo de pago
                        </th>
                        <th scope="col"
                            class="px-4 py-2">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($payment_conditions as $item)
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
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $item->is_deferred ? 'Diferido' : 'No diferido' }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    @livewire('payment-conditions.edit-payment-condition', ['paymentCondition' => $item], key($item->id))
                                    <x-jet-danger-button wire:click="$emit('deletePaymentCondition', '{{ $item->id }}')">
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

        @if ($payment_conditions->hasPages())
            <div class="px-6 py-3">
                {{ $payment_conditions->links() }}
            </div>
        @endif

    </x-responsive-table>

    @push('script')
        <script>
            Livewire.on('deletePaymentCondition', paymentConditionId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Sí, eliminar condición de pago',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('payment-conditions.index-payment-condition', 'delete', paymentConditionId);

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
