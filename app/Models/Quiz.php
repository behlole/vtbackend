<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';


  public static function questions()
  {
      return $this->hasMany(QuizQuestion::class);
  }
}
