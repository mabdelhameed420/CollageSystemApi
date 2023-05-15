<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lecturer;
use App\Models\Department;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'course_code',
        'department_id',
        'level',
        'semester',
    ];

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function classroom()
    {
        return $this->hasMany(Classroom::class);
    }
    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
}
