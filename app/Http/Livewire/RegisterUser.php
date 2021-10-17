<?php

namespace App\Http\Livewire;

use App\Repositories\Eloquent\Service\ServiceRepository;
use Livewire\Component;

class RegisterUser extends Component
{
    public $type;
    public $service;
    public $services;
    public $otherSelected;


    public function render()
    {
        $repository = new ServiceRepository;
        $this->services = $repository->all();
        return view('livewire.register-user', ['services' => $this->services]);
    }

    public function updatedService()
    {
        if ($this->service == "other") {
            $this->otherSelected = true;
        } else {
            $this->otherSelected = false;
        }
    }
}
