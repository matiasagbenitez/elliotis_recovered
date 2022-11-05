<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class IndexClients extends Component
{
    use WithPagination;

    public $search;
    public $sort = 'id';
    public $direction = 'asc';

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'desc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $clients = Client::where('business_name', 'LIKE', "%" . $this->search . "%")->orderBy($this->sort, $this->direction)->paginate(6);
        return view('livewire.clients.index-clients', compact('clients'));
    }
}
