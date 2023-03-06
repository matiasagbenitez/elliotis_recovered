<div class="py-3">

    <div>
        <div class="flex justify-between">
            <h2 class="font-bold text-xl uppercase text-gray-800 leading-tight text-center my-3">
                Estadísticas de ventas
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
                <p class="text-xl text-gray-700 font-bold">Total de ventas</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_ventas }} ventas</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total de clientes</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ $total_clientes }} clientes</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Total m²</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ number_format($total_m2, 2, ',', '.') }} m²</span>
            </div>

            <div>
                <p class="text-xl text-gray-700 font-bold">Promedio m²/venta</p>
                <hr>
                <span class="text-lg font-semibold text-gray-500">{{ number_format($promedio_m2_venta) }}
                    m²/venta</span>
            </div>

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Detalle de clientes</p>
                <hr>
                @if ($clientes)
                    <table class="w-full text-gray-600">
                        <thead>
                            <tr>
                                <th class="w-1/3"></th>
                                <th class="w-1/3"></th>
                                <th class="w-1/3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente['nombre'] }}</td>
                                    <td class="text-center">{{ $cliente['cantidad'] }} ventas</td>
                                    <td class="text-right">${{ number_format($cliente['total'], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <li>
                        No hay datos para mostrar
                    </li>
                @endif
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

            <div class="col-span-2">
                <p class="text-xl text-gray-700 font-bold">Productos más vendidos</p>
                <hr>
                <ul class="list-disc ml-10">
                    @if ($productos_vendidos_ordenados)
                        @foreach ($productos_vendidos_ordenados as $producto)
                            <li class="whitespace-nowrap">
                                <span class="font-semibold">{{ $producto['nombre'] }}</span>
                                <br>
                                Unidades: {{ $producto['unidades'] }}
                                &ensp;|&ensp;
                                <span class="font-semibold">
                                    m²: {{ number_format($producto['m2'], 2, ',', '.') }}
                                </span>
                                &ensp;|&ensp;
                                Total: ${{ number_format($producto['total'], 2, ',', '.') }}
                                <br><br>
                            </li>
                        @endforeach
                    @else
                        <li>
                            No hay datos para mostrar
                        </li>
                    @endif
                </ul>
            </div>
        </div>
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
