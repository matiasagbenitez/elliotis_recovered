<div class="py-3">

    <div>
        <div class="flex justify-between">
            <h2 class="font-bold text-xl uppercase text-gray-800 leading-tight text-center my-3">
                Estadísticas de producción en empaquetadora
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
                <p class="text-xl text-gray-700 font-bold">Total de tareas de empaquetado</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_tareas_empaquetado }} tareas</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Sublotes consumidos</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $cantidad_sublotes_entrada }} sublotes</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total machimbres entrada</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_fajas_entrada }}
                    ({{ $total_fajas_entrada_m2 }} m²)</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Tiempo de trabajo total</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $tiempo_empaquetado_formateado }}</span>
            </div>

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Detalle de fajas machimbradas procesadas</p>
                <hr>
                <ul class="list-disc ml-10">
                    @if ($productos_entrada)
                        @foreach ($productos_entrada as $item)
                            <li class="font-semibold text-gray-500">
                                {{ $item['nombre'] }}: {{ $item['m2'] }} m²
                            </li>
                        @endforeach
                    @else
                        <li class="font-semibold text-gray-500">
                            No hay datos para mostrar
                        </li>
                    @endif
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
                <p class="text-xl text-gray-700 font-bold">Total producción paquetes</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_paquetes_salida }} paquetes
                    ({{ $total_paquetes_salida_m2 }} m²)</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Sublotes generados</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $cantidad_sublotes_salida }} generados</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">m² x hora</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $m2_por_hora }} m²/h</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Paquetes por hora</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $paquetes_por_hora }} paq./h</span>
            </div>

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Detalle de paquetes producidos</p>
                <hr>
                <ul class="list-disc ml-10">
                    @if ($productos_salida)
                        @foreach ($productos_salida as $item)
                            <li class="font-semibold text-gray-500">
                                {{ $item['nombre'] }}: {{ $item['cantidad'] }} paquetes
                            </li>
                        @endforeach
                    @else
                        <li class="font-semibold text-gray-500">
                            No hay datos para mostrar
                        </li>
                    @endif
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
                        {{ $item['fecha'] }}: {{ $item['paquetes'] }} paquetes
                    </li>
                @endforeach
            @else
                <li class="font-semibold text-gray-500">
                    No hay datos para mostrar
                </li>
            @endif
        </ul>
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
