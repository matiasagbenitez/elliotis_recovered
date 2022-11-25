<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Tareas de producción</h1>
            <a href="{{ route('admin.tasks.create') }}">
                <x-jet-secondary-button>
                    Registrar nueva tarea
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow mb-5">
        <h1 class="font-bold uppercase text-lg text-center">Tareas de producción</h1>
        <hr class="my-2">
        <div class="grid grid-cols-3 gap-4">

            <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                <div class="flex justify-center">
                    <img class="h-24 w-24" src="{{ asset('img/rollo.png') }}" alt="Imagen de rollos">
                </div>
                <h2 class="uppercase font-bold text-center my-3">Corte de rollos</h2>
            </div>


            <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                <div class="flex justify-center">
                    <img class="h-24 w-24" src="{{ asset('img/machimbres.png') }}" alt="Imagen de machimbres">
                </div>
                <h2 class="uppercase font-bold text-center my-3">Machimbrado</h2>
            </div>

            <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                <div class="flex justify-center">
                    <img class="h-24 w-24" src="{{ asset('img/paquetes.png') }}" alt="Imagen de paquetes">
                </div>
                <h2 class="uppercase font-bold text-center my-3">Empaquetado</h2>
            </div>
        </div>
    </div>

    {{-- <div class="p-6 bg-white rounded-lg shadow mb-5">
        <h1 class="font-bold uppercase text-lg text-center">Movimientos</h1>
        <hr class="my-2">
        <div class="grid grid-cols-4 gap-4">

            <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                <div class="flex justify-center">
                    <img class="h-24 w-24" src="{{ asset('img/sampi.png') }}" alt="Imagen de sampi">
                </div>
                <h2 class="uppercase font-bold text-center my-3">Movimiento para secado al aire libre</h2>
            </div>

            <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                <div class="flex justify-center">
                    <img class="h-24 w-24" src="{{ asset('img/sampi.png') }}" alt="Imagen de sampi">
                </div>
                <h2 class="uppercase font-bold text-center my-3">Movimiento a depósito secos</h2>
            </div>

            <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                <div class="flex justify-center">
                    <img class="h-24 w-24" src="{{ asset('img/sampi.png') }}" alt="Imagen de sampi">
                </div>
                <h2 class="uppercase font-bold text-center my-3">Movimiento a depósito machimbrados</h2>
            </div>

            <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                <div class="flex justify-center">
                    <img class="h-24 w-24" src="{{ asset('img/sampi.png') }}" alt="Imagen de sampi">
                </div>
                <h2 class="uppercase font-bold text-center my-3">Movimiento a depósito paquetes listos</h2>
            </div>
        </div>
    </div> --}}

    {{-- <div class="p-6 bg-white rounded-lg shadow mb-5">
        <h1 class="font-bold uppercase text-lg text-center">Áreas</h1>
        <hr class="my-2">
        <div class="grid grid-cols-5 gap-4">
        <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
            <div class="flex justify-center">
                <img class="h-24 w-24" src="{{ asset('img/lineacorte.png')}}" alt="Imagen de sampi">
            </div>
            <h2 class="uppercase font-bold text-center my-3">Línea de corte</h2>
        </div>

        <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
            <div class="flex justify-center">
                <img class="h-24 w-24" src="{{ asset('img/secado.png')}}" alt="Imagen de sampi">
            </div>
            <h2 class="uppercase font-bold text-center my-3">Área de secado</h2>
        </div>

        <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
            <div class="flex justify-center">
                <img class="h-24 w-24" src="{{ asset('img/secos.png')}}" alt="Imagen de sampi">
            </div>
            <h2 class="uppercase font-bold text-center my-3">Depósito secos</h2>
        </div>

        <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
            <div class="flex justify-center">
                <img class="h-24 w-24" src="{{ asset('img/machimbrados.png')}}" alt="Imagen de sampi">
            </div>
            <h2 class="uppercase font-bold text-center my-3">Depósito machimbrados</h2>
        </div>

        <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
            <div class="flex justify-center">
                <img class="h-24 w-24" src="{{ asset('img/depositopaquetes.png')}}" alt="Imagen de sampi">
            </div>
            <h2 class="uppercase font-bold text-center my-3">Depósito paquetes listos</h2>
        </div>

    </div> --}}

</div>
