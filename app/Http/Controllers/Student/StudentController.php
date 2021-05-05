<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentController extends Controller
{
    public function getTeacher()
    {
        return Student::getTeacher(auth()->user()->role_id);
    }

    public function getCourses()
    {
        return Student::getCourse(auth()->user()->role_id);
    }

    public function recordActiviy($meeting_code, $activity)
    {
        Student::recordActivity($meeting_code, $activity);
    }
}
