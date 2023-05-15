<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\StudentAffair;
use App\Models\Lecturer;
use App\Models\Classroom;
use App\Models\Message;


class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_sender_id',
        'student_affairs_sender_id',
        'lecturer_sender_id',
        'student_reciver_id',
        'student_affairs_reciver_id',
        'lecturer_reciver_id'
    ];

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
    public function classroom()
    {
        return $this->hasMany(Classroom::class);
    }
    public function message()
    {
        return $this->hasMany(Message::class);
    }
}
