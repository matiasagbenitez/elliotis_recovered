<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">OpenWeather API</h1>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow mb-5">
        <div class="px-8 py-6">

            <div class="flex gap-10">
                <div class="w-1/2 space-y-1">
                    <span class="font-bold text-gray-700">
                        <i class="fas fa-cloud mr-1"></i>
                        Información general
                    </span>
                    <hr class="my-3">

                    <p class="font-bold">
                        Respuesta servidor:
                        <span class="font-normal">
                            {{ $general_stats['code'] }}
                        </span>
                    </p>
                    <p class="font-bold">
                        Ciudad:
                        <span class="font-normal">
                            {{ $general_stats['city'] }} ({{ $general_stats['country'] }})
                        </span>
                    </p>
                    <p class="font-bold">
                        Amanece:
                        <span class="font-normal">
                            {{ $general_stats['sunrise'] }} AM
                        </span>
                    </p>
                    <p class="font-bold">
                        Oscurece:
                        <span class="font-normal">
                            {{ $general_stats['sunset'] }} PM
                        </span>
                    </p>
                    <p class="font-bold">
                        Pronóstico:
                        <span class="font-normal">
                            Próximos 5 días, intervalo de 3 horas
                        </span>
                    </p>
                    <p class="font-bold">
                        Total de registros:
                        <span class="font-normal">
                            {{ $general_stats['count'] }} registros
                        </span>
                    </p>
                </div>

                <div class="w-1/2 space-y-1">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-700">
                            <i class="fas fa-signal mr-1"></i>
                            Alarma por inclemencias
                        </span>
                        <div class="flex gap-2">
                            @livewire('weather-api.edit-conditions', ['id' => $conditions['id']], key($conditions['id']))
                            <button wire:click="testAlert" title="Probar alarma">
                                <i class="fas fa-bell"></i>
                            </button>
                        </div>
                    </div>
                    <hr class="my-3">

                    <p class="font-bold">
                        Temperatura mínima promedio:
                        <span class="font-normal">
                            {{ $conditions['temp'] }}° C
                        </span>
                    </p>
                    <p class="font-bold">
                        Probabilidad de lluvia promedio:
                        <span class="font-normal">
                            {{ $conditions['rain_prob'] }}% ({{ $conditions['rain_prob_x100'] }}%)
                        </span>
                    </p>
                    <p class="font-bold">
                        Milímetros promedio:
                        <span class="font-normal">
                            {{ $conditions['rain_mm'] }}mm
                        </span>
                    </p>
                    <p class="font-bold">
                        Humedad máxima promedio:
                        <span class="font-normal">
                            {{ $conditions['humidity'] }}% ({{ $conditions['humidity_x100'] }}%)
                        </span>
                    </p>
                    <p class="font-bold">
                        Velocidad del viento:
                        <span class="font-normal">
                            {{ $conditions['wind_speed'] }} km/h
                        </span>
                    </p>
                    <div class="flex justify-between">
                        <p class="font-bold">
                            Condiciones mínimas:
                            <span class="font-normal">
                                {{ $conditions['max_conditions'] }}/5
                            </span>
                        </p>
                        <p class="font-bold">
                            Días en consideración:
                            <span class="font-normal">
                                {{ $conditions['days_in_row'] }} días
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <x-responsive-table>

            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th class="px-4 py-2 w-1/10">
                            Fecha
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Temperatura (T°)
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Sensación
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Mínima
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Máxima
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Humedad
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Viento
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Prob. lluvia
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Prob. lluvia x100
                        </th>
                        <th class="px-4 py-2 w-1/10">
                            Lluvia mm
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($weatherStats as $stat)
                        <tr class="bg-gray-50 text-center">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase whitespace-nowrap">
                                    {{ $stat['date'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center font-bold">
                                <p class="text-sm uppercase">
                                    {{ $stat['temp'] }}°
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['feels_like'] }}°
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['temp_min'] }}°
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['temp_max'] }}°
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center  font-bold">
                                <p class="text-sm uppercase">
                                    {{ $stat['humidity'] }}%
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm">
                                    {{ $stat['wind_speed'] }}km/h
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm">
                                    {{ $stat['pop'] }}%
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center font-bold">
                                <p class="text-sm">
                                    {{ $stat['popx100'] }}%
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm">
                                    {{ $stat['rain'] }}mm
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </x-responsive-table>
    </div>
</div>

@push('script')
    <script>
         Livewire.on('success', message => {
            Swal.fire({
                icon: 'success',
                title: '¡Sin problemas!',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#1f2937',
            });
        });

        Livewire.on('warning', message => {
            Swal.fire({
                icon: 'warning',
                title: '¡Atención!',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#1f2937',
            });
        });
    </script>
@endpush
