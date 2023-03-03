<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">Historial de sublote</h2>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg px-10 py-5">

        <p class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Historial de tareas aplicadas a un sublote
        </p>
        <hr class="mt-2 mb-4">

        <div>
            <x-jet-label for="sublot" value="Código del sublote" />
            <div class="flex gap-3">
                <div class="w-3/4">
                    <x-jet-input id="sublot" type="text" class="mt-1 block w-full"
                        wire:model.defer="selectedSublot" />
                </div>
                <div class="w-2/5 flex gap-2">
                    <x-jet-button wire:click="showSublot" class="w-full">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </x-jet-button>
                    <x-jet-secondary-button wire:click="resetSublot" class="w-full">
                        <i class="fas fa-eraser mr-2"></i>
                        Reiniciar
                        </x-jet-seconda-button>
                        <x-jet-danger-button wire:click="$emit('pdf')" class="w-full">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Generar PDF
                        </x-jet-danger-button>
                </div>
            </div>
        </div>

        @if ($sublotStats)
            <div class="mt-5">
                <h1 class="uppercase font-bold text-xl">Información del sublote</h1>
                <hr class="my-1">
                <div class="flex">
                    <div class="w-4/5 flex gap-10">
                        <div>
                            <p class="font-bold">ID sublote: <span class="font-normal">{{ $sublotStats['id'] }}</span>
                            </p>
                            <p class="font-bold">Código sublote: <span
                                    class="font-normal">{{ $sublotStats['code'] }}</span></p>
                            <p class="font-bold">Código lote: <span class="font-normal">{{ $sublotStats['lot'] }}</span>
                            </p>
                            <p class="font-bold">Tipo de producto: <span
                                    class="font-normal">{{ $sublotStats['product'] }}</span></p>
                            <p class="font-bold">Ubicación actual: <span
                                    class="font-normal">{{ $sublotStats['area'] }}</span></p>
                        </div>
                        <div>
                            <p class="font-bold">Tarea generadora: <span
                                    class="font-normal">{{ $sublotStats['task'] }}</span></p>
                            <p class="font-bold">Inicio tarea: <span
                                    class="font-normal">{{ $sublotStats['started_at'] }}
                                    ({{ $sublotStats['started_by'] }})</span></p>
                            <p class="font-bold">Fin tarea: <span class="font-normal">{{ $sublotStats['finished_at'] }}
                                    ({{ $sublotStats['finished_by'] }})</span></p>
                            <p class="font-bold">Stock original: <span
                                    class="font-normal">{{ $sublotStats['initial_quantity'] }}
                                    {{ $sublotStats['initial_m2'] }}</span></p>
                            <p class="font-bold">Stock actual: <span
                                    class="font-normal">{{ $sublotStats['actual_quantity'] }}
                                    {{ $sublotStats['actual_m2'] }}</span></p>
                        </div>
                    </div>
                    <div class="w-1/5 flex items-top justify-end">
                        <img class="h-60 w-60" src="{{ asset('storage/img/example_qrcode.png') }}" alt="QR de ejemplo">
                    </div>
                </div>
            </div>
        @endif

        @if ($historic)

            <div class="mb-5">
                <h1 class="uppercase font-bold text-xl">Histórico de tareas aplicadas</h1>
                <hr class="my-1">
            </div>

            <div class="columns-2 space-y-8">
                @foreach (array_reverse($historic) as $task)
                    <div class="break-inside-avoid">
                        <h2 class="uppercase font-bold">
                            {{ $task['started_at'] }}: {{ $task['name'] }}
                            <a href="{{ route('admin.tasks.show', $task['task_id']) }}" target="_blank"
                                class="text-blue-700">
                                (#{{ $task['task_id'] }})
                            </a>
                        </h2>
                        <div>
                            <p class="font-bold">Inicio tarea: <span class="font-normal">{{ $task['started_at'] }}
                                    ({{ $task['started_by'] }})</span></p>
                            <p class="font-bold">Fin tarea: <span class="font-normal">{{ $task['finished_at'] }}
                                    ({{ $task['finished_by'] }})</span></p>
                        </div>
                        @foreach ($task as $key => $subtask)
                            @if (is_array($subtask) && $key !== 'sublots')
                                <h3 class="ml-10 font-semibold">Tarea anterior: {{ $subtask['name'] }}
                                    <a href="{{ route('admin.tasks.show', $subtask['task_id']) }}" target="_blank"
                                    class="text-blue-700">
                                        (#{{ $subtask['task_id'] }})
                                    </a>
                                </h3>
                                <span class="underline ml-10">Sublotes de entrada</span>
                                <ul class="ml-20 list-disc">
                                    @foreach ($subtask['sublots'] as $sublot)
                                        <li>{{ $sublot['code'] }} - {{ $sublot['product'] }}
                                            @if ($sublot['purchase'])
                                                <a class="text-blue-700" target="_blank" href="{{ route('admin.purchases.show-detail', $sublot['m2']) }}">(Ver compra)</a>
                                            @else
                                                {{ $sublot['m2'] }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            <p class="font-semibold my-5">Debe ingresar el código de un sublote para obtener su histórico.</p>
        @endif

    </div>
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
