<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;

class ShowClient extends Component
{
    public $client;

    public $isOpen = false;

    public function mount($client)
    {
        $this->client = $client;
    }

    public function showClient()
    {
        $this->isOpen = true;
    }

    public function render()
    {
        return view('livewire.clients.show-client');
    }
}
