<div class="container py-6">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl border bg-white rounded-xl p-10">

        {{-- <div class="flex flex-col md:flex-row gap-2  w-full">
            <div class="w-full md:w-1/2">
                <x-jet-label class="mb-1">Desde fecha</x-jet-label>
                <x-jet-input wire:model="filtros.fecha_inicio" type="date" class="w-full" />
            </div>

            <div class="w-full md:w-1/2 mb-3">
                <x-jet-label class="mb-1">Hasta fecha</x-jet-label>
                <x-jet-input wire:model="filtros.fecha_fin" type="date" class="w-full" />
            </div>
        </div> --}}

        <div class="flex flex-col md:flex-row gap-2 mb-3">
            {{-- ROLLOS --}}
            <div class="w-full md:w-1/5 border rounded-lg">
                <div class="flex justify-between mx-2 mt-2">
                    <div>
                        <span class="text-5xl">
                            {{ $stats['total_trunks'] }}
                        </span>
                        <p class="text-left text-gray-500 font-semibold">Rollos cortados</p>
                    </div>
                    <div class="flex flex-col items-end justify-start gap-1">
                        <p class="text-right text-gray-500 font-semibold">horas</p>
                        <span class="text-4xl">
                            {{ $stats['horas_corte'] }}
                        </span>
                    </div>
                </div>
                <div class="bg-gray-100 p-2">
                    <p class="text-center text-gray-500 text-lg font-bold">
                        {{ $stats['rollos_por_hora'] }}
                        <span class="font-normal">
                            rollos/hora
                        </span>
                    </p>
                </div>
            </div>

            {{-- FAJAS HÚMEDAS --}}
            <div class="w-full flex flex-col justify-between md:w-1/5 border rounded-lg">
                <div class="mx-2 mt-2">
                    <div class="flex flex-col justify-between">
                        <p class="text-left text-gray-500 font-semibold">Fajas cortadas</p>
                        <span class="text-3xl font-semibold">
                            {{ $stats['total_m2_cortados'] }} m²
                        </span>
                    </div>
                </div>
                <div class="bg-gray-100 p-2">
                    <p class="text-gray-500 text-lg font-semibold">
                        Actual:
                        <span class="font-normal">
                            {{ $stats['m2_humedas'] }} m²
                        </span>
                    </p>
                </div>
            </div>

            {{-- FAJAS SECAS --}}

            <div class="w-full flex flex-col justify-between md:w-1/5 border rounded-lg">
                <div class="mx-2 mt-2">
                    <div class="flex flex-col justify-between">
                        <p class="text-left text-gray-500 font-semibold">Fajas secadas</p>
                        <span class="text-3xl font-semibold">
                            {{ $stats['total_m2_secados'] }} m²
                        </span>
                    </div>
                </div>
                <div class="bg-gray-100 p-2">
                    <p class="text-gray-500 text-lg font-semibold">
                        Actual:
                        <span class="font-normal">
                            {{ $stats['m2_secas'] }} m²
                        </span>
                    </p>
                </div>
            </div>

            {{-- FAJAS HÚMEDAS --}}
            <div class="w-full flex flex-col justify-between md:w-1/5 border rounded-lg">
                <div class="mx-2 mt-2">
                    <div class="flex flex-col justify-between">
                        <p class="text-left text-gray-500 font-semibold">Fajas machimbradas</p>
                        <span class="text-3xl font-semibold">
                            {{ $stats['total_m2_machimbrados'] }} m²
                        </span>
                    </div>
                </div>
                <div class="bg-gray-100 p-2">
                    <p class="text-gray-500 text-lg font-semibold">
                        Actual:
                        <span class="font-normal">
                            {{ $stats['m2_machimbradas'] }} m²
                        </span>
                    </p>
                </div>
            </div>

            {{-- FAJAS HÚMEDAS --}}
            <div class="w-full flex flex-col justify-between md:w-1/5 border rounded-lg">
                <div class="mx-2 mt-2">
                    <div class="flex flex-col justify-between">
                        <span class="text-3xl font-bold">
                            {{ $stats['total_empaquetados'] }}
                            <span class="text-lg">
                                ≈{{ $stats['total_m2_empaquetados'] }} m²
                            </span>
                        </span>
                        <p class="text-left text-gray-500 font-semibold">Paquetes preparados</p>
                    </div>
                </div>
                <div class="bg-gray-100 p-2">
                    <p class="text-gray-500 text-lg font-semibold">
                        Actual:
                        <span class="font-normal">
                            {{ $stats['m2_empaquetadas'] }} m²
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-2 max-h-90 mb-3">
            <div class="flex justify-between w-full md:w-2/5 border p-2 rounded-lg">
                {!! $generalesPieChart->container() !!}
                {!! $generalesPieChart->script() !!}
            </div>

            {{-- INTERMEDIO --}}
            <div class="flex flex-col justify-between w-full md:w-1/5 border p-2 rounded-lg">

                <div>
                    <p class="text-left text-gray-500 font-semibold">Línea de corte</p>
                    <div class="flex flex-col justify-between">
                        <span class="text-4xl">
                            {{ $stats['cortes_por_hora'] }}
                            <span class="text-lg font-semibold">
                                m²/hora
                            </span>
                        </span>
                    </div>
                </div>

                <div class="max-h-48">
                    {!! $corteMachimbradoraChart->container() !!}
                    {!! $corteMachimbradoraChart->script() !!}
                </div>

                <div class="flex flex-col items-end">
                    <p class="text-left text-gray-500 font-semibold">Machimbradora</p>
                    <div class="flex flex-col justify-between">
                        <span class="text-4xl">
                            {{ $stats['machimbrados_por_hora'] }}
                            <span class="text-lg font-semibold">
                                m²/hora
                            </span>
                        </span>
                    </div>
                </div>

            </div>

            <div class="flex justify-between w-full md:w-2/5 border p-2 rounded-lg">
                {!! $horasTrabajoChart->container() !!}
                {!! $horasTrabajoChart->script() !!}
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-2">
            <div class="flex justify-between w-full md:w-2/5 border p-2 rounded-lg">
                {{-- {!! $chart->container() !!}
                {!! $chart->script() !!} --}}
            </div>
        </div>
    </div>
</div>
