<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Chat;


class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'sentAt',
        'image',
        'voice_file',
        'classroom_id',
        'chat_id',
        'sender',
        'receiver',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
