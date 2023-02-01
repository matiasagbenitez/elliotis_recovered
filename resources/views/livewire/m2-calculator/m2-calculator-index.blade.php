<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Calculadora</h2>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">

        <div class="px-6 py-4 rounded-t-lg bg-gray-200">
            <x-jet-label class="mb-2 text-lg font-semibold">Cantidad de m² requeridos</x-jet-label>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <x-jet-input wire:model.defer="required_m2" type="text" class="w-full"
                    placeholder="Ingrese la cantidad de metros cuadrados (m²) requeridos"></x-jet-input>

                <div class="flex justify-between gap-1">
                    <x-jet-button wire:click="calculate" class="px-4 py-3 whitespace-nowrap">Calcular</x-jet-button>
                    <x-jet-secondary-button wire:click="resetCalculation" class="px-4 py-3 whitespace-nowrap">Reiniciar</x-jet-secondary-button>

                </div>
            </div>
        </div>

        <div class="px-6 py-4 rounded-b-lg bg-white">
            <p class="text-sm italic">
                Para el cálculo de m² se considera un desperdicio del 10% debido al ensamblaje de las ranuras entre
                tablas.
            </p>

            @isset($required_m2)
                <div class="mt-3">
                    <p class="py-2">
                        Para cubrir <span class="font-bold">{{ $required_m2 }} m²</span> se tienen las siguientes opciones:
                    </p>

                    <ul>
                        @foreach ($products_quantities as $item)
                            <li class="list-disc ml-10">
                                <p class="{{ $item['best_option'] ? 'text-green-700' : '' }}">
                                    {{ $item['product'] }}
                                    &ensp;
                                    {{ $item['quantity_10'] }} ({{ $item['rounded_quantity_10'] }})
                                    &ensp;
                                    {{ $item['m2_total']}}
                                    &ensp;
                                    {{ $item['price']}}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endisset

            <hr class="my-5">

            <p class="text-sm italic">
                La opción más económica es la que tiene el menor precio por m². Teniendo en cuenta el stock disponible y el desperdicio del 10%, se tiene:
            </p>

            @isset($required_m2)
                <div class="mt-3">
                    <p class="py-2">
                        Para cubrir <span class="font-bold">{{ $required_m2 }} m²</span> se tienen las siguientes opciones:
                    </p>

                    <ul>
                        @foreach ($result as $item)
                            <li class="list-disc ml-10">
                                <p class="">
                                    {{ $item['product_name'] }}
                                    &ensp;
                                    {{ $item['quantity'] }}
                                    &ensp;
                                    {{ $item['m2_formated']}}
                                    &ensp;
                                    {{ $item['price_formated']}}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-3">
                    <p>
                        El total de m² es de <span class="font-bold">{{ $total_m2 }}</span>
                    </p>
                    <p>
                        El costo total es de <span class="font-bold">{{ $total_price }}</span>
                    </p>
                </div>
            @endisset

        </div>

    </div>
</div>
