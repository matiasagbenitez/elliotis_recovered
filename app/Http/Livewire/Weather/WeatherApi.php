<?php

namespace App\Http\Livewire\Weather;

use App\Models\Company;
use App\Models\User;
use GuzzleHttp\Client;
use Livewire\Component;
use Illuminate\Support\Facades\Date;
use App\Models\WeatherApi as ModelsWeatherApi;
use App\Notifications\WeatherAlertNotification;

class WeatherApi extends Component
{
    public $weatherData;
    public $general_stats = [];
    public $weatherStats = [];
    public $conditions = [];

    public function mount()
    {
        try {
            $client = new Client();
            $appid = '80a1f0ad792ad6a929fdcbf257dc8166';

            $company = Company::first();
            $lat = $company->lat;
            $lon = $company->lon;

            $response = $client->get("http://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$appid&units=metric");
            $weatherData = json_decode($response->getBody()->getContents(), true);
            $this->getStats($weatherData);
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

        $conf = ModelsWeatherApi::first();
        $conditions = [
            'id' => $conf->id,
            'temp' => $conf->temp,
            'rain_prob' => $conf->rain_prob,
            'rain_prob_x100' => $conf->rain_prob * 100,
            'rain_mm' => $conf->rain_mm,
            'humidity' => $conf->humidity,
            'humidity_x100' => $conf->humidity * 100,
            'wind_speed' => $conf->wind_speed,
            'days_in_row' => $conf->days_in_row,
            'max_conditions' => $conf->max_conditions,
        ];
        $this->conditions = $conditions;

        $weatherStats = [];
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
            $weatherStats[$key]['popx100'] = $value['pop'] * 100;
            $weatherStats[$key]['rain'] = $value['rain']['3h'] ?? 0;
        }
        $this->weatherStats = $weatherStats;
    }

    public function testAlert()
    {
        $weatherData = $this->weatherStats;
        $temp_min = $this->conditions['temp'];
        $rain_prob = $this->conditions['rain_prob'];
        $rain_mm = $this->conditions['rain_mm'];
        $humidity = $this->conditions['humidity'];
        $wind_speed = $this->conditions['wind_speed'];
        $days_in_row = $this->conditions['days_in_row'];
        $max_conditions = $this->conditions['max_conditions'];
        // dd($weatherData);
        $this->decideGuardarProduccion($weatherData, $temp_min, $rain_prob, $rain_mm, $humidity, $wind_speed, $days_in_row, $max_conditions);
    }

    function decideGuardarProduccion($weatherData, $temp_min, $rain_prob, $rain_mm, $humidity, $wind_speed, $days_in_row, $max_conditions)
    {
        // dd($weatherData, $temp_min, $rain_prob, $rain_mm, $humidity, $wind_speed, $days_in_row);
        try {
            $averages = [];

            for ($i = 0; $i < $days_in_row; $i++) {
                // Crear variables para almacenar los valores diarios
                $temp_sum = 0;
                $rain_prob_sum = 0;
                $rain_mm_sum = 0;
                $humidity_sum = 0;
                $wind_speed_sum = 0;

                // Iterar por cada registro de $weatherData que corresponda al día actual ($i)
                for ($j = 0; $j < 8; $j++) {

                    $weather = $weatherData[($i * 8) + $j];

                    // Sumar los valores diarios
                    $temp_sum += $weather['temp'];
                    $rain_prob_sum += $weather['pop'];
                    $rain_mm_sum += $weather['rain'] ?? 0;
                    $humidity_sum += $weather['humidity'] / 100;
                    $wind_speed_sum += $weather['wind_speed'];
                }

                // Calcular los promedios diarios
                $temp_avg = $temp_sum / 8;
                $rain_prob_avg = $rain_prob_sum / 8;
                $rain_mm_avg = $rain_mm_sum / 8;
                $humidity_avg = $humidity_sum / 8;
                $wind_speed_avg = $wind_speed_sum / 8;

                // Agregar los promedios al arreglo de promedios
                $averages[] = [
                    'temp' => $temp_avg,
                    'rain_prob' => $rain_prob_avg,
                    'rain_mm' => $rain_mm_avg,
                    'humidity' => $humidity_avg,
                    'wind_speed' => $wind_speed_avg,
                ];
            }

            // Promedios de los promedios
            $temp_avg = 0;
            $rain_prob_avg = 0;
            $rain_mm_avg = 0;
            $humidity_avg = 0;
            $wind_speed_avg = 0;

            foreach ($averages as $average) {
                $temp_avg += $average['temp'];
                $rain_prob_avg += $average['rain_prob'];
                $rain_mm_avg += $average['rain_mm'];
                $humidity_avg += $average['humidity'];
                $wind_speed_avg += $average['wind_speed'];
            }

            // Decidir
            $temp_avg = round($temp_avg / $days_in_row, 2);
            $rain_prob_avg = round($rain_prob_avg / $days_in_row, 2);
            $rain_mm_avg = round($rain_mm_avg / $days_in_row, 2);
            $humidity_avg = round($humidity_avg / $days_in_row, 2);
            $wind_speed_avg = round($wind_speed_avg / $days_in_row, 2);

            // Si se cumplen 4 condiciones, guardar producción
            $conditions = 0;

            if ($temp_avg < $temp_min) {
                $conditions++;
            }
            if ($rain_prob_avg > $rain_prob) {
                $conditions++;
            }
            if ($rain_mm_avg > $rain_mm) {
                $conditions++;
            }
            if ($humidity_avg > $humidity) {
                $conditions++;
            }
            if ($wind_speed_avg < $wind_speed) {
                $conditions++;
            }

            $conditions_resume = [
                'temp_avg' => $temp_avg,
                'temp_min' => $temp_min,
                'rain_prob_avg' => $rain_prob_avg,
                'rain_prob' => $rain_prob,
                'rain_mm_avg' => $rain_mm_avg,
                'rain_mm' => $rain_mm,
                'humidity_avg' => $humidity_avg,
                'humidity' => $humidity,
                'wind_speed_avg' => $wind_speed_avg,
                'wind_speed' => $wind_speed,
                'days_in_row' => $days_in_row,
            ];

            if ($conditions >= $max_conditions) {
                $this->emit('warning', '¡Quizás sea conveniente guardar la producción!');
                $users = User::role('Administrador')->get();
                foreach ($users as $user) {
                    $user->notify(new WeatherAlertNotification($conditions_resume));
                }
            } else {
                $this->emit('success', '¡No es necesario guardar la producción!');
            }

        } catch (\Throwable $th) {
            dd($th);
        }
    }



    public function render()
    {
        return view('livewire.weather.weather-api');
    }
}
