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
        return Student::find($student_id)->activities->where('meeting_id', $meeting_id)->toArray();
    }

    public static function joinMeeting($meetingId, $role_id)
    {
        return self::find($role_id)->meetings()->attach($meetingId);
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
        $student = self::find(auth()->user()->role_id);
        $meetingId = Meeting::findByCode($meeting_code);
        if ($activity_record == 'Joined-Meeting') {
            Student::joinMeeting($meetingId, auth()->user()->role_id);
        }
        /*
         * Type of activities
         *  1. Video-Turned-Off
         *  2. Audio-Turned-On
         *  3. Audio-Turned-Off
         *  4. Joined-Meeting
         *  5. Left-Meeting
         *  7. Video-Turned-On
         *  8. Screen-Sharing-Turned-On
         *  9. Screen-Sharing-Turned-Off
         */
        $activity = new StudentActivity();
        $activity->student_id = auth()->user()->role_id;
        $activity->meeting_id = $meetingId;
        $activity->activity_type = $activity_record;
        if ($activity_record == 'Audio-Turned-On') {
            StudentCheck::where('student_id', auth()->user()->role_id)->update(
                [
                    'audio' => true
                ]
            );
        } else if ($activity_record == 'Audio-Turned-Off') {
            StudentCheck::where('student_id', auth()->user()->role_id)->update(
                [
                    'audio' => false
                ]
            );

        } else if ($activity_record == 'Video-Turned-Off') {
            StudentCheck::where('student_id', auth()->user()->role_id)->update(
                [
                    'video' => false
                ]
            );
        } else if ($activity_record == 'Video-Turned-On') {
            StudentCheck::where('student_id', auth()->user()->role_id)->update(
                [
                    'video' => true
                ]
            );
        } else if ($activity_record == 'Screen-Sharing-Turned-Off') {
            StudentCheck::where('student_id', auth()->user()->role_id)->update(
                [
                    'screen' => false
                ]
            );
        } else if ($activity_record == 'Screen-Sharing-Turned-On') {
            StudentCheck::where('student_id', auth()->user()->role_id)->update(
                [
                    'screen' => true
                ]
            );
        } else if ($activity_record == 'Left-Meeting') {
            StudentCheck::where('student_id', auth()->user()->role_id)->delete();
        } else if ($activity_record == 'Joined-Meeting') {
            $student1 = new StudentCheck();
            $student1->student_id = auth()->user()->role_id;
            $student1->save();
        }
        return $student->activities()->save($activity);
    }
}
