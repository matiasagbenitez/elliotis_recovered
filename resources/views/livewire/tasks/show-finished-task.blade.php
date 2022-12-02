<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tasks.manage', ['taskType' => $task->taskType]) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de tarea #{{ $taskData['id'] }}
                <span class="uppercase font-semibold text-md">
                    ({{ $taskData['task_type_name'] }})
                </span>
            </h1>

            <a href="#">
                <x-jet-secondary-button>
                    <i class="fas fa-info-circle mr-2"></i>
                    Ayuda
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <div class="px-8 py-6 max-w-6xl mx-auto bg-white rounded-lg shadow">

        {{-- DETALLE DE TAREA --}}
        <h1 class="font-bold text-lg uppercase">Detalle de tarea</h1>
        <hr class="my-1">

        <div class="grid grid-cols-8 gap-3 my-4">
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Área" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['area'] }}" disabled />
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Tipo de tarea" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['task_type_name'] }}" disabled />
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="ID de tarea" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['id'] }}" disabled />
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Estado de la tarea" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['task_status_name'] }}" disabled />
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Fecha de inicio" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['started_at'] }}" disabled />
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Usuario que inició la tarea" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['started_by'] }}" disabled />
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Fecha de finalización" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['finished_at'] }}" disabled />
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Usuario que finalizó la tarea" />
                    <x-jet-input type="text" class="input-control" value="{{ $taskData['finished_by'] }}" disabled />
                </div>
            </div>
        </div>

        {{-- DETALLE DE PRODUCCIÓN --}}
        <h1 class="font-bold text-lg uppercase">Detalle de producción</h1>
        <hr class="my-1">

        <h2 class="font-semibold text-md mt-4 mb-2">Productos de entrada</h2>

        <x-responsive-table>
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead
                    class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                    <tr>
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
                        <tr class="bg-gray-50">
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['id'] }}
                                </p>
                            </td>
                            <td class="px-6 py-2 text-center">
                                <p class="text-sm uppercase">
                                    {{ $product['code'] }}
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
        </x-responsive-table>

        {{-- SALIDA --}}
        <h2 class="font-semibold text-md mt-4 mb-2">Productos de salida</h2>

        <x-responsive-table>
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead
                    class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                    <tr>
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
                        <tr class="bg-gray-50">
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
        </x-responsive-table>

    </div>

</div>
