<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tasks.manage', ['task_type' => $type_of_task]) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <a href="{{ route('admin.task-detail.pdf', $task) }}">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    @if ($task->cancelled)
        <div class="max-w-6xl mx-auto bg-red-100 flex justify-center items-center text-red-700 px-4 py-3 rounded relative gap-4 mb-5"
            role="alert">
            {{-- <div> --}}
            <i class="fas fa-ban mr-5 text-5xl"></i>
            {{-- </div> --}}
            <div class="flex flex-col">
                <p class="font-bold font-mono uppercase">Atención</p>
                <p class="font-mono text-sm">
                    El detalle de esta tarea no es válida ya que la misma fue anulada por
                    {{ $user_who_cancelled }}
                    el día
                    {{ Date::parse($task->cancelled_at)->format('d-m-Y') }}
                    a las
                    {{ Date::parse($task->cancelled_at)->format('H:i') }}.
                </p>
            </div>
        </div>
    @endif

    <div class="px-8 py-6 max-w-6xl mx-auto bg-white rounded-lg shadow">

        <h1 class="font-mono font-bold text-2xl text-center mb-2 text-gray-800 leading-tight">
            Detalle de tarea #
            <span class="uppercase font-semibold text-md">
                {{ $taskData['task_id'] }}
            </span>
        </h1>

        <hr class="my-1">

        <div class="flex flex-col gap-2 mt-6 font-mono">
            <x-jet-label class="font-bold uppercase" value="Tipo de tarea" />
            <p class="text-sm">{{ $taskData['type_of_task_name'] }}</p>
        </div>

        <div class="grid grid-cols-8 gap-4 mt-4 mb-6 font-mono">

            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Iniciada por" />
                    <p class="text-sm">{{ $taskData['started_by'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Fecha de inicio" />
                    <p class="text-sm">{{ $taskData['started_at'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Finalizada por" />
                    <p class="text-sm">{{ $taskData['finished_by'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Fecha de fin" />
                    <p class="text-sm">{{ $taskData['finished_at'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Área origen" />
                    <p class="text-sm">{{ $taskData['origin_area'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Etapa inicial" />
                    <p class="text-sm">{{ $taskData['initial_phase'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Área destino" />
                    <p class="text-sm">{{ $taskData['destination_area'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold uppercase" value="Etapa final" />
                    <p class="text-sm">{{ $taskData['final_phase'] }}</p>
                </div>
            </div>
        </div>

        {{-- DETALLE DE PRODUCCIÓN --}}
        @if ($movement || $initial)
            <h1 class="font-mono font-bold text-lg uppercase">Detalle de movimiento</h1>
        @else
            <h1 class="font-mono font-bold text-lg uppercase">Detalle de producción</h1>
        @endif
        <hr class="my-1">

        <h2 class="font-semibold font-mono text-md mt-4 mb-2">Detalle inicial</h2>
        <x-responsive-table>
        <table class="min-w-full divide-y border">
            <thead>
                <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                    <th scope="col" class="w-1/8 py-2">
                        Lote
                    </th>
                    <th scope="col" class="{{ $initial ? 'w-1/4' : 'w-1/8' }} py-2">
                        {{ $initial ? 'Proveedor' : 'Sublote' }}
                    </th>
                    <th scope="col" class="{{ $initial ? 'w-1/4' : 'w-1/2' }} py-2">
                        Producto
                    </th>
                    <th scope="col" class="py-2">
                        {{ $movement || $initial || $movement_transformation ? 'Stock original' : 'Cantidad consumida' }}
                    </th>
                    <th scope="col" class="py-2">
                        M2
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($inputData as $item)
                    <tr class="uppercase text-sm font-mono">
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['lot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['sublot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['product_name'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['quantity'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase {{ $item['m2'] == 'N/A' ? 'px-3' : ''}}">
                                {{ $item['m2'] }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </x-responsive-table>

        <h2 class="font-semibold font-mono text-md mt-4 mb-2">Detalle final</h2>

        <x-responsive-table>
        <table class="min-w-full divide-y border">
            <thead>
                <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                    <th scope="col" class="w-1/8 py-2">
                        Lote
                    </th>
                    <th scope="col" class="w-1/8 py-2">
                        Sublote
                    </th>
                    <th scope="col" class="w-1/2 py-2">
                        Producto
                    </th>
                    <th scope="col" class="py-2">
                        {{ $movement || $initial || $movement_transformation ? 'Cantidad movida' : 'Cantidad producida' }}
                    </th>
                    <th scope="col" class="py-2">
                        M2
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($outputData as $item)
                    <tr class="uppercase text-sm font-mono">
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['lot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['sublot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['product_name'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['quantity'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $item['m2'] }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </x-responsive-table>
    </div>

</div>
