<?php

namespace App\Http\Livewire\Notifications;

use App\Models\Product;
use Illuminate\Notifications\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Termwind\Components\Dd;

class IndexNotifications extends Component
{
    use WithPagination;

    public $notifications = [];
    public $text = [];
    protected $listeners = ['markAsRead', 'createTendering'];

    public function mount()
    {
        $this->notifications = auth()->user()->notifications;
        $this->text = $this->formatNotifications();
    }

    public function formatNotifications()
    {
        $text = [];
        foreach ($this->notifications as $notification) {
            if ($notification->type == 'App\Notifications\NewTenderingRequired') {
                $message = 'La propuesta es: ';
                foreach ($notification->data['detail'] as $detail) {
                    $product = Product::find($detail['product_id']);
                    $reposition = $detail['reposition'];
                    $message .= "x $reposition $product->name - ";
                }
                $message = substr($message, 0, -3);
                $text[] = [
                    'id' => $notification->id,
                    'task_id' => $notification->data['task_id'],
                    'created_at' => 'Recibido el ' .  $notification->created_at->format('d-m-Y H:i'),
                    'title' => 'Tras la finalización de la tarea ' . $notification->data['task_name'] . ' se ha detectado bajo stock de rollos, por lo que se recomienda realizar un pedido. ¿Desea iniciar un proceso de licitación ahora?',
                    'read_at' => $notification->read_at,
                    'message' => $message,
                    'licitation' => true,
                ];
            } elseif ($notification->type == 'App\Notifications\WeatherAlertNotification') {
                $message = 'Las condiciones climáticas son: ';
                $message .= "Temperatura media próximos días: " . $notification->data['conditions_resume']['temp_avg'] . "ºC - ";
                $message .= "Temperatura mínima esperada: " . $notification->data['conditions_resume']['temp_min'] . "ºC - ";
                $message .= "Probabilidad de lluvia media: " . $notification->data['conditions_resume']['rain_prob_avg'] . "% - ";
                $message .= "Probabilidad de lluvia máxima: " . $notification->data['conditions_resume']['rain_prob'] . "% - ";
                $message .= "Lluvia media próximos días: " . $notification->data['conditions_resume']['rain_mm_avg'] . "mm - ";
                $message .= "Lluvia máxima aceptada: " . $notification->data['conditions_resume']['rain_mm'] . "mm - ";
                $message .= "Humedad media próximos días: " . $notification->data['conditions_resume']['humidity_avg'] . "% - ";
                $message .= "Humedad máxima esperada: " . $notification->data['conditions_resume']['humidity'] . "% - ";
                $message .= "Velocidad media del viento próximos días: " . $notification->data['conditions_resume']['wind_speed_avg'] . "km/h - ";
                $message .= "Mínima velocidad del viento esperada: " . $notification->data['conditions_resume']['wind_speed'] . "km/h - ";
                $message .= "Días seguidos: " . $notification->data['conditions_resume']['days_in_row'] . " - ";
                $message = substr($message, 0, -3);
                $text[] = [
                    'id' => $notification->id,
                    'created_at' => 'Recibido el ' .  $notification->created_at->format('d-m-Y H:i'),
                    'title' => 'Se ha detectado un cambio en las condiciones climáticas. Es posible que desee resguardar la producción que se encuentra al aire libre.',
                    'read_at' => $notification->read_at,
                    'message' => $message,
                    'licitation' => false,
                ];
            }
        }
        return $text;
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications->where('id', $id)->first();
        $notification->markAsRead();
        $this->notifications = auth()->user()->notifications;
        $this->text = $this->formatNotifications();
    }

    public function createTendering($id)
    {
        $notification = auth()->user()->notifications->where('id', $id)->first();
        $notification->markAsRead();

        return redirect()->route('admin.tenderings.create', ['notification' => $id]);
    }

    public function render()
    {
        return view('livewire.notifications.index-notifications');
    }
}
