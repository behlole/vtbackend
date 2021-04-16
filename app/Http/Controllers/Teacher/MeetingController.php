<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Meeting;
use App\Models\Teacher;

class MeetingController extends Controller
{
    /**
     * @param $id
     */
    public function start($id, $code)
    {
        date_default_timezone_set("Asia/Karachi");
        $course = Course::findCourse($id);
        $students = $course->students;
        Course::updateCode($id, $code);
        //TODO Mail Driver
//        foreach ($students as $student)
//        {
//            Mail::to($student->email);
//        }
        $meeting = new Meeting();
        $meeting->meeting_code = $code;
        $meeting->teacher_id = auth()->user()->id;
        $meeting->start_time = date("Y-m-d h:i:s");
//        $meeting->start_time =time('h:i:s');
        $meeting->save();
        $meeting_id = Meeting::latest('id')->first()->id;
        Course::addMeeting($id, $meeting_id);
        Teacher::addMeeting(auth()->user()->role_id, $meeting_id);
        return Helper::successResponse("Meeting Started Successfully");

    }

    public function end($code)
    {
        $meeting = Meeting::where('meeting_code', $code)->first();

        date_default_timezone_set("Asia/Karachi");
        $end = date("Y-m-d h:i:s");
        $diff = abs(strtotime($end) - strtotime($meeting->start_time));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24)
            / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
                $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24
                - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
            / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
                - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
                - $hours * 60 * 60) / 60);
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
            - $hours * 60 * 60 - $minutes * 60));

        Meeting::where('meeting_code', $code)->update([
            'end_time' => $end,
            'duration' => $hours . ' hours,' . $minutes . ' minutes,' . $seconds . ' seconds'
        ]);
        Course::where('current_meeting_code', $code)->update(['current_meeting_code' => '']);
        return Helper::successResponse("Meeting ended");
    }

    public function getMeetings()
    {
        $data = Teacher::getAllMeetings();
        foreach ($data as $meetings) {
            $meetings['course_name'] = Meeting::findCourseById($meetings->meeting_code)->course_name;
        }
        return $data;
    }
}