<div>
    <div class="container py-6">

        <x-slot name="header">
            <div class="flex items-center justify-between">

                {{-- GO BACK BUTTON --}}
                <a href="{{ route('admin.tenderings.index') }}">
                    <x-jet-button>
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </x-jet-button>
                </a>

                {{-- PDF BUTTON --}}
                <a href="#">
                    <x-jet-danger-button>
                        <i class="fas fa-file-pdf mr-2"></i>
                        Descargar PDF
                    </x-jet-danger-button>
                </a>
            </div>
        </x-slot>

        @if (!$tender->is_active)
            <div class="flex p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                <div class="font-semibold flex items-center gap-2">
                    <i class="fas fa-ban text-2xl mr-2"></i>
                    <span class="font-bold uppercase">¡CONCURSO ANULADO!</span>
                    Este concurso fue anulado por {{ $user_who_cancelled }} el día
                    {{ Date::parse($tender->cancelled_at)->format('d-m-Y') }}.
                    <span class="font-bold">Motivo:</span>
                    {{ $tender->cancel_reason }}
                </div>
            </div>
        @endif

        @if ($tender->is_finished && !$tender->is_cancelled)
            <div class="flex flex-col md:flex-row md:justify-between p-4 mb-4 bg-slate-200 rounded-lg text-gray-600">
                <div class="font-semibold flex flex-col md:flex-row items-center gap-2">
                    <i class="fas fa-check text-2xl mr-2"></i>
                    <span class="font-bold uppercase">CONCURSO FINALIZADO!</span>
                    Este concurso fue finalizado por
                    {{ $user_who_finished }}
                    el día
                    {{ Date::parse($tender->finished_at)->format('d-m-Y H:i') }}hs. Puedes ver el detalle haciendo click en el siguiente botón:</span>
                </div>
                <a href="{{ route('admin.tenderings.show-finished-tendering', $tender) }}"
                    class="font-bold uppercase">
                    <x-jet-button>
                        Ver resultados
                    </x-jet-button>
                </a>
            </div>
        @endif

        {{-- Purchase detail --}}
        <div class="bg-gray-50 rounded-t-lg">

            <div class="flex px-6 py-3 items-center justify-between">
                <div>
                    <span class="font-bold text-gray-600 text-lg">
                        <i class="fas fa-info-circle mr-1"></i>
                        Detalle licitación N° {{ $tender->id }}
                    </span>
                </div>

                <div>
                    <span
                        class="px-6 py-1 inline-flex text-xs uppercase leading-5 font-semibold rounded-full {{ $stats['active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $stats['active'] ? 'Concurso válido' : 'Concurso inválido' }}
                    </span>
                    <span
                        class="px-6 py-1 inline-flex text-xs uppercase leading-5 font-semibold rounded-full {{ $stats['finished'] ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $stats['finished'] && !$stats['active'] ? 'Finalizado' : 'En ejecución' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-6">
                <div class="space-y-1">
                    <p class="font-bold">
                        Fecha inicio licitación:
                        <span class="font-normal">{{ $stats['start_date'] }}</span>
                    </p>
                    <p class="font-bold">
                        Fin fin licitación:
                        <span class="font-normal">{{ $stats['end_date'] }}</span>
                    </p>
                    <p class="font-bold">
                        Tiempo restante:
                        <span class="font-normal">{{ $stats['remaining_time'] }}</span>
                    </p>
                    <p class="font-semibold">
                        {{ $stats['suppliers_stats'] }}
                    </p>
                </div>

                <div class="space-y-1">
                    <p class="font-bold">
                        Productos y cantidades en licitación:
                    </p>
                    <ul class="list-disc text-gray-600 ml-10">
                        @foreach ($products as $item)
                            <li>
                                {{ $item['quantity'] }} unidades de <span
                                    class="font-semibold">{{ $item['name'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="font-bold">
                        Cantidad total de productos:
                        <span class="font-normal">{{ $stats['total_products'] }}</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- DETALLE DE HASHES --}}
        <div class="bg-gray-50 rounded-b-lg">

            <div class="px-6 py-4">
                <p class="font-bold text-gray-600 text-sm uppercase text-center">
                    Detalle de solicitudes enviadas
                </p>
            </div>

            @if ($hashes->count())
                <x-responsive-table>
                    <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                            <tr>
                                <th scope="col" class="px-4 py-3">
                                    ID
                                </th>
                                <th scope="col" class="w-1/3 px-4 py-3">
                                    Proveedor
                                </th>
                                <th scope="col" class="px-4 py-3">
                                    Visto
                                </th>
                                <th scope="col" class="w-1/4 px-4 py-3">
                                    Fecha visto
                                </th>
                                <th scope="col" class="px-4 py-3">
                                    Respuesta
                                </th>
                                <th scope="col" class="w-1/4 px-4 py-3">
                                    Fecha respuesta
                                </th>
                                <th scope="col" class="px-4 py-3">
                                    Detalle
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($hashes as $hash)
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-2">
                                        <p class="text-sm uppercase">
                                            {{ $hash->id }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <p class="text-sm uppercase {{ $hash->cancelled ? 'text-red-500 font-bold' : '' }}">
                                            {{ $hash->supplier->business_name }}
                                            {{ $hash->cancelled ? '(Oferta anulada)' : '' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <i class="fas fa-{{ !$hash->seen ? 'times' : 'check' }} text-{{ !$hash->seen ? 'red' : 'green' }}-600"></i>
                                    </td>
                                    <td class="px-6 py-2">
                                        <p
                                            class="text-sm uppercase text-center">
                                            {{ $hash->seen_at ? Date::parse($hash->seen_at)->format('d-m-Y H:i') : 'N/A' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <i class="fas fa-{{ !$hash->answered ? 'times' : 'check' }} text-{{ !$hash->answered ? 'red' : 'green' }}-600"></i>
                                    </td>
                                    <td class="px-6 py-2">
                                        <p
                                            class="text-sm uppercase text-center">
                                            {{ $hash->answered_at ? Date::parse($hash->answered_at)->format('d-m-Y H:i') : 'N/A' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm font-medium">
                                        @if ($hash->offer)
                                            <div class="flex items-center justify-end gap-2">
                                                <a title="Ver detalle"
                                                    href="{{ route('admin.tenderings.show-offer-detail', ['tendering' => $tender, $hash]) }}">
                                                    <x-jet-secondary-button>
                                                        <i class="fas fa-list"></i>
                                                    </x-jet-secondary-button>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-responsive-table>
            @endif
        </>
    </div>

    @if ($tender->is_active && !$tender->is_finished)
        <div class="flex justify-center mt-5">
            <x-jet-button wire:click="$emit('finishTender', '{{ $tender->id }}')">
                <i class="fas fa-flag mr-2"></i>
                Finalizar concurso
            </x-jet-button>
        </div>
    @endif

</div>

@push('script')
    <script>
        Livewire.on('finishTender', tenderId => {
            console.log('nememes');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Sí, finalizar concurso',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('tenderings.show-tendering', 'finishTendering', tenderId);
                }
            })
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
