<?php

namespace App\Models;

use App\Models\Service\Service;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The services that belong to the user.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * Get the user admin
     *
     * @return string
     */
    public function getIsAdminAttribute()
    {
        return $this->type == 'admin';
    }

    /**
     * Get the user customer
     *
     * @return string
     */
    public function getIsCustomerAttribute()
    {
        return $this->type == 'customer';
    }

    /**
     * Get the user service provider
     *
     * @return string
     */
    public function getIsServiceProviderAttribute()
    {
        return $this->type == 'service';
    }
}
