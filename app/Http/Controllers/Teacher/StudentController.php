<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\TextUI\Help;

class StudentController extends Controller
{
    public function index(Request $request)
    {
//        $per_page=$request->input('per_page')?$request->input('per_page'):env('DEFAULT_PER_PAGE');
//        return Teacher::getAllStudents($per_page);
        return Teacher::find(auth()->user()->id)->students;
    }

    public function add(Request $request)
    {
        $this->validate($request,[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users,email',
            'gender'=>'required',
        ]);
        try {
            $student=new Student();
            $student->fill($request->all())->save();
            $role_id = Student::latest('id')->first()->id;
            Teacher::find(auth()->user()->id)->students()->attach($role_id);
            $user=new User();
            $user->first_name=$request->input('first_name');
            $user->last_name=$request->input('last_name');
            $user->password=Hash::make('defaultpass');
            $user->email=$request->input('email');

            $user->role_type=2;
            $user->role_id=$role_id;
            $user->save();
            return response([
                'message'=>'Student saved successfully',
                'student'=>$student
            ]);
        }
        catch (\Exception $e)
        {
            return Helper::errorResponse('Something bad happened');
        }
    }
    public function update(Request $request)
    {
        $student=Student::where($request->input('id'));
        $student->update($request->all());
        return "hello";
        $user=User::where('role_type','=',2)->where('id','2');
        $user->update($request->all());
        return Helper::successResponse("Student Updated Successfully");
    }
}
