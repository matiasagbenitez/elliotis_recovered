<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Tipos de tareas</h1>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow mb-5">
        <div class="px-6 py-3">
            <h1 class="font-bold uppercase text-lg text-center mb-3 mt-0">Tipos de tareas</h1>
            <select wire:model='filter' class="input-control w-full">
                <option value="all">Todos los tipos de tareas</option>
                <option value="production">Tareas de producción</option>
                <option value="movement">Tareas de movimiento</option>
            </select>
        </div>

        <div class="gap-4">

            @foreach ($stats as $stat)
                <a href="{{ route('admin.tasks.manage', $stat['id']) }}">

                    <div
                        class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer h-full flex items-center">
                        <div class="w-2/3 flex items-center gap-10 font-mono">
                            <div class="hidden md:flex md:justify-center">
                                <img class="h-24 w-24" src="{{ asset('storage/img' . $stat['icon']) }}" alt="Ícono de la tarea">
                            </div>

                            <div class="flex flex-col items-start">
                                <h2 class="uppercase font-bold text-center">{{ $stat['name'] }}</h2>
                                <p class="font-normal font-sans text-sm">{{ $stat['description'] }}</p>
                            </div>
                        </div>

                        <div class="w-1/3">
                            @if ($stat['running_task'] == true)
                                <div class="space-y-1 text-center p-3 font-semibold">
                                    <span
                                        class="px-6 py-1 mb-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        LÍNEA EN FUNCIONAMIENTO
                                    </span>
                                    @if (isset($stat['running_task']))
                                        <p class="text-sm">Iniciada por {{ $stat['user'] }}</p>
                                        <p class="text-sm">{{ $stat['date'] }}</p>
                                    @endif
                                </div>
                            @elseif ($stat['running_task'] == false)
                                <div class="space-y-1 text-center p-3 font-semibold ">
                                    <span
                                        class="px-6 py-1 mb-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        LÍNEA DETENIDA
                                    </span>
                                    @if (isset($stat['running_task']))
                                        <p class="text-sm">Detenida por {{ $stat['user'] }}</p>
                                        <p class="text-sm">{{ $stat['date'] }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="w-1/4 flex items-center justify-end">
                            @if ($stat['pendingProducts'] == true)
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"
                                    title="Producción necesaria"></i>
                            @endif
                            <x-jet-secondary-button>
                                Gestionar
                            </x-jet-secondary-button>
                        </div>
                    </div>

                </a>
            @endforeach

        </div>
    </div>
</div>

@push('script')
    <script>
        Livewire.on('success', message => {
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
