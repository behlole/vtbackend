<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function getAll()
    {
        return Teacher::find(auth()->user()->id)->courses;
    }

    public function update(Request $request)
    {
        try {

            $this->validate($request, [
                'course_name' => 'required',
                'id' => 'required',
            ]);
            $course = Course::find($request->input('id'));
            $course->update([
                'course_name' => $request->input('course_name')
            ]);
            return Helper::successResponse("Course updated successfully");
        } catch (Exception $e) {
            return Helper::errorResponse($e->getMessage());
        }

    }

    public function add(Request $request)
    {
        try {
            $this->validate($request, [
                'course_name' => 'required',
            ]);
            return Course::addCourse($request->input('course_name'));

        } catch (Exception $e) {
            return Helper::errorResponse($e->getMessage());
        }
    }

    public function delete($id)
    {
        return Course::deleteCourse($id);

    }

    public function enrol(Request $request)
    {
        $this->validate($request,
            [
                'student_id' => 'required',
                'course_id' => 'required',
            ]);

        return (new Student)->enrolCourse($request->input('course_id'), $request->input('student_id'));


    }

    public function getEnrolled($id)
    {
        return Course::find($id)->students;
    }
}
