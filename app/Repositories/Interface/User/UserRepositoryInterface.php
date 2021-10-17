<?php

namespace App\Repositories\Interface\User;

use App\Models\User;
use App\Repositories\Interface\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all service providers
     *
     *
     * @return Collection|null
     */
    public function serviceProviders(): ?Collection;

    /**
     * Get customers
     *
     * @return Collection|null
     */
    public function customers(): ?Collection;

    /**
     * Get administrator members
     *
     * @return Collection|null
     */
    public function administrators(): ?Collection;

    public function register(array $data): User;
}
