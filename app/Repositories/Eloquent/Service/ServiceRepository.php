<?php

namespace App\Repositories\Eloquent\Service;

use App\Models\Service\Service;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interface\Service\ServiceRepositoryInterface;

class ServiceRepository extends BaseRepository implements ServiceRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function model(): string
    {
        return Service::class;
    }
}
