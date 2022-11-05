<div>
    <x-jet-action-section>
        <x-slot name="title">
            Condiciones de pago
        </x-slot>

        <x-slot name="description">
            Lista de las condiciones de pago existentes
        </x-slot>

        <x-slot name="content">
            <div class="rounded-lg overflow-hidden">
                <table class="text-gray-500 w-full text-sm border">
                    <thead class="border-b border-gray-300 bg-gray-100">
                        <tr class="text-center uppercase">
                            <th class="py-2">ID</th>
                            <th class="py-2">Descripción</th>
                            <th class="py-2">Tipo de pago</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($payment_conditions as $payment_condition)
                            <tr class="text-center">
                                <td class="py-2">
                                    <span class="uppercase">
                                        {{ $payment_condition->id }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <span class="uppercase">
                                        {{ $payment_condition->name }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <div>
                                        @switch($payment_condition->is_deferred )
                                        @case(1)
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Diferido
                                            </span>
                                            @break
                                        @case(0)
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                No diferido
                                            </span>
                                            @break
                                        @default
                                    @endswitch
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-slot>
    </x-jet-action-section>
</div>
