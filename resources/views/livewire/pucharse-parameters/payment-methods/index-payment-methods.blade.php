<div class="my-6">

    <x-jet-action-section>
        <x-slot name="title">
            Métodos de pago
        </x-slot>

        <x-slot name="description">
            Lista de los métodos de pago existentes
        </x-slot>

        <x-slot name="content">
            <div class="rounded-lg overflow-hidden border">
                <table class="text-gray-500 w-full text-sm">
                    <thead class="border-b border-gray-300 bg-gray-100"">
                        <tr class="text-center uppercase">
                            <th class="py-2">ID</th>
                            <th class="py-2">Descripción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($payment_methods as $payment_method)
                            <tr class="text-center">
                                <td class="py-2">
                                    <span class="uppercase">
                                        {{ $payment_method->id }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <span class="uppercase">
                                        {{ $payment_method->name }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-slot>
    </x-jet-action-section>
</div>
