<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                'course_id' => 'required',
            ]);
            $course = Course::find($request->input('course_id'));
            if ($request->hasFile('thumbnail')) {

                if (Storage::disk('local')->exists('courses/' . $course->thumbnail)) {
                    unlink(app()->basePath('storage/app/courses/' . $course->thumbnail));
                }
                $file = $request->file('thumbnail');
                $file_name = Carbon::now()->timestamp . "-" . $file->getClientOriginalName();
                $course->thumbnail = $file_name;
                Storage::putFileAs("courses", $file, $file_name);

            }
            $course->course_name = $request->input('course_name');
            $course->save();
            return Helper::successResponse("Course updated successfully");
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getMessage());
        }

    }

    public function add(Request $request)
    {
        try {
            $this->validate($request, [
                'course_name' => 'required',
            ]);
            $course = new Course();
            $course->course_name = $request->input('course_name');
            $course->teacher_id = auth()->user()->id;
            if ($request->hasFile('thumbnail')) {


                $file = $request->file('thumbnail');
                $file_name = Carbon::now()->timestamp . "-" . $file->getClientOriginalName();
                $course->thumbnail = $file_name;
                Storage::putFileAs("courses", $file, $file_name);
            }
            $course->save();

            return Helper::successResponse("Course added Successfully");
        } catch (\Exception $e) {
            return Helper::errorResponse($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $data = Course::deleteCourse($request->input('course_id'));
        if ($data) {
            if (Storage::disk('local')->exists('courses/' . $data->thumbnail)) {
                unlink(app()->basePath('storage/app/courses/' . $data->thumbnail));
            }
            return response(
                [
                    'message' => 'Course has been deleted successfully ',
                    'Course' => $data
                ]
            );

        } else {
            return Helper::errorResponse("Course not found");
        }
    }

    public function enrol(Request $request)
    {
        $this->validate($request,
        [
            'student_id'=>'required',
            'course_id'=>'required',
        ]);
        $student=Student::find($request->input('student_id'));
        if($student)
        {
            $student->courses()->attach($request->input('student_id'),['enrollment_date'=>Carbon::today()]);
            return response(
                [
                    'message'=>'Student Successfully enrolled',
                    'data'=>$student,
                ]
            );
        }
        else
        {
            return Helper::errorResponse("Student could not be enrolled");
        }

    }
}
