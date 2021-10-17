<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interface\Service\ServiceRepositoryInterface;
use App\Repositories\Interface\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class UserRepository
 *
 * @package \App\Repositories\Eloquent\User
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $services;

    public function __construct(ServiceRepositoryInterface $services)
    {
        parent::__construct();
        $this->services = $services;
    }

    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * Get all service providers
     *
     *
     * @return Collection|null
     */
    public function serviceProviders(): ?Collection
    {
        return $this->model->whereType('service')->orderBy('name', 'asc')->get();
    }

    /**
     * Get customers
     *
     * @return Collection|null
     */
    public function customers(): ?Collection
    {
        return $this->model->whereType('customer')->orderBy('name', 'asc')->get();
    }

    /**
     * Get administrator members
     *
     * @return Collection|null
     */
    public function administrators(): ?Collection
    {
        return $this->model->whereType('admin')->orderBy('name', 'asc')->get();
    }

    public function register(array $data): User
    {

        $user = $this->create([
            'name' => $data["name"],
            'email' => $data["email"],
            'password' => Hash::make($data["password"]),
            'type' => $data["type"],
        ]);

        if ($user->type == 'service') {
            $service = $this->services->where('slug', Str::slug($data["service"]))->first();

            if (empty($service)) {
                if ($data["service"] == "other") {
                    $service = $this->services->create([
                        'name' => strtolower($data["service_type"]),
                        'slug' => Str::slug($data["service_type"]),
                    ]);
                } else {
                    $service = $this->services->create([
                        'name' => strtolower($data["service"]),
                        'slug' => Str::slug($data["service"]),
                    ]);
                }
            }
            // dd($service);
            $user->services()->attach($service->id);
        }
        return $user;
    }
}
