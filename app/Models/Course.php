<?php

namespace App\Models;

use App\Helpers\Helper;
use Faker\Provider\File;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table='courses';
    protected $guarded=[];
    /**
     * @var mixed
     */

    public static function deleteCourse($id)
    {
        if($course=self::find($id))
        {
            $course->delete();
            return $course;
        }

    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class,'enrolled_courses','course_id','student_id');
    }
}
