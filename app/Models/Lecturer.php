<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Department;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Course;
use App\Models\Post;
use App\Models\Quiz;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Lecturer extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'national_id',
        'email',
        'image',
        'course_id',
        'phone_no',
        'password',
        'department_id',
    ];
    protected $hidden = [
        'password',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function chat()
    {
        return $this->hasMany(Chat::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function commentReplies()
    {
        return $this->hasMany(CommentReply::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function post()
    {
        return $this->hasMany(Post::class);
    }
    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
    public function classroom()
    {
        return $this->hasMany(Classroom::class);
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
