<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Lecturer;


class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'department_code',
        'semester',
        'level',
    ];
    public function course()
    {
        return $this->hasMany(Course::class);
    }
    public function lecturer()
    {
        return $this->hasMany(Lecturer::class);
    }
    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
