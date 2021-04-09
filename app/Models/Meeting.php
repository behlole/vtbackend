<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $table = 'meetings';
    protected $guarded = [];

    public static function findByCode($code)
    {
        return self::where('meeting_code', $code)->first()->id;
    }

    public static function findCourseById($code)
    {
        return self::where('meeting_code', $code)->first()->courses->first();
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'meeting_students',
            'meeting-id',
            'student_id');
    }

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'meeting_courses',
            'meeting_id',
            'course_id'
        );
    }
}
