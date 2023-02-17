<div class="py-3">

    <div>
        <div class="flex justify-between">
            <h2 class="font-bold text-xl uppercase text-gray-800 leading-tight text-center my-3">
                Estadísticas de producción en machimbradora
            </h2>
            <x-jet-danger-button wire:click="generatePDF">
                <i class="fas fa-file-pdf mr-2"></i>
                Generar PDF
            </x-jet-danger-button>
        </div>
        <div class="mt-5 flex items-end gap-5">
            <p class="text-lg text-gray-700 font-bold">Rango de fechas:</p>
            <p class="font-semibold text-gray-600">
                {{ $from_datetime }}
                &ensp; a &ensp;
                {{ $to_datetime }}
            </p>
        </div>
    </div>

    <br>

    <div class="flex flex-col md:flex-row gap-3 my-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:w-1/2">
            <div>
                <p class="text-xl text-gray-700 font-bold">Total de tareas de machimbrado</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_tareas_machimbrado }} tareas</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Sublotes consumidos</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $cantidad_sublotes_entrada }} sublotes</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total fajas secas procesadas</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_fajas_entrada }} ({{ $total_fajas_entrada_m2 }} m²)</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Tiempo de trabajo total</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $tiempo_machimbrado_formateado }}</span>
            </div>

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Detalle de fajas secas procesadas</p>
                <hr>
                <ul class="list-disc ml-10">
                    @foreach ($productos_machimbrados_entrada as $item)
                        <li class="font-semibold text-gray-500">
                            {{ $item['producto'] }}: {{ $item['m2'] }} m²
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="w-full sm:w-1/2">
            {!! $chart1->container() !!}
            {!! $chart1->script() !!}
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-3 my-10">
        <div class="w-full sm:w-1/2">
            {!! $chart2->container() !!}
            {!! $chart2->script() !!}
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:w-1/2">
            <div>
                <p class="text-xl text-gray-700 font-bold">Total de fajas machimbradas</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_fajas_salida }} ({{ $total_fajas_salida_m2 }} m²)</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Sublotes generados</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $cantidad_sublotes_salida }} generados</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">m² x hora</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $m2_x_hora }} m²/h</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Tasa de producción</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $tasa_produccion }}%</span>
            </div>

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Detalle de fajas machimbradas</p>
                <hr>
                <ul class="list-disc ml-10">
                    @foreach ($productos_machimbrados_salida as $item)
                        <li class="font-semibold text-gray-500">
                            {{ $item['producto'] }}: {{ $item['m2'] }} m²
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="w-full ">
        <p class="text-xl text-gray-700 font-bold">Top 5 días con mayor producción</p>
        <hr>
        <ul class="list-disc ml-10">
            @if ($top_5_dias)
                @foreach ($top_5_dias as $item)
                    <li class="font-semibold text-gray-500">
                        {{ $item['fecha'] }}: {{ $item['m2'] }} m²
                    </li>
                @endforeach
            @else
                <li class="font-semibold text-gray-500">
                    No hay datos para mostrar
                </li>
            @endif
        </ul>
    </div>

    <div class="w-full">
        {!! $chart3->container() !!}
        {!! $chart3->script() !!}
    </div>

</div>

@push('script')
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
