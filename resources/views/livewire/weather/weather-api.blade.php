<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">OpenWeather API</h1>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow mb-5">
        <div class="px-6 py-4">
            <span class="font-bold text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                OpenWeather API
            </span>
            <hr class="my-3">

            <div class="flex">
                <div class="w-1/2 uppercase">
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
                        Pronósitico:
                        <span class="font-normal">
                            Próximos 5 días, intervalo de 3 horas
                        </span>
                    </p>
                    <p class="font-bold">
                        Total datos:
                        <span class="font-normal">
                            {{ $general_stats['count'] }} registros
                        </span>
                    </p>
                </div>

                <div class="w-1/2 uppercase">
                    <p class="font-bold">Condiciones lanzamiento de alarma</p>
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
