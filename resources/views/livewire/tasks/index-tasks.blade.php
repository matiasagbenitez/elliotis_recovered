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

        <div class="grid grid-cols-5 gap-4">

            @foreach ($stats as $stat)
                <a href="{{ route('admin.tasks.manage', $stat['id']) }}">
                {{-- <a href="#"> --}}
                    <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer h-full flex flex-col justify-between">
                        <div class="flex justify-center">
                            <img class="h-40 w-40" src="{{ asset($stat['icon']) }}" alt="Ícono de la tarea">
                        </div>

                        <h2 class="uppercase font-bold text-center my-3">{{ $stat['name'] }}</h2>

                        @if ($stat['running_task'] == true)
                            <div class="space-y-1 text-center p-3 font-semibold">
                                <span
                                    class="px-6 py-1 mb-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    LÍNEA EN FUNCIONAMIENTO
                                </span>
                                @if (isset($stat['running_task']))
                                    <p class="text-sm">Última tarea iniciada por</p>
                                    <p class="text-sm">{{ $stat['user'] }}</p>
                                    <p class="text-sm">el {{ $stat['date'] }}hs</p>
                                @endif
                            </div>
                        @elseif ($stat['running_task'] == false)
                            <div class="space-y-1 text-center p-3 font-semibold ">
                                <span
                                    class="px-6 py-1 mb-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    LÍNEA DETENIDA
                                </span>
                                @if (isset($stat['running_task']))
                                    <p class="text-sm">Última tarea finalizada por</p>
                                    <p class="text-sm">{{ $stat['user'] }}</p>
                                    <p class="text-sm">el {{ $stat['date'] }}hs</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach

        </div>
    </div>

</div>
