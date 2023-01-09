<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lotes</h2>
        </div>
    </x-slot>

    <x-responsive-table>
        <div class="px-4 py-3">
            <div class="grid grid-cols-8 gap-4">
                <div class="col-span-4">
                    <x-jet-label class="mb-1">Tipo de tarea</x-jet-label>
                    <select wire:model='filters.task_name' class="input-control w-full">
                        <option disabled value="">Seleccione una opción</option>
                        @foreach ($typesOfTasks as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 rounded-lg">
                    <x-jet-label class="mb-1">Desde fecha</x-jet-label>
                    <x-jet-input wire:model="filters.fromDate" type="date" class="w-full" />
                </div>

                {{-- Hasta fecha --}}
                <div class="col-span-2 rounded-lg">
                    <x-jet-label class="mb-1">Hasta fecha</x-jet-label>
                    <x-jet-input wire:model="filters.toDate" type="date" class="w-full" />
                </div>
            </div>
        </div>

        @if ($stats != null)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th scope="col" class="px-4 py-2">
                            ID
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Código
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Tarea
                        </th>
                        <th scope="col" class="px-4 py-2">
                            ID Tarea
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Sublotes
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Creado el
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Creado por
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($stats as $stat)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['id'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['lot_code'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['task'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['task_id'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['sublots_count'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['created_at'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['created_by'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                               <a href="{{ route('admin.sublots.index', ['lot' => $stat['lot']]) }}">
                                <x-jet-secondary-button>
                                    <i class="fas fa-list"></i>
                                </x-jet-secondary-button>
                               </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

    </x-responsive-table>

</div>
