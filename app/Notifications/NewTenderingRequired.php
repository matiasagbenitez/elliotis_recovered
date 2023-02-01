<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTenderingRequired extends Notification
{
    use Queueable;

    public $task_id;
    public $task_name;
    public $detail;

    public function __construct($task_id, $task_name, $detail)
    {
        $this->task_id = $task_id;
        $this->task_name = $task_name;
        $this->detail = $detail;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    // Almacena la notificación en la base de datos
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task_id,
            'task_name' => $this->task_name,
            'detail' => $this->detail,
        ];
    }
}
