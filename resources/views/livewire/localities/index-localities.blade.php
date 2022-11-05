<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Localidades</h2>
            @livewire('localities.create-locality')
        </div>
    </x-slot>

    <x-responsive-table>

        <div class="px-6 py-4">
            <x-jet-input type="text" wire:model="search" class="w-full" placeholder="Filtre su búsqueda aquí..." />
        </div>

        @if ($localities->count())
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr>
                        <th scope="col"
                            class="px-4 py-2 text-center text-md font-bold text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col"
                            class="w-2/6 px-4 py-2 text-center text-md font-bold text-gray-500 uppercase tracking-wider">
                            Localidad
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-md font-bold text-gray-500 uppercase tracking-wider">
                            Código postal
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-md font-bold text-gray-500 uppercase tracking-wider">
                            Provincia
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-md font-bold text-gray-500 uppercase tracking-wider">
                            País
                        </th>
                        <th scope="col"
                            class="w-1/6 px-4 py-2 text-center text-md font-bold text-gray-500 uppercase tracking-wider">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($localities as $locality)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $locality->id }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="font-bold text-sm uppercase">
                                    {{ $locality->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $locality->postal_code }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class=" text-sm uppercase">
                                    {{ $locality->province->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class=" text-sm uppercase">
                                    {{ $locality->province->country->name }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    @livewire('localities.edit-locality', ['locality' => $locality], key($locality->id))
                                    <x-jet-danger-button wire:click="$emit('deleteLocality', '{{ $locality->id }}')">
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

        @if ($localities->hasPages())
            <div class="px-6 py-3">
                {{ $localities->links() }}
            </div>
        @endif

    </x-responsive-table>

    @push('script')
        <script>
            Livewire.on('deleteLocality', localityId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Sí, eliminar localidad',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.isConfirmed) {

                            Livewire.emitTo('localities.index-localities', 'delete', localityId);

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
                    }
                })
            });
        </script>
    @endpush

</div>
