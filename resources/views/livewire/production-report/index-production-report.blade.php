<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reporte de tareas
            </h2>
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4 grid grid-cols-10 gap-3">

            <div class="col-span-10">
                <span class="font-bold text-gray-700 text-lg">
                    <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                    Reporte de tareas
                </span>
            </div>

            {{-- Tipo de tarea --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Categoría de tarea</x-jet-label>
                <select class="input-control w-full" wire:model="filters.category">
                    <option value="" class="text-md">Todos</option>
                    <option value="movement" class="text-md">Movimiento</option>
                    <option value="transformation" class="text-md">Producción</option>
                    <option value="movement_transformation" class="text-md">Movimiento y producción</option>
                </select>
            </div>

            {{-- Tipo de tarea --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Tipo de tarea</x-jet-label>
                <select class="input-control w-full" wire:model="filters.type_of_task_id">
                    <option value="" class="text-md">Todos</option>
                    @foreach ($types_of_tasks as $type_of_task)
                        <option value="{{ $type_of_task->id }}" class="text-md">{{ $type_of_task->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Desde fecha --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Desde fecha inicio</x-jet-label>
                <x-jet-input wire:model="filters.from_date" type="datetime-local" class="w-full" />
            </div>

            {{-- Hasta fecha --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Hasta fecha fin</x-jet-label>
                <x-jet-input wire:model="filters.to_date" type="datetime-local" class="w-full" />
            </div>

            {{-- Limpiar filtros --}}
            <div class="col-span-2 rounded-lg flex items-end justify-center pb-1 space-x-3">
                <x-jet-button wire:click="resetFilters">
                    <i class="fas fa-eraser mr-2"></i>
                    Limpiar
                </x-jet-button>
                <a href="{{ route('admin.tasks-report.pdf', [
                    'category' => $filters['category'],
                    'type_of_task_id' => $filters['type_of_task_id'],
                    'from_date' => $filters['from_date'],
                    'to_date' => $filters['to_date'],
                ]) }}">
                    <x-jet-danger-button>
                        <i class="fas fa-file-pdf mr-2"></i>
                        Exportar
                    </x-jet-danger-button>
                </a>
            </div>
        </div>

        @if ($tasks)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th class="px-4 py-2">
                            ID
                        </th>
                        <th class="px-4 py-2">
                            Tipo de tarea
                        </th>
                        <th class="px-4 py-2">
                            Fecha inicio
                        </th>
                        <th class="px-4 py-2">
                            Iniciada por
                        </th>
                        <th class="px-4 py-2">
                            Fecha fin
                        </th>
                        <th class="px-4 py-2">
                            Finalizada por
                        </th>
                        <th class="px-4 py-2">
                            Volumen
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($tasks as $task)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $task->id }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $task->typeOfTask->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ Date::parse($task->started_at)->format('d/m/Y H:i') }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    @php
                                        $user_started = App\Models\User::find($task->started_by)->name;
                                    @endphp
                                    {{ $user_started }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ Date::parse($task->finished_at)->format('d/m/Y H:i') }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    @php
                                        $user_finished = App\Models\User::find($task->started_by)->name;
                                    @endphp
                                    {{ $user_finished }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm">
                                    @if ($task->lot->sublots->sum('initial_m2') == 0)
                                        N/A
                                    @else
                                        {{ $task->lot->sublots->sum('initial_m2') }} m2
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($tasks->hasPages())
                <div class="px-6 py-4">
                    {{ $tasks->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

    </x-responsive-table>
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
