<div class="container py-6">
    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.tenderings.show-detail', $tendering->id) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de
                <span class="font-bold">LICITACIÓN #{{ $tendering->id }}</span>
            </h2>

            {{-- PDF BUTTON --}}
            <a href="#">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    {{-- MEJOR OFERTA --}}
    @if ($bestOffer)
        <div class="px-6 py-6 bg-white rounded-lg shadow mb-6">
            <span class="font-bold text-gray-700 text-lg">
                <i class="fas fa-star text-yellow-400 text-lg mr-2"></i>
                Oferta ganadora
            </span>
            <hr class="my-2">
            <div class="md:grid md:grid-cols-2">
                <div class="space-y-1">
                    <p class="font-bold">
                        Proveedor:
                        <span class="uppercase font-normal">{{ $bestOfferStats['supplier'] }}</span>
                    </p>
                    <p class="font-bold">
                        {{ $bestOfferStats['products_quantities'] }}
                    </p>
                    <p class="font-bold">
                        {{ $bestOfferStats['summary'] }}
                    </p>
                    <p class="font-bold">
                        Importe total estimado:
                        <span class="uppercase font-normal">${{ $bestOfferStats['total'] }}</span>
                    </p>
                    <p class="font-bold">
                        Fecha de entrega:
                        <span class="uppercase font-normal">{{ $bestOfferStats['delivery_date'] }}</span>
                    </p>
                </div>

                <div class="flex items-end justify-end gap-2">
                    <a
                        href="{{ route('admin.tenderings.show-offer-detail', ['tendering' => $bestOfferStats['tendering_id'], 'hash' => $bestOfferStats['hash']]) }}">
                        <x-jet-secondary-button class="mt-4">
                            Ver oferta
                        </x-jet-secondary-button>
                    </a>
                    @if (!$bestOfferHasOrderAssociated)
                        <x-jet-button class="mt-4" wire:click="showBestOffer"
                            wire:click="$emit('createOrder', '{{ $bestOfferStats['offer_id'] }}')">
                            Generar orden de compra
                        </x-jet-button>
                    @endif
                </div>
            </div>

        </div>
    @endif


    <x-responsive-table>

        <div class="bg-gray-200 px-6 py-3">
            <p class="font-bold text-gray-700 text-lg ">
                <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                Detalle de ofertas recibidas
            </p>
        </div>
        {{-- TABLA --}}
        @if ($allOffersStats)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-50">
                    <tr class="px-4 py-2">
                        <th scope="col" class="py-3 px-3">
                            ID
                        </th>
                        <th scope="col" class="w-1/4 py-3 px-3">
                            Proveedor
                        </th>
                        <th scope="col" class="py-3 px-3">
                            Fecha respuesta
                        </th>
                        <th scope="col" class="py-3 px-3">
                            Total TN
                        </th>
                        <th scope="col" class="py-3 px-3">
                            Total oferta
                        </th>
                        <th scope="col" class="w-1/4 py-3 px-3">
                            Características
                        </th>
                        <th scope="col" class="py-3 px-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($allOffersStats as $stats)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase text-center">
                                    {{ $stats['offer_id'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase whitespace-nowrap text-center">
                                    {{ $stats['supplier'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase text-center">
                                    {{ $stats['answered_at'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase text-center">
                                    {{ $stats['total_tn'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase text-center">
                                    {{ $stats['total'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase whitespace-nowrap">
                                    {{ $stats['products_quantities'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a
                                        href="{{ route('admin.tenderings.show-offer-detail', ['tendering' => $tendering->id, 'hash' => $stats['hash']]) }}">
                                        <x-jet-secondary-button title="Ver detalle">
                                            <i class="fas fa-list"></i>
                                        </x-jet-secondary-button>
                                    </a>
                                    <x-jet-button title="Generar orden de compra" wire:click="$emit('createOrder', '{{ $stats['offer_id'] }}')">
                                        <i class="fas fa-sticky-note"></i>
                                        {{-- Ticket icon --}}
                                    </x-jet-button>
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

    </x-responsive-table>

</div>

@push('script')
    <script>
        Livewire.on('createOrder', offerId => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Estás a punto de crear una orden de compra para este proveedor. ¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Sí, crear orden',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.emitTo('tenderings.show-finished-tendering', 'createPurchaseOrder', offerId);

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

    <script>
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
    </script>

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
