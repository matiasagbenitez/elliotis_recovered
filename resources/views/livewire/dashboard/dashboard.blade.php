<div class="p-6 space-y-6">

    <div class="flex">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div class="col-span-1 sm:col-span-2 md:col-span-5">
                <span class="font-bold text-gray-700 text-lg">
                    <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                    Control de líneas de trabajo
                    <hr class="my-2">
                </span>
            </div>
            @foreach ($taskTypesStats as $taskStat)
                <a href="{{ route('admin.tasks.manage', $taskStat['id']) }}">

                    <div
                        class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer h-full border flex flex-col justify-between">

                        <div class="font-mono">
                            <h1 class="uppercase font-bold text-center">{{ $taskStat['name'] }}</h1>
                        </div>

                        <div class="mt-5">
                            @if ($taskStat['running_task'] == true)
                                <div class="space-y-1 text-center font-semibold">
                                    <span
                                        class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        LÍNEA EN FUNCIONAMIENTO
                                    </span>
                                </div>
                            @elseif ($taskStat['running_task'] == false)
                                <div class="space-y-1 text-center font-semibold ">
                                    <span
                                        class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        LÍNEA DETENIDA
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    </div>

    <div class="flex gap-10">
        <div class="w-full md:w-1/2 space-y-6">
            <div>
                <span class="font-bold text-gray-700 text-lg">
                    <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                    Resumen de órdenes de venta
                    <hr class="my-2">
                </span>
                @if ($saleOrdersStats)
                    <ul class="list-disc ml-10">
                        @foreach ($saleOrdersStats as $item)
                            <li>
                                x {{ $item['quantity'] }}
                                <span class="font-semibold">{{ $item['name'] }} </span>
                                (≈{{ $item['m2_total'] }} m²)
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center">No hay órdenes de venta pendientes.</p>
                @endif
            </div>
            <div>
                <span class="font-bold text-gray-700 text-lg">
                    <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                    Estimación de estado de avance por etapa
                    <hr class="my-2">
                </span>
                @if ($pending)
                    @if ($productsStats)
                        <table class="w-100%">
                            <thead>
                                <tr class="font-bold text-center uppercase">
                                    <td class="w-1/4">Etapa</td>
                                    <td class="w-1/4">Requerido</td>
                                    <td class="w-1/4">Stock</td>
                                    <td class="w-1/4">Falta</td>
                                    <td class="w-1/4">Completo</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productsStats as $productStat)
                                    <tr class="text-center">
                                        <td class="text-left">{{ $productStat['phase_name'] }}</td>
                                        <td class="text-center">{{ $productStat['total'] }} m²</td>
                                        <td class="text-center">{{ $productStat['m2_real_stock'] }} m²</td>
                                        <td class="text-center">{{ $productStat['m2_necessary_stock'] }} m²</td>
                                        <td class="text-center">{{ $productStat['percentage'] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @else
                    <p class="text-center">Dado que no hay órdenes de venta, no se requiere calcular el estado de avance
                        por etapas.</p>
                @endif
            </div>
        </div>

        <div class="w-full md:w-1/2">
            {!! $chart1->container() !!}
            {!! $chart1->script() !!}
        </div>
    </div>

    <div>
        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-3">
            <div class="col-span-1 sm:col-span-2 md:col-span-6">
                <span class="font-bold text-gray-700 text-lg">
                    <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                    Otras opciones
                    <hr class="my-2">
                </span>
            </div>

            <a href="{{ route('admin.products.index') }}">
                <div class="border px-3 py-4 rounded-lg font-semibold">
                    <i class="fas fa-tree mr-2"></i>
                    Productos
                </div>
            </a>

            <a href="{{ route('admin.necessary-production.index') }}">
                <div class="border px-3 py-4 rounded-lg font-semibold">
                    <i class="fas fa-link mr-2"></i>
                    Producción necesaria
                </div>
            </a>

            <a href="{{ route('admin.tasks.index') }}">
                <div class="border px-3 py-4 rounded-lg font-semibold">
                    <i class="fas fa-tasks mr-2"></i>
                    Tareas
                </div>
            </a>

            <a href="">
                <div class="border px-3 py-4 rounded-lg font-semibold">
                    <i class="fas fa-mobile mr-2"></i>
                    Soporte técnico
                </div>
            </a>

            <a href="">
                <div class="border px-3 py-4 rounded-lg font-semibold">
                    <i class="fas fa-video mr-2"></i>
                    Videotutoriales
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <a href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    <div class="border px-3 py-4 rounded-lg font-semibold">
                        <i class="fas fa-arrow-alt-circle-left mr-2"></i>
                        Cerrar sesión
                    </div>
                </a>
            </form>

        </div>
    </div>

</div>
