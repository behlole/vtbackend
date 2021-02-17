<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table='quiz_questions';
    public static function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
