<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Parámetros de compra</h2>
        </div>
    </x-slot>

    @livewire('pucharse-parameters.payment-conditions.index-payment-conditions')

    @livewire('pucharse-parameters.payment-methods.index-payment-methods')

    @livewire('pucharse-parameters.voucher-types.index-voucher-types')

</div>
