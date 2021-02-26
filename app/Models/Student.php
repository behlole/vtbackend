<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table='students';
    protected $fillable=[
        'id','last_name','email','first_name','profile_pic','date_of_birth','gender','phone_number','is_enrolled','city','department',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class,'enrolled_courses','student_id','course_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'teachers_students','student_id','teacher_id');
    }


    public function enrolCourse($course,$student_id)
    {
        self::find($student_id)->courses()->detach();
        return self::find($student_id)->courses()->attach($course,['enrollment_date'=>Carbon::now()->format('Y-m-d')]);

    }
}
