<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\ResponseFactory;

class Course extends Model
{
    /**
     * @var string
     */
    protected $table = 'courses';
    /**
     * @var string[]
     */
    protected $guarded = ['id'];


    /**
     * @param $id
     * @return array|Response|ResponseFactory
     */
    public static function deleteCourse($id)
    {
        if ($course = self::find($id)) {
            $course->delete();
            if ($course->meeting)
                $course->meeting->detach();
            return response(
                [
                    'message' => 'Course has been deleted successfully ',
                    'Course' => $course
                ]);
        } else {
            return Helper::errorResponse("Course not found");
        }

    }

    /**
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * @return BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'enrolled_courses',
            'course_id',
            'student_id');
    }

    /**
     * @return BelongsToMany
     */
    public function meetings()
    {
        return $this->belongsToMany(
            Meeting::class,
            'meeting_courses',
            'course_id',
            'meeting_id'
        );
    }

    /**
     * @param $course_name
     * @return array
     */
    public static function addCourse($course_name)
    {
        self::create(
            [
                'course_name' => $course_name,
                'teacher_id' => auth()->user()->id
            ]
        );
        return Helper::successResponse("Course added Successfully");
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function findCourse($id)
    {
        return self::find($id)->first();
    }

    /**
     * @param $id
     * @param $code
     * @return mixed
     */
    public static function updateCode($id, $code)
    {
        return self::findCourse($id)->update(
            [
                'current_meeting_code' => $code
            ]
        );
    }

    /**
     * @param $id
     * @param $meetingId
     */
    public static function addMeeting($id, $meetingId)
    {
        self::find($id)->meetings()->attach($meetingId);

    }
}
