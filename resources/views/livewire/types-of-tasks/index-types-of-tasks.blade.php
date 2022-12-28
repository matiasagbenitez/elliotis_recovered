<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tipos de tareas</h2>
            @livewire('types-of-tasks.create-type-of-task')
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
        </div>

        @if ($types_of_tasks->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                        <th scope="col"
                            class="px-4 py-2">
                            ID
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/3">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/6">
                            Área origen
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/6">
                            Área destino
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/6">
                            Etapa inicial
                        </th>
                        <th scope="col"
                            class="px-4 py-2 w-1/6">
                            Etapa final
                        </th>
                        <th scope="col"
                            class="px-4 py-2">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($types_of_tasks as $type_of_task)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $type_of_task->id }}
                                </p>
                            </td>
                            <td class="px-6 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $type_of_task->name }}
                                </p>
                            </td>
                            <td class="px-6 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $type_of_task->originArea->name }}
                                </p>
                            </td>
                            <td class="px-6 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $type_of_task->destinationArea->name }}
                                </p>
                            </td>
                            <td class="px-6 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $type_of_task->initialPhase->prefix }}
                                </p>
                            </td>
                            <td class="px-6 py-1 text-center">
                                <p class="text-sm uppercase">
                                    {{ $type_of_task->finalPhase->prefix }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    @livewire('types-of-tasks.edit-type-of-task', ['type_of_task' => $type_of_task], key($type_of_task->id))
                                    <x-jet-danger-button wire:click="$emit('deleteTypeOfTask', '{{ $type_of_task->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </x-jet-danger-button>
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

        @if ($types_of_tasks->hasPages())
            <div class="px-6 py-3">
                {{ $types_of_tasks->links() }}
            </div>
        @endif

    </x-responsive-table>

    @push('script')
        <script>
            Livewire.on('deleteTypeOfTask', id => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Sí, eliminar tipo de tarea',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('types-of-tasks.index-types-of-tasks', 'delete', id);

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
    @endpush

</div>
