<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tasks.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Tareas de
                <span class="uppercase font-bold">{{ $task_type_name }}</span>
            </h1>

            <a href="#">
                <x-jet-secondary-button>
                    <i class="fas fa-info-circle mr-2"></i>
                    Ayuda
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    @if ($running_task)
        <div class="bg-orange-100 border-t-4 border-orange-500 rounded-b text-orange-900 px-4 py-3 shadow-md mb-5 rounded-lg"
            role="alert">
            <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-orange-500 mr-4"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                            d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                    </svg></div>
                <div>
                    <p class="font-bold">Una tarea de tipo <span class="uppercase">{{ $task_type_name }}</span> se está
                        ejecutando ahora mismo.</p>
                    <p class="text-sm">
                        Si quieres ejecutar otra tarea de este tipo, deberás esperar a que la tarea actual finalice.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-stone-100 border-t-4 border-stone-500 rounded-b text-stone-900 px-4 py-3 shadow-md mb-5 rounded-lg"
            role="alert">
            <div class="flex justify-between items-center">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-stone-500 mr-4"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">
                            Ninguna tarea de tipo <span class="uppercase">{{ $task_type_name }}</span>
                            se está ejecutando ahora mismo.</p>
                        <p class="text-sm">
                            Puedes iniciar una nueva tarea pulsando el botón "Iniciar nueva tarea" o bien, elegir una
                            tarea PENDIENTE de la lista.
                        </p>
                    </div>
                </div>
                <div>
                    <x-jet-button wire:click="$emit('start')">
                        <i class="fas fa-play mr-2"></i>
                        Iniciar nueva tarea
                    </x-jet-button>
                </div>
            </div>
        </div>
    @endif

    <x-responsive-table>

        <div class="px-6 py-4 grid grid-cols-9 gap-3 bg-grat-50">

            {{-- Empleado asociado --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Empleado asociado</x-jet-label>
                <select class="input-control w-full" wire:model="filters.employee_id">
                    <option value="" class="text-md">Todos</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" class="text-md">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Estado de la tarea --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Estado de la tarea</x-jet-label>
                <select class="input-control w-full" wire:model="filters.task_status_id">
                    <option value="" class="text-md">Todos</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" class="text-md">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Desde fecha --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Desde fecha</x-jet-label>
                <x-jet-input wire:model="filters.fromDate" type="date" class="w-full" />
            </div>

            {{-- Hasta fecha --}}
            <div class="col-span-2 rounded-lg gap-2">
                <x-jet-label class="mb-1">Hasta fecha</x-jet-label>
                <x-jet-input wire:model="filters.toDate" type="date" class="w-full" />
            </div>

            {{-- Limpiar filtros --}}
            <div class="col-span-1 rounded-lg flex items-end justify-center pb-1 space-x-3">
                <x-jet-button wire:click="resetFilters">
                    <i class="fas fa-eraser"></i>
                </x-jet-button>
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf"></i>
                </x-jet-danger-button>
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
                            Estado
                        </th>
                        <th class="px-4 py-2">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($tasks as $task)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $task['id'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $task['started_at'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $task['started_by'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $task['finished_at'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $task['finished_by'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                @switch($task['status'])
                                    @case(1)
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-stone-100 text-stone-800">
                                            PENDIENTE
                                        </span>
                                    @break

                                    @case(2)
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            EN PROCESO
                                        </span>
                                    @break

                                    @case(3)
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            FINALIZADA
                                        </span>
                                    @break

                                    @case(4)
                                        <span
                                            class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            CANCELADA
                                        </span>
                                    @break

                                    @default
                                @endswitch
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    @switch($task['status'])
                                        @case(1)
                                            @if (!$running_task)
                                                <x-jet-button>
                                                    <i class="fas fa-play"></i>
                                                </x-jet-button>
                                            @endif
                                        @break

                                        @case(2)
                                            <x-jet-button wire:click="$emit('finish', '{{ $task['id'] }}')">
                                                <i class="fas fa-stop"></i>
                                            </x-jet-button>
                                        @break

                                        @case(3)
                                            <x-jet-secondary-button wire:click="showFinishedTask({{ $task['id'] }})">
                                                <i class="fas fa-list"></i>
                                            </x-jet-secondary-button>
                                        @break

                                        @default
                                    @endswitch
                                </div>
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

@push('script')
    <script>
        Livewire.on('start', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Iniciarás una nueva tarea y quedará registro!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Sí, iniciar tarea',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.emitTo('tasks.tasks-management', 'startNewTask');

                    Livewire.on('success', message => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: message
                        });
                    });

                    Livewire.on('error', message => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                            showConfirmButton: true,
                            confirmButtonColor: '#1f2937',
                        });
                    });
                }
            })
        });
    </script>

    <script>
        Livewire.on('finish', taskId => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Finalizarás esta tarea y quedará registro!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Sí, finalizar tarea',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.emitTo('tasks.tasks-management', 'finishTask', taskId);

                    Livewire.on('success', message => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: message
                        });
                    });

                    Livewire.on('error', message => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                            showConfirmButton: true,
                            confirmButtonColor: '#1f2937',
                        });
                    });
                }
            })
        });
    </script>

    <script>
        Livewire.on('success', message => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: 'success',
                title: message
            });
        });

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
