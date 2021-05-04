<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'id', 'last_name', 'email', 'first_name', 'profile_pic', 'date_of_birth', 'gender', 'phone_number', 'is_enrolled', 'city', 'department',
    ];

    public static function findStudentsByMeetingCode($code)
    {
        return Meeting::findMeetingByCode($code)->students;
    }

    public static function getActivityRecordByMeeting($meeting_id, $student_id)
    {
        return Student::find($student_id)->activities->where('meeting_id', $meeting_id);
    }

    public function meetings()
    {
        return $this->belongsToMany(
            Meeting::class,
            'meeting_students',
            'student_id',
            'meeting_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrolled_courses', 'student_id', 'course_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teachers_students', 'student_id', 'teacher_id');
    }

    public function activities()
    {
        return $this->hasMany(StudentActivity::class);
    }

    public function enrolCourse($course, $student_id)
    {
        self::find($student_id)->courses()->detach();
        return self::find($student_id)->courses()->attach($course, ['enrollment_date' => Carbon::now()->format('Y-m-d')]);

    }

    public static function getTeacher($id)
    {
        return self::find($id)->teachers;
    }

    public static function getCourse($id)
    {
        return self::find($id)->courses;
    }

    public static function recordActivity($meeting_code, $activity_record)
    {

        //TODO student record with respect to meeting check activity and then store that with relationed table of meeting_students :)
        $student = self::find(auth()->user()->role_id);
        $meetingId = Meeting::findByCode($meeting_code);
        $activity = new StudentActivity();
        $activity->student_id = auth()->user()->role_id;
        $activity->meeting_id = $meetingId;
        $activity->activity_type = $activity_record;

        return $student->activities()->save($activity);
    }
}
