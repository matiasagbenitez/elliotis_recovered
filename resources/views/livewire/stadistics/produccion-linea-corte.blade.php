<div class="py-3">

    <div>
        <div class="flex justify-between">
            <h2 class="font-bold text-xl uppercase text-gray-800 leading-tight text-center my-3">
                Estadísticas de producción en línea de corte (corte de rollos)
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
                <p class="text-xl text-gray-700 font-bold">Total de tareas de corte</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_tareas_corte }} tareas</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total de sublotes de rollos</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $cantidad_sublotes_cortados }} sublotes</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total rollos cortados</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_rollos }} rollos</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Tiempo de corte total</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $tiempo_corte_formateado }}</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Rollos por hora (aprox.)</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $rollos_x_hora }} rollos/h</span>
            </div>

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Detalle de rollos cortados</p>
                <hr>
                <ul class="list-disc ml-10">
                    @foreach ($productos_cortados as $item)
                        <li class="font-semibold text-gray-500">
                            {{ $item['nombre'] }}: {{ $item['cantidad_consumida'] }} unidades
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:w-1/2">
            <div>
                <p class="text-xl text-gray-700 font-bold">Total de fajas cortadas</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_fajas_cortados }} unidades</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total m² de fajas cortadas</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_m2_cortados }} m²</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total sublotes generados</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $cantidad_sublotes_fajas_cortadas }}
                    sublotes</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">m² x hora</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $m2_x_hora }} m²/h</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">m² x rollo (aprox.)</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $m2_x_rollo }} m²/rollo</span>
            </div>

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Detalle de fajas obtenidas</p>
                <hr>
                <ul class="list-disc ml-10">
                    @foreach ($productos_fajas_cortadas as $item)
                        <li class="font-semibold text-gray-500">
                            {{ $item['nombre'] }}: {{ $item['m2_producidos'] }} m²
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="w-full sm:w-1/2">
            {!! $chart2->container() !!}
            {!! $chart2->script() !!}
        </div>
    </div>

    <div class="w-full sm:w-1/2">
        <p class="text-xl text-gray-700 font-bold">Top 5 días con mayor producción</p>
        <hr>
        <ul class="list-disc ml-10">
            @if ($top_5_dias)
                @foreach ($top_5_dias as $item)
                    <li class="font-semibold text-gray-500">
                        {{ $item['fecha'] }}: {{ $item['initial_m2'] }} m²
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
