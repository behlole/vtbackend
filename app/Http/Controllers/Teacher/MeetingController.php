<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Meeting;
use App\Models\Teacher;
use Carbon\Carbon;

class MeetingController extends Controller
{
    /**
     * @param $id
     */
    public function start($id, $code)
    {
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
        $meeting->start_time = date('H:i:s', time());
        $meeting->save();
        $meeting_id = Meeting::latest('id')->first()->id;
        Course::addMeeting($id, $meeting_id);
        Teacher::addMeeting(auth()->user()->role_id, $meeting_id);
        return Helper::successResponse("Meeting Started Successfully");

    }

    public function end($code)
    {
        $meeting = Meeting::where('meeting_code', $code)->first();


        $start = Carbon::parse($meeting->date_begin);
        $end = Carbon::parse(Carbon::now());
        $hours = $end->diffInHours($start);
        $seconds = $end->diffInSeconds($start);
        $duration = $hours . ':' . $seconds;


        Meeting::where('meeting_code', $code)->update([
            'end_time' => $end,
            'duration' => $duration
        ]);
        Course::where('current_meeting_code', $code)->update(['current_meeting_code' => '']);
        return Helper::successResponse("Meeting ended");
    }

    public function getMeetings()
    {
        $data = Teacher::getAllMeetings();
        foreach ($data as $meetings) {
            $meetings['course_name'] = Meeting::findCourseById($meetings->meeting_code)->course_name;
            $start = Carbon::parse($meetings->start_date);
            $end = Carbon::parse($meetings->end_date);
            $hours = $end->diffInHours($start);
            $seconds = $end->diffInSeconds($start);

            $meetings['duration'] = $hours . ':' . $seconds;
        }
        return $data;
    }
}
