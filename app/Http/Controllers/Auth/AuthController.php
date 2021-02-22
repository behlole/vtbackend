<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    protected function jwt(User $user)
    {
        $token = JWTAuth::fromUser($user);
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'email' => 'required | email | unique:users',
            'password' => 'required | confirmed',
            'role_type' => 'required | integer',
        ]);
        try {
            //role 1 for teacher
            //role 2 for student
            //role 3 for superadmin
            $role_id = 0;
            if ($request->input('role_type') == 1) {
                $teacher = new Teacher();
                $teacher->first_name=$request->input('first_name');
                $teacher->last_name=$request->input('last_name');
                $teacher->save();
                $role_id = Teacher::latest('id')->first();
                $role_id = $role_id->id;

            } elseif ($request->input('role_type') == 2) {
                $student = new Student();
                $student->first_name=$request->input('first_name');
                $student->last_name=$request->input('last_name');
                $student->save();
                $role_id = Student::latest('id')->first();
                $role_id = $role_id->id;

            } else {
                return Helper::errorResponse("Invalid Role Type ");
            }
            $user = new User();
            $user->first_name=$request->input('first_name');
            $user->last_name=$request->input('last_name');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->role_type = $request->input('role_type');
            $user->role_id = $role_id;
            $user->save();
            $credentials['email'] = $user->email;
            $credentials['password'] = $request->input('password');
            return $this->authenticate($credentials);
        } catch (\Exception $e) {
            return Helper::errorResponse("User Registration failed");
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only(['email', 'password']);
        return $this->authenticate($credentials);
    }

    public function authenticate($cridentials)
    {
        if (!$token = Auth::attempt($cridentials)) {
            return response()->json(
                [
                    'message' => 'UnAuthorized',
                ], 401
            );
        }
        return $this->respondWithToken($token);
    }

    function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
