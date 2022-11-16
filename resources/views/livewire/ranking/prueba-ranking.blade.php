<div>
    <span class="font-bold">Mejor oferta recibida</span>
    <hr class="my-1">

    @isset($bestFinalOffer)

        <div class="flex justify-between mb-2">
            <p class="text-sm font-bold">
                Oferta #{{ $bestFinalOffer->id }}</span>
            </p>
            <p class="text-sm font-bold">
                Proveedor:
                <span class="font-normal">{{ $bestFinalOffer->hash->supplier->business_name }}</span>
            </p>
            <p class="text-sm font-bold">
                Total:
                <span class="font-normal">${{ number_format($bestFinalOffer->total, 2, ',', '.') }}</span>

            </p>
        </div>

        <p class="text-sm mb-2">
            La oferta pertenece a la categoría
            <span class="font-bold italic">{{ $offerTypeMessage }}.</span>
        </p>

        @isset($equalTotalsMessage)
            <p class="text-sm">- {{ $equalTotalsMessage }}</p>
        @endisset

        @isset($equalPurchasesCountMessage)
            <p class="text-sm">- {{ $equalPurchasesCountMessage }}</p>
        @endisset

        @isset($equalDeliveryDateMessage)
            <p class="text-sm">- {{ $equalDeliveryDateMessage }}</p>
        @endisset

        @isset($equalCreatedAtMessage)
            <p class="text-sm">- {{ $equalCreatedAtMessage }}</p>
        @endisset

        @isset($randomMessage)
            <p class="text-sm">- {{ $randomMessage }}</p>
        @endisset

        <div class="flex justify-end gap-3">
            <a
                href="{{ route('admin.tenderings.show-offer-detail', ['tendering' => $tendering, 'hash' => $bestFinalOfferHash]) }}">
                <x-jet-secondary-button>
                    {{ __('Ver detalle') }}
                </x-jet-secondary-button>
            </a>
            @if (!$hasPurchaseOrderCreated)
            <x-jet-button wire:click="createPurchaseOrder">
                {{ __('Aceptar y crear orden de compra') }}
            </x-jet-button>
            @endif
        </div>
    @else
        <p class="text-sm">No hay ofertas recibidas.</p>
    @endisset



</div>


@push('script')
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
