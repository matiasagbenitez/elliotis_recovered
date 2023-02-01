<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Licitaciones</h2>
            <a href="{{ route('admin.tenderings.create') }}">
                <x-jet-secondary-button>
                    Registrar nueva compra
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <div class="flex flex-col rounded-lg bg-white">

        {{-- FILTROS --}}
        <div class="grid grid-cols-6 gap-3 bg-gray-200 px-5 py-5 rounded-t-lg">
            <div class="col-span-6">
                <span class="font-bold text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Listado de licitaciones
                </span>
            </div>

            <div class="col-span-3">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Filtros" />
                    <select wire:model="query" class="input-control">
                        <option value="">Todos los concursos</option>

                    </select>
                </div>
            </div>

            <div class="col-span-3">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Orden" />
                    <select wire:model="direction" class="input-control"
                        @if ($query == 3 || $query == 4) disabled @endif>
                        <option value="asc">Ascendente</option>
                        <option value="desc">Descendente</option>
                    </select>
                </div>
            </div>
        </div>


        <x-responsive-table>

            {{-- TABLA --}}
            @if ($tenderings->count())
                <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-50">
                        <tr class="px-4 py-2">
                            <th>
                                ID
                            </th>
                            <th scope="col" class="w-1/5 py-3">
                                Fecha inicio
                            </th>
                            <th scope="col" class="w-1/5 py-3">
                                Fecha final
                            </th>
                            <th scope="col" class="w-1/5 py-3">
                                Total en licitación
                            </th>
                            <th scope="col" class="w-1/5 py-3">
                                Estado
                            </th>
                            <th scope="col" class="w-1/5 py-3">
                                Situación
                            </th>
                            <th scope="col">
                                Acción
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($tenderings as $tender)
                            <tr class="bg-gray-50">
                                <td class="px-6 py-3">
                                    <p class="text-sm uppercase text-center">
                                        {{ $tender->id }}
                                    </p>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-center">
                                    <p class="text-sm uppercase">
                                        {{ Date::parse($tender->start_date)->format('d-m-Y H:i') }}
                                    </p>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-sm uppercase text-center">
                                        {{ Date::parse($tender->end_date)->format('d-m-Y H:i') }}
                                    </p>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-sm text-center">
                                        {{ $tender->products->sum('pivot.quantity') }} unidades
                                    </p>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <p
                                        class="text-center px-6 py-1 inline-flex text-xs uppercase leading-5 font-semibold rounded-full {{ $tender->is_active ? ' bg-green-100 text-green-800' : ' bg-red-100 text-red-800' }}">
                                        {{ $tender->is_active ? 'Válido' : 'Anulado' }}
                                    </p>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <p
                                        class="text-center px-6 py-1 inline-flex text-xs uppercase leading-5 font-semibold rounded-full {{ $tender->is_active ? ' bg-yellow-100 text-yellow-800' : ' bg-gray-100 text-gray-800' }}">
                                        {{ $tender->is_active ? 'En ejecución' : 'Finalizado' }}
                                    </p>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($tender->is_active)
                                            <button title="Anular venta"
                                                wire:click="$emit('disableSaleOrder', '{{ $tender->id }}')">
                                                <i class="fas fa-ban mr-1"></i>
                                            </button>
                                        @endif
                                        <a title="Ver detalle" href="{{ route('admin.tenderings.show-detail', $tender) }}">
                                            <x-jet-secondary-button>
                                                <i class="fas fa-list"></i>
                                            </x-jet-secondary-button>
                                        </a>
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

            @if ($tenderings->hasPages())
                <div class="px-6 pt-2 pb-3 bg-gray-50">
                    {{ $tenderings->links() }}
                </div>
            @endif

        </x-responsive-table>
    </div>

</div>

@push('script')
    <script>
        Livewire.on('disableTender', async (tenderId) => {

            const {
                value: reason
            } = await Swal.fire({
                title: 'Anular concurso',
                input: 'textarea',
                inputPlaceholder: 'Especifique aquí el o los motivos de anulación',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
            });

            if (reason) {
                Swal.fire({
                    title: '¿Anular concurso?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f2937',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Sí, anular concurso',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('tenderings.index-tenderings', 'disable', tenderId, reason);
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
            }
        });
    </script>
@endpush
