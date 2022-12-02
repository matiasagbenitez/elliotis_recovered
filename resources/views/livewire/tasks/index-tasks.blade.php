<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Tareas de producción</h1>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow mb-5">
        <h1 class="font-bold uppercase text-lg text-center">Tareas de producción</h1>
        <hr class="my-2">
        <div class="grid grid-cols-3 gap-4">

            @foreach ($tasksTypes as $taskType)
                <a href="{{ route('admin.tasks.manage', $taskType) }}">
                    <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                        <div class="flex justify-center">
                            <img class="h-40 w-40" src="{{ asset($taskType->icon) }}" alt="Ícono de la tarea">
                        </div>
                        <h2 class="uppercase font-bold text-center my-3">{{ $taskType->name }}</h2>
                    </div>
                </a>
            @endforeach

        </div>
    </div>

</div>
