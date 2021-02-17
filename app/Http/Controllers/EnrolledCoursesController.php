<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Models\Student;
use App\Models\User;
use Carbon\Traits\Date;
use Illuminate\Http\Request;

class EnrolledCoursesController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request,[
            'student_email'=>'required',
            'course_id'=>'required',
        ]);
        $enrollment['student_id']=User::findByEmail($request->input('student_email'));
        if($enrollment['student_id'])
        {
            $enrollment['course_id']=Course::find($request->input('course_id'))->first()->id;
            $enrollment['enrollment_date']=date('Y-m-d H');
            $course=new EnrolledCourse();
            $course->fill($enrollment);
            $course->save();
            return Helper::successResponse("Course enrolled successfully");
        }
        else
        {
            return Helper::errorResponse("Student email not found ");
        }

    }
}
