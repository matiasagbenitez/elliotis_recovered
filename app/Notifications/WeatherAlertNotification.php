<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeatherAlertNotification extends Notification
{
    use Queueable;

    public $conditions_resume = [];

    public function __construct($conditions_resume)
    {
        $this->conditions_resume = [
            'temp_avg' => $conditions_resume['temp_avg'],
            'temp_min' => $conditions_resume['temp_min'],
            'rain_prob_avg' => $conditions_resume['rain_prob_avg'],
            'rain_prob' => $conditions_resume['rain_prob'],
            'rain_mm_avg' => $conditions_resume['rain_mm_avg'],
            'rain_mm' => $conditions_resume['rain_mm'],
            'humidity_avg' => $conditions_resume['humidity_avg'],
            'humidity' => $conditions_resume['humidity'],
            'wind_speed_avg' => $conditions_resume['wind_speed_avg'],
            'wind_speed' => $conditions_resume['wind_speed'],
            'days_in_row' => $conditions_resume['days_in_row'],
        ];
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    // Almacena la notificación en la base de datos
    public function toDatabase($notifiable)
    {
        return [
            'conditions_resume' => $this->conditions_resume,
        ];
    }
}
