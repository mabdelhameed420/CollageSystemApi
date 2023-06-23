<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Post;
use App\Models\Admin;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StudentAffair extends  Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'national_id',
        'email',
        'phone_no',
        'image',
        'admin_id',
        'password',
        'responsible_level',
        'date_added'
    ];
    protected $hidden = [
        'password',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function chat()
    {
        return $this->hasMany(Chat::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function commentReply()
    {
        return $this->hasMany(CommentReply::class);
    }
    public function post()
    {
        return $this->hasMany(Post::class);
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
