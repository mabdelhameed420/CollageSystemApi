<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Lecturer;
use App\Models\Question;


class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'classroom_id',
        'limit_time',
        'lecturer_id',
        'number_questions',
        'course_id'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function realtime()
    {
        return $this->hasOne(Realtime::class);
    }
}
