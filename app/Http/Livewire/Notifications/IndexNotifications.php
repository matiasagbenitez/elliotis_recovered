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
