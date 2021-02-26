<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\Carbon;
use Faker\Provider\File;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $guarded = [];


    public static function deleteCourse($id)
    {
        if ($course = self::find($id)) {
            $course->delete();
            return response(
                [
                    'message' => 'Course has been deleted successfully ',
                    'Course' => $course
                ]);
        } else {
            return Helper::errorResponse("Course not found");
        }

    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'enrolled_courses',
            'course_id',
            'student_id');
    }

    public static function addCourse($course_name)
    {
        self::create(
            [
                'course_name'=>$course_name,
                'teacher_id'=>auth()->user()->id
            ]
        );
        return Helper::successResponse("Course added Successfully");
    }
}
