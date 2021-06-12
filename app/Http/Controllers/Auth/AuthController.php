<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\UserActions;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Swift_TransportException;


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
            'password' => 'required',
            'role_type' => 'required | integer',
            'phone_number' => 'required',
            'gender' => 'string',
            'department' => 'required',
            'city' => 'required'
        ]);
//        try {
        //role 1 for teacher
        //role 2 for student
        //role 3 for superadmin
        $role_id = 0;
        if ($request->input('role_type') == 1) {
            $teacher = new Teacher();
            $teacher->fill($request->all())->save();
            $teacher->save();
            $role_id = Teacher::latest('id')->first();
            $role_id = $role_id->id;

        } elseif ($request->input('role_type') == 2) {
            $student = new Student();
            $student->fill($request->all())->save();
            $role_id = Student::latest('id')->first();
            $role_id = $role_id->id;

        } else {
            return Helper::errorResponse("Invalid Role Type ");
        }
        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = app('hash')->make($request->input('password'));
        $user->role_type = $request->input('role_type');
        $user->role_id = $role_id;

        $user->save();
        $credentials['email'] = $user->email;
        $credentials['password'] = $request->input('password');
        return $this->authenticate($credentials);
//        } catch (\Exception $e) {
//            return Helper::errorResponse("User Registration failed");
//        }
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

    function getUser()
    {
        if (\auth()->guard('api')->user()->role_type == 1) {
            return Teacher::where('id', \auth()->guard('api')->user()->role_id)->first();
        } else {
            return Student::where('id', \auth()->guard('api')->user()->role_id)->first();

        }
    }

    function saveUser(Request $request, $role_type)
    {
        try {

            if ($role_type == 1) {
                Teacher::where('id', $request->id)->update(
                    [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'department' => $request->department,
                        'gender' => $request->gender,
                        'phone_number' => $request->phone_number,
                        'date_of_birth' => Carbon::parse($request->date_of_birth)
                    ]
                );

                User::where('role_type', $role_type)->where('id', $request->id)->update([
                    'email' => $request->email,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ]);

                return response()->json([
                    'message' => 'Data updated successfully',
                ], 200);
            } else {
                Student::where('id', $request->id)->update(
                    [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'department' => $request->department,
                        'gender' => $request->gender,
                        'phone_number' => $request->phone_number,
                        'date_of_birth' => Carbon::parse($request->date_of_birth)
                    ]
                );

                User::where('role_type', $role_type)->where('id', $request->id)->update([
                    'email' => $request->email,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ]);

                return response()->json([
                    'message' => 'Data updated successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 405);
        }
    }

    public function changePassword(Request $request)
    {
        try {

            $user = User::find(\auth()->guard('api')->user()->id);
            $user->password = app('hash')->make($request->input('new_password'));
            $user->save();

            return response()->json([
                'message' => 'Password Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something bad Happened, Please try Again'
            ]);
        }
    }

    public function sendMail()
    {
        $sender_email = "behloleaqil@gmail.com";
        $receiver_email = "behloleaqil@gmail.com";

        Mail::raw(UserActions::class, function($message) use ($sender_email, $receiver_email) {
            $message->from($sender_email, config('app.mail'));
            $message->to($receiver_email)->subject("Email subject");
        });
        return "mail send";
    }
}
