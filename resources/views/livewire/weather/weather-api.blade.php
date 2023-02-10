<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">OpenWeather API</h1>
        </div>
    </x-slot>

    <div class="px-10 py-5 bg-white rounded-lg shadow mb-5">
        <div class="my-1">
            <span class="font-bold text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                OpenWeather API
            </span>
            <hr class="my-1">
            <p class="font-bold">
                Respuesta:
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
                Cantidad de registros en consideración:
                <span class="font-normal">
                    {{ $general_stats['count'] }} registros
                </span>
            </p>
        </div>

        <x-responsive-table>

            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase whitespace-nowrap">
                        <th class="px-4 py-2">
                            Fecha
                        </th>
                        <th class="px-4 py-2">
                            Temperatura (T°)
                        </th>
                        <th class="px-4 py-2">
                            Sensación
                        </th>
                        <th class="px-4 py-2">
                            Mínima
                        </th>
                        <th class="px-4 py-2">
                            Máxima
                        </th>
                        <th class="px-4 py-2">
                            Humedad
                        </th>
                        <th class="px-4 py-2">
                            Clima
                        </th>
                        <th class="px-4 py-2">
                            Viento
                        </th>
                        <th class="px-4 py-2">
                            Probabilidad lluvia
                        </th>
                        <th class="px-4 py-2">
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
                            <td class="px-6 py-3 text-center">
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
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['humidity'] }}%
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm">
                                    {{ $stat['weather'] }}
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
