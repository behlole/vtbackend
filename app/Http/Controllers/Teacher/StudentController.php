<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return Teacher::find(auth()->user()->role_id)->students;
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required',
        ]);
        try {
            $student = new Student();
            $student->fill($request->all())->save();
            $role_id = Student::latest('id')->first()->id;
            Teacher::find(auth()->user()->role_id)->students()->attach($role_id);
            $user = new User();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->password = Hash::make('defaultpass');
            $user->email = $request->input('email');

            $user->role_type = 2;
            $user->role_id = $role_id;
            $user->save();
            return response([
                'message' => 'Student saved successfully',
                'student' => $student
            ]);
        } catch (Exception $e) {
            return Helper::errorResponse('Please try to change email ,Something bad happened');
        }
    }

    public function update(Request $request)
    {
        try {
            $student = Student::find($request->input('id'));
            $student->update($request->all());

            $user = User::where('role_type', '=', 2)->where('role_id', '=', $request->input('id'));
            $user->update(
                [
                    'email' => $request->input('email'),
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name')
                ]
            );
            return Helper::successResponse("Student Updated Successfully");
        } catch (Exception $e) {
            return Helper::errorResponse("Please change email,Something bad happened \n Could not update, please try again");
        }

    }

    public function delete($id)
    {
//        Student::find($id)->delete();
        Teacher::find(auth()->user()->role_id)->students()->detach($id);
        return Helper::successResponse("Student Deleted Successfully");
    }
    public function getAll()
    {
        return Student::all();
    }
    public static function getStudent($id)
    {
        return User::join('students','users.role_id','=','students.id')->where('users.role_id',$id)->first();
    }

    public static function addFromAll($id)
    {
        if(!Teacher::find(auth()->user()->role_id)->students()->where('student_id', $id)->exists()) {
            Teacher::find(auth()->user()->role_id)->students()->attach($id);
            return Helper::successResponse("Student successfully added");
        }
        else
        {
            return Helper::errorResponse("Student already enrolled");

        }
    }
}
