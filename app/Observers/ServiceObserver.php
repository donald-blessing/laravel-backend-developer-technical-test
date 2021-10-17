<?php

namespace App\Observers;

use App\Models\Service\Service;
use Illuminate\Support\Str;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     *
     * @param  \App\Models\Service\Service  $service
     * @return void
     */
    public function created(Service $service)
    {
        //
    }

    /**
     * Handle the Service "creating" event.
     *
     * @param  \App\Models\Service\Service  $service
     * @return void
     */
    public function creating(Service $service)
    {
        $service->slug = Str::slug($service->name);
    }

    /**
     * Handle the Service "updated" event.
     *
     * @param  \App\Models\Service\Service  $service
     * @return void
     */
    public function updated(Service $service)
    {
        //
    }

    /**
     * Handle the Service "deleted" event.
     *
     * @param  \App\Models\Service\Service  $service
     * @return void
     */
    public function deleted(Service $service)
    {
        //
    }

    /**
     * Handle the Service "restored" event.
     *
     * @param  \App\Models\Service\Service  $service
     * @return void
     */
    public function restored(Service $service)
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     *
     * @param  \App\Models\Service\Service  $service
     * @return void
     */
    public function forceDeleted(Service $service)
    {
        //
    }
}
