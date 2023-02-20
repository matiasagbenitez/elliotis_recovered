<?php

namespace App\Http\Livewire\WeatherApi;

use GuzzleHttp\Client;
use App\Models\Company;
use Livewire\Component;
use App\Models\UsualCoordinate;

class EditCoordinates extends Component
{
    public $editForm = [
        'lat' => '',
        'lon' => '',
    ];

    public $city_id = '';

    public $usual_coordinates = [];

    protected $listeners = ['save'];

    public function mount()
    {
        $this->init();
        $this->usual_coordinates = UsualCoordinate::all();
    }

    public function init()
    {
        $company = Company::first();
        $this->editForm = [
            'lat' => $company->lat,
            'lon' => $company->lon,
        ];
    }

    public function updatedCityId()
    {
        $usual_coordinate_id = UsualCoordinate::find($this->city_id);
        $this->editForm['lat'] = $usual_coordinate_id->lat;
        $this->editForm['lon'] = $usual_coordinate_id->lon;
    }

    public function updatedEditForm()
    {
        $this->city_id = '';
    }

    public function presave()
    {
        $client = new Client();
        $appid = '80a1f0ad792ad6a929fdcbf257dc8166';

        $lat = $this->editForm['lat'];
        $lon = $this->editForm['lon'];

        try {
            $response = $client->get("http://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$appid&units=metric");

            if ($response->getStatusCode() == 200) {
                $weatherData = json_decode($response->getBody()->getContents(), true);
                $city = $weatherData['city']['name'];
                $this->emit('warning', "La ciudad que coincide con las coordenadas ingresadas es $city. ¿Desea guardar los cambios?");
            }
        } catch (\Throwable $th) {
            $this->emit('error', "No se encontró la ciudad");
            $this->init();
        }
    }

    public function save()
    {
        $client = new Client();
        $appid = '80a1f0ad792ad6a929fdcbf257dc8166';

        $lat = $this->editForm['lat'];
        $lon = $this->editForm['lon'];

        try {
            $response = $client->get("http://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$appid&units=metric");

            if ($response->getStatusCode() == 200) {
                $weatherData = json_decode($response->getBody()->getContents(), true);
                $city = $weatherData['city']['name'];

                $company = Company::first();
                $company->update([
                    'lat' => $lat,
                    'lon' => $lon,
                ]);

                $new_coordinate = [
                    'city' => $city,
                    'lat' => $lat,
                    'lon' => $lon,
                ];

                // UPDATE OR CREATE
                UsualCoordinate::updateOrCreate(
                    ['city' => $city],
                    $new_coordinate
                );

                session()->flash('message', 'Las coordenadas se guardaron correctamente.');
                return redirect()->route('admin.api.index');
            }
        } catch (\Throwable $th) {
            $this->emit('error', "Ocurrió un error al guardar las coordenadas.");
            $this->init();
        }
    }

    public function render()
    {
        return view('livewire.weather-api.edit-coordinates');
    }
}
