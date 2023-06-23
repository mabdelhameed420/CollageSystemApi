<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StudentAffair;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'national_id',
        'email',
        'image',
        'phone_no',
        'password',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'password',
    ];
    public function  studentAffair()
    {
        return $this->hasMany(StudentAffair::class);
    }


    use Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
