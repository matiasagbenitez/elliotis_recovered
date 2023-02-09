<?php

namespace App\Http\Services;

class WeatherService
{
    function decideWeather($weatherStats) {
        $rainThreshold = 50; // porcentaje
        $temperatureThreshold = 20; // grados Celsius
        $rainyDays = 0;
        $temperature = 0;

        foreach ($weatherStats['list'] as $weather) {
          // contar los días con lluvia
          if ($weather['pop'] > $rainThreshold) {
            $rainyDays++;
          }
          // calcular la temperatura promedio
          $temperature += $weather['main']['temp'];
        }

        $temperature = $temperature / count($weatherStats['list']);

        // tomar una decisión basada en los criterios
        if ($rainyDays > 10 && $temperature < $temperatureThreshold) {
          return "Es recomendable guardar la producción bajo techo debido a lluvias frecuentes y temperaturas bajas.";
        } else {
          return "No es necesario guardar la producción bajo techo.";
        }
      }

}
