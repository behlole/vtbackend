<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $fillable = [
        'last_name', 'first_name', 'email', 'profile_pic', 'date_of_birth', 'phone_number', 'gender', 'department', 'city'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'teachers_students', 'teacher_id', 'student_id');
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_teachers', 'teacher_id', 'meeting_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public static function getAllStudents($perPage)
    {
        $data = self::find(auth()->user()->id)->students()->paginate($perPage);
        return $data;
    }

    public static function addMeeting($id, $meetingId)
    {
        return self::find($id)->meetings()->attach($meetingId);
    }

    public static function getAllMeetings()
    {
        return self::find(auth()->user()->id)->meetings;
    }
}
