<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 'rating',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(Student::class);
    }
}
