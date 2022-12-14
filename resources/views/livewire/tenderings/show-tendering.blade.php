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
            <div
                class="flex items-center p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800 border-2 border-red-600">
                <i class="fas fa-info-circle text-lg mr-2"></i>
                <div>
                    <span class="font-bold uppercase">Atención!</span>
                    Este concurso fue anulado por {{ $user_who_cancelled }} el día
                    {{ Date::parse($tender->cancelled_at)->format('d-m-Y') }}.
                    <span class="font-bold">Motivo:</span>
                    {{ $tender->cancel_reason }}
                </div>
            </div>
        @endif

        @if ($tender->is_finished)
            <div
                class="flex items-center p-4 mb-4 text-sm text-cyan-700 bg-cyan-100 rounded-lg dark:bg-cyan-200 dark:text-cyan-800 border-2 border-cyan-600">
                <i class="fas fa-info-circle text-lg mr-2"></i>
                <div>
                    <span class="font-bold uppercase">CONCURSO FINALIZADO!</span>
                    Este concurso fue finalizado por
                    {{ $user_who_finished }}
                    el día
                    {{ Date::parse($tender->finished_at)->format('d-m-Y H:i') }}hs.

                    Puedes ver más información haciendo
                    <a href="{{ route('admin.tenderings.show-finished-tendering', $tender) }}"
                        class="font-bold underline">click aquí</a>.

                </div>
            </div>
        @endif

        {{-- Purchase detail --}}
        <div class="px-6 py-3 bg-white rounded-lg shadow">
            <span class="font-bold">Concurso #{{ $tender->id }}</span>
            <hr class="mt-1">
            {{-- <div @if (!$tender->is_active) class="grid grid-cols-2" @endif> --}}
            <div class="grid">

                {{-- DIV IZQUIERDA --}}
                <div>
                    <p class="text-sm font-bold my-1">Inicio concurso:
                        {{-- <span class="font-normal"> {{ Date::parse($tender->start_date)->format('d-m-Y h:m:s') }} hs</span> --}}
                        <span class="font-normal"> {{ Date::parse($tender->start_date)->format('d-m-Y H:i') }} hs</span>
                    </p>
                    <div class="flex justify-between">
                        <p class="text-sm font-bold">Fin concurso:
                            <span class="font-normal">{{ Date::parse($tender->end_date)->format('d-m-Y H:i') }}
                                hs</span>
                        </p>
                        {{-- Si le fecha de fin aún no llegó o si no está inactivo, mostrar el tiempo restante --}}
                        @if ($tender->end_date > now() && $tender->is_active == 1)
                            <p class="text-sm font-bold">Tiempo restante:
                                <span class="font-normal">{{ Date::parse($tender->end_date)->diffForHumans() }}</span>
                            </p>
                        @endif
                    </div>
                    <p class="text-sm my-1 font-bold"> Ítems solicitados:</p>
                    <ul class="list-disc list-inside ml-4">
                        {{-- Get price, quantity and subtotal for each product in pivot table --}}
                        @foreach ($tender->products as $product)
                            <li class="text-xs">{{ $product->name }} (x{{ $product->pivot->quantity }})</li>
                        @endforeach
                    </ul>
                    <div class="flex justify-between">
                        <p class="text-sm my-1 font-bold">Subtotal estimado:
                            <span class="font-normal">${{ number_format($tender->subtotal, 2, ',', '.') }}</span>
                        </p>
                        <p class="text-sm my-1 font-bold">IVA estimado:
                            <span class="font-normal">${{ number_format($tender->iva, 2, ',', '.') }}</span>
                        </p>
                        <p class="text-sm my-1 font-bold">Total estimado:
                            <span class="font-normal">${{ number_format($tender->total, 2, ',', '.') }}</span>
                        </p>
                    </div>
                    <p class="text-sm my-1 font-bold">Concurso analizado:
                        <span class="font-normal">{{ $tender->is_analyzed == 1 ? 'Sí' : 'No' }}</span>
                    </p>
                    <p class="text-sm my-1 font-bold">Oferta aceptada:
                        <span class="font-normal">{{ $tender->is_approved == 1 ? 'Sí' : 'No' }}</span>
                    </p>
                </div>

            </div>
        </div>

        {{-- DETALLE DE HASHES --}}
        <div class="px-6 pt-3 pb-6 bg-white rounded-lg shadow mt-4">
            <p class="font-bold my-1">Solicitudes enviadas</p>
            <hr class="mt-1 mb-2">
            @if ($hashes->count())
                <x-responsive-table>
                    <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="text-sm text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                            <tr>
                                <th scope="col" class="px-4 py-2">
                                    ID
                                </th>
                                <th scope="col" class="w-1/3 px-4 py-2">
                                    Proveedor
                                </th>
                                <th scope="col" class="px-4 py-2">
                                    Visto
                                </th>
                                <th scope="col" class="w-1/4 px-4 py-2">
                                    Fecha visto
                                </th>
                                <th scope="col" class="px-4 py-2">
                                    Respuesta
                                </th>
                                <th scope="col" class="w-1/4 px-4 py-2">
                                    Fecha respuesta
                                </th>
                                <th scope="col" class="px-4 py-2">
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
                                        <p class="text-sm uppercase {{ $hash->cancelled ? 'line-through' : '' }}">
                                            {{ $hash->supplier->business_name }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        @switch($hash->seen)
                                            @case(1)
                                                <span
                                                    class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Sí
                                                </span>
                                            @break

                                            @case(0)
                                                <span
                                                    class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    No
                                                </span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-2">
                                        <p
                                            class="text-sm uppercase text-center {{ $hash->cancelled ? 'line-through' : '' }}">
                                            {{ $hash->seen_at ? Date::parse($hash->seen_at)->format('d-m-Y H:i') : 'N/A' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        @switch($hash->answered)
                                            @case(1)
                                                <span
                                                    class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Sí
                                                </span>
                                            @break

                                            @case(0)
                                                <span
                                                    class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    No
                                                </span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-2">
                                        <p
                                            class="text-sm uppercase text-center {{ $hash->cancelled ? 'line-through' : '' }}">
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
        </div>
    </div>

    @if ($tender->is_active)
        @if (!$tender->is_finished)
            <div class="bg-white p-3">
                <div class="flex justify-center">
                    <x-jet-button wire:click="$emit('finishTender', '{{ $tender->id }}')">
                        <i class="fas fa-flag mr-2"></i>
                        Finalizar concurso
                    </x-jet-button>
                </div>
            </div>
        @endif
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
    </script>
@endpush
