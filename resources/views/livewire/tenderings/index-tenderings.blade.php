<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Concursos privados de precios</h2>
            <a href="{{ route('admin.tenderings.create') }}">
                <x-jet-secondary-button>
                    Registrar nuevo concurso
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <div class="flex flex-col gap-4 rounded-lg bg-transparent">

        {{-- Search --}}
        <div class="grid grid-cols-6 gap-3">
            <div class="col-span-3">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Filtros" />
                    <select wire:model="query" class="input-control">
                        <option value="">Todos los concursos</option>
                        <option value="1">Concursos válidos</option>
                        <option value="2">Concursos anulados</option>
                        <option value="3">Concursos más prontos a vencer</option>
                        <option value="4">Concursos más tardes a vencer</option>
                        <option value="5">Concursos analizados</option>
                        <option value="6">Concursos aprobados</option>
                    </select>
                </div>
            </div>
            <div class="col-span-3">
                <div class="flex flex-col gap-2">
                    <x-jet-label value="Orden" />
                    <select wire:model="direction" class="input-control" @if ($query == 3 || $query == 4) disabled @endif>
                        <option value="asc">Ascendente</option>
                        <option value="desc">Descendente</option>
                    </select>
                </div>
            </div>
        </div>

        @if ($tenderings->count())
            @foreach ($tenderings as $tender)
                <div class="px-6 py-3 bg-white rounded-lg shadow">
                    <div class="flex justify-between">
                        <span class="font-bold">
                            Concurso #{{ $tender->id }}
                        </span>
                        <p class="text-sm uppercase">
                            @if ($tender->is_active == 1)
                                <span
                                    class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    VÁLIDO
                                </span>
                            @else
                                <span
                                    class="px-6 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    ANULADO
                                </span>
                            @endif
                        </p>
                    </div>
                    <hr class="mt-1">
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
                    <p class="text-sm my-1 font-bold">Subtotal estimado:
                        <span class="font-normal">${{ number_format($tender->subtotal, 2, ',', '.') }}</span>
                    </p>
                    <div class="flex justify-between">
                        <p class="text-sm my-1 font-bold">Concurso analizado:
                            <span class="font-normal">{{ $tender->is_analyzed == 1 ? 'Sí' : 'No' }}</span>
                        </p>
                        @if ($tender->is_active)
                            @if ($tender->end_date > now())
                                <button wire:click="$emit('disableTender', '{{ $tender->id }}')">
                                    <span class="text-sm hover:font-bold cursor-pointer">
                                        <i class="fas fa-ban mr-1 text-gray-800"></i>
                                        Anular
                                    </span>
                                </button>
                            @endif
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm my-1 font-bold">Oferta aceptada:
                            <span class="font-normal">{{ $tender->is_approved == 1 ? 'Sí' : 'No' }}</span>
                        </p>
                        <a href="{{ route('admin.tenderings.show-detail', $tender->id) }}">
                            <span class="text-sm hover:font-bold cursor-pointer">
                                <i class="fas fa-info-circle mr-1 text-gray-800"></i>
                                Ver detalle
                            </span>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

        @if ($tenderings->count())
            {{ $tenderings->links() }}
        @endif
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
