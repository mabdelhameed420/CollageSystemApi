<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\StudentAffair;
use App\Models\Lecturer;
use App\Models\Post;
use App\Models\CommentReply;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_text',
        'timestamp',
        'student_id',
        'student_affairs_id',
        'lecturer_id',
        'post_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentAffairs()
    {
        return $this->belongsTo(StudentAffair::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    public function commentReply()
    {
        return $this->hasMany(CommentReply::class);
    }
}
