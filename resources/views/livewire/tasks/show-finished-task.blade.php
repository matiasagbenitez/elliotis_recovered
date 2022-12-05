<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tasks.manage', ['taskType' => $task->taskType]) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <a href="#">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    <div class="px-8 py-6 max-w-6xl mx-auto bg-white rounded-lg shadow">

        <h1 class="font-mono font-bold text-2xl text-center mb-2 text-gray-800 leading-tight">
            Detalle de tarea #{{ $taskData['id'] }}
            <span class="uppercase font-semibold text-md">
                ({{ $taskData['task_type_name'] }})
            </span>
        </h1>

        <hr class="my-1">

        <div class="grid grid-cols-8 gap-8 my-8 font-mono">
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="Área" />
                    <p class="text-sm">{{ $taskData['area'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="Tipo de tarea" />
                    <p class="text-sm">{{ $taskData['task_type_name'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="ID de tarea" />
                    <p class="text-sm">{{ $taskData['id'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="Estado de la tarea" />
                    <p class="text-sm">{{ $taskData['task_status_name'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="Fecha de inicio" />
                    <p class="text-sm">{{ $taskData['started_at'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="Usuario que inició la tarea" />
                    <p class="text-sm">{{ $taskData['started_by'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="Fecha de finalización" />
                    <p class="text-sm">{{ $taskData['finished_at'] }}</p>
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label class="font-bold" value="Usuario que finalizó la tarea" />
                    <p class="text-sm">{{ $taskData['finished_by'] }}</p>
                </div>
            </div>
        </div>

        {{-- DETALLE DE PRODUCCIÓN --}}
        <h1 class="font-mono font-bold text-lg uppercase">Detalle de producción</h1>
        <hr class="my-1">

        <h2 class="font-semibold font-mono text-md mt-4 mb-2">Productos de entrada</h2>

        {{-- <x-responsive-table> --}}
        <table class="min-w-full divide-y border">
            <thead>
                <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                    <th scope="col" class="w-1/6 px-4 py-2">
                        Lote
                    </th>
                    <th scope="col" class="w-1/6 px-4 py-2">
                        Sublote
                    </th>
                    <th scope="col" class="w-1/3 px-4 py-2">
                        Producto
                    </th>
                    <th scope="col" class="w-1/6 px-4 py-2">
                        Cantidad consumida
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($inputProductsData as $product)
                    <tr class="uppercase text-sm font-mono">
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['lot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['sublot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['product_name'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['consumed_quantity'] }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- </x-responsive-table> --}}

        {{-- SALIDA --}}
        <h2 class="font-semibold font-mono  text-md mt-4 mb-2">Productos de salida</h2>

        {{-- <x-responsive-table> --}}
        <table class="min-w-full divide-y border">
            <thead>
                <tr class="text-center text-gray-500 uppercase text-sm  font-mono font-thin">
                    <th scope="col" class="w-1/6 px-4 py-2">
                        Lote
                    </th>
                    <th scope="col" class="w-1/6 px-4 py-2">
                        Sublote
                    </th>
                    <th scope="col" class="w-1/3 px-4 py-2">
                        Producto
                    </th>
                    <th scope="col" class="w-1/6 px-4 py-2">
                        Cantidad producida
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($outputProductsData as $product)
                <tr class="uppercase text-sm font-mono">
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['lot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['sublot_code'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['product_name'] }}
                            </p>
                        </td>
                        <td class="px-6 py-2 text-center">
                            <p class="text-sm uppercase">
                                {{ $product['produced_quantity'] }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- </x-responsive-table> --}}

    </div>

</div>
