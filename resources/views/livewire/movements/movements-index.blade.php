<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Movimientos internos</h1>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow mb-5">
        <h1 class="font-bold uppercase text-lg text-center">Movimientos internos</h1>
        <hr class="my-2">
        <div class="grid grid-cols-2 gap-4">

            @foreach ($stats as $stat)
                <a href="{{ route('admin.movements.manage', $stat['id']) }}">
                {{-- <a href=""> --}}
                    <div class="hover:bg-gray-50 hover:shadow-lg rounded-lg p-2 hover:cursor-pointer">
                        <div class="flex justify-center">
                            <img class="h-40 w-40" src="{{ asset($stat['icon']) ? asset($stat['icon']) : asset('img/default.png') }}" alt="Ícono de la tarea">
                        </div>

                        <h2 class="uppercase font-bold text-center my-3">{{ $stat['name'] }}</h2>

                        @if ($stat['running_movement'] == true)
                            <div class="space-y-1 text-center p-3 font-semibold">
                                <span
                                    class="px-6 py-1 mb-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    EN MOVIMIENTO
                                </span>
                                @if (isset($stat['running_movement']))
                                    <p class="text-sm">Último movimiento iniciado por</p>
                                    <p class="text-sm">{{ $stat['user'] }}</p>
                                    <p class="text-sm">el {{ $stat['date'] }}hs</p>
                                @endif
                            </div>
                        @elseif ($stat['running_movement'] == false)
                            <div class="space-y-1 text-center p-3 font-semibold ">
                                <span
                                    class="px-6 py-1 mb-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    SIN MOVIMIENTO
                                </span>
                                @if (isset($stat['running_movement']))
                                    <p class="text-sm">Último movimiento finalizado por</p>
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
