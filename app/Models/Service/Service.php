<?php

namespace App\Models\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'name'];

    /**
     * The users that belong to the service.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
