<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lotes</h2>
        </div>
    </x-slot>

    <x-responsive-table>
        <div class="px-8 py-3 bg-gray-200">
            <h1 class="mb-2 text-lg font-bold text-gray-500">Listado de lotes</h1>
            <div class="grid grid-cols-8 gap-4">
                <div class="col-span-2">
                    <x-jet-label class="mb-1 font-bold text-gray-600">Tipo de tarea</x-jet-label>
                    <select wire:model='filters.type_of_task' class="input-control w-full">
                        <option value="">Todos los tipos de tarea</option>
                        @foreach ($typesOfTasks as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <x-jet-label class="mb-1 font-bold text-gray-600">Sublotes</x-jet-label>
                    <select wire:model='filters.sublots_availability' class="input-control w-full">
                        <option value="all">Todos los tipos</option>
                        <option value="available">Lotes con disponibilidad</option>
                        <option value="unavailable">Lotes sin disponibilidad</option>
                        <option value="partially">Lotes con disponibilidad parcial</option>
                    </select>
                </div>
                <div class="col-span-2 rounded-lg">
                    <x-jet-label class="mb-1 font-bold text-gray-600">Desde fecha</x-jet-label>
                    <x-jet-input wire:model="filters.fromDate" type="date" class="w-full" />
                </div>

                {{-- Hasta fecha --}}
                <div class="col-span-2 rounded-lg">
                    <x-jet-label class="mb-1 font-bold text-gray-600">Hasta fecha</x-jet-label>
                    <x-jet-input wire:model="filters.toDate" type="date" class="w-full" />
                </div>
            </div>
        </div>

        @if ($stats != null)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th scope="col" class="px-4 whitespace-nowrap py-2">
                            Fecha creación
                        </th>
                        <th scope="col" class="px-4 whitespace-nowrap py-2">
                            Código
                        </th>
                        <th scope="col" class="px-4 whitespace-nowrap py-2">
                            Tarea generadora
                        </th>
                        <th scope="col" class="px-4 whitespace-nowrap py-2">
                            M2 iniciales
                        </th>
                        <th scope="col" class="px-4 whitespace-nowrap py-2">
                            Sublotes generados
                        </th>
                        <th scope="col" class="px-4 whitespace-nowrap py-2">
                            Disponibilidad sublotes
                        </th>
                        <th scope="col" class="px-4 whitespace-nowrap py-2">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($stats as $stat)
                        <tr class="bg-gray-50">
                            <td class="px-2 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['created_at'] }}
                                </p>
                            </td>
                            <td class="px-2 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['lot_code'] }}
                                </p>
                            </td>
                            <td class="px-2 py-1">
                                <a href="{{ route('admin.tasks.show', $stat['task_id']) }}" class="hover:underline">
                                    <p class="text-sm uppercase">
                                        {{ $stat['task'] }} ({{ $stat['task_id'] }})
                                    </p>
                                </a>
                            </td>
                            <td class="px-2 py-1 text-center">
                                <p class="text-sm">
                                    {{ $stat['m2'] }}
                                </p>
                            </td>
                            <td class="px-2 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['sublots_count'] }}
                                </p>
                            </td>
                            <td class="px-2 py-1 text-center">
                                <p class="text-sm uppercase">
                                    @switch($stat['sublots_availability'])
                                        @case(0)
                                            <span
                                                class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Sin disponibilidad
                                            </span>
                                        @break

                                        @case(1)
                                            <span
                                                class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disponibilidad total
                                            </span>
                                        @break

                                        @case(2)
                                            <span
                                                class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Disponibilidad parcial
                                            </span>
                                        @break

                                        @default
                                    @endswitch
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <a href="{{ route('admin.sublots.index', ['lot' => $stat['id']]) }}">
                                    <x-jet-secondary-button>
                                        <i class="fas fa-eye"></i>
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
