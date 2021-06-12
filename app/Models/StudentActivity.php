<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentActivity extends Model
{
    protected $table = 'student_activity_records';
    protected $guarded = ['id'];

    public static function joinMeeting($courseId, $meetingId)
    {

    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
