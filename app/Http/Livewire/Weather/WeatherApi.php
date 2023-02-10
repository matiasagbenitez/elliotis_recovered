<?php

namespace App\Http\Livewire\Weather;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class WeatherApi extends Component
{
    public $weatherData;
    public $general_stats = [];
    public $weatherStats = [];

    public function mount()
    {
    //    Try or fail
        try {
            $client = new Client();
            $appid = '80a1f0ad792ad6a929fdcbf257dc8166';
            $lat = '-27.0435';
            $lon = '-55.227';

            $response = $client->get("http://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$appid&units=metric");
            $weatherData = json_decode($response->getBody()->getContents(), true);
            $this->getStats($weatherData);
            // $rta = $this->decideWeather($weatherData);
            // dd($rta);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getStats($weatherData)
    {
        $general_stats = [
            'code' => $weatherData['cod'],
            'city' => $weatherData['city']['name'],
            'country' => $weatherData['city']['country'],
            'timezone' => $weatherData['city']['timezone'],
            'sunrise' => Date::createFromTimestamp($weatherData['city']['sunrise'])->format('H:i:s'),
            'sunset' => Date::createFromTimestamp($weatherData['city']['sunset'])->format('H:i:s'),
            'count' => $weatherData['cnt'],
        ];
        $this->general_stats = $general_stats;

        $weatherStats = [];
        // dd($weatherData['list']);
        foreach ($weatherData['list'] as $key => $value) {
            $weatherStats[$key]['date'] = Date::createFromTimestamp($value['dt'])->format('d-m-Y H:i');
            $weatherStats[$key]['temp'] = $value['main']['temp'];
            $weatherStats[$key]['feels_like'] = $value['main']['feels_like'];
            $weatherStats[$key]['temp_min'] = $value['main']['temp_min'];
            $weatherStats[$key]['temp_max'] = $value['main']['temp_max'];
            $weatherStats[$key]['humidity'] = $value['main']['humidity'];
            $weatherStats[$key]['weather'] = $value['weather'][0]['main'];
            $weatherStats[$key]['wind_speed'] = $value['wind']['speed'];
            $weatherStats[$key]['pop'] = $value['pop'];
            $weatherStats[$key]['rain'] = $value['rain']['3h'] ?? 0;
        }
        $this->weatherStats = $weatherStats;

    }

    function decideWeather($weatherData) {
        $rainThreshold = 50; // porcentaje
        $temperatureThreshold = 20; // grados Celsius
        $rainyDays = 0;
        $temperature = 0;

        foreach ($weatherData['list'] as $weather) {
          // contar los días con lluvia
          if ($weather['pop'] > $rainThreshold) {
            $rainyDays++;
          }
          // calcular la temperatura promedio
          $temperature += $weather['main']['temp'];
        }

        $temperature = $temperature / count($weatherData['list']);

        // tomar una decisión basada en los criterios
        if ($rainyDays > 10 && $temperature < $temperatureThreshold) {
          return "Es recomendable guardar la producción bajo techo debido a lluvias frecuentes y temperaturas bajas.";
        } else {
          return "No es necesario guardar la producción bajo techo.";
        }
      }


    public function render()
    {
        return view('livewire.weather.weather-api');
    }
}
