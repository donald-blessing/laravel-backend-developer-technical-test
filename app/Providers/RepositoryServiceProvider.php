<?php

namespace App\Providers;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\Service\ServiceRepository;
use App\Repositories\Eloquent\User\UserRepository;
use App\Repositories\Interface\BaseRepositoryInterface;
use App\Repositories\Interface\Service\ServiceRepositoryInterface;
use App\Repositories\Interface\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{

    protected $repositories = [
        BaseRepositoryInterface::class => BaseRepository::class,
        ServiceRepositoryInterface::class => ServiceRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
