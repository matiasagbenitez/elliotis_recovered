<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Etapas</h2>
            @livewire('phases.create-phase')
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
        </div>

        @if ($phases->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr>
                        <th scope="col"
                            class="px-4 py-2 text-center text-sm text-gray-500 uppercase">
                            ID
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-center text-sm text-gray-500 uppercase w-full">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-center text-sm text-gray-500 uppercase">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($phases as $phase)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $phase->id }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $phase->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    @livewire('phases.edit-phase', ['phase' => $phase], key($phase->id))
                                    <x-jet-danger-button wire:click="$emit('deletephase', '{{ $phase->id }}')">
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

        @if ($phases->hasPages())
            <div class="px-6 py-3">
                {{ $phases->links() }}
            </div>
        @endif

    </x-responsive-table>

    @push('script')
        <script>
            Livewire.on('deletephase', phaseId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Sí, eliminar etapa',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('phases.index-phases', 'delete', phaseId);

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
