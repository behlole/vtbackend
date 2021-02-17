<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table='students';
    protected $fillable=[
        'id','last_name','first_name'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class,'enrolled_courses','student_id','course_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'teachers_students','student_id','teacher_id');
    }

    public static function enrol($id,$course_id)
    {
        if($student=self::find($id))
        {

        }
    }
}
