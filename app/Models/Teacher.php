<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table='teachers';
    protected $fillable=[
        'last_name','first_name'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class,'teachers_students','teacher_id','student_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public static function getAllStudents($perPage)
    {
        $data=self::find(auth()->user()->id)->students()->paginate($perPage);
        return $data;
    }
}
