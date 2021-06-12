<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;

class ProfileController extends Controller
{
    public function getProfile()
    {
        if (auth()->user()->role_id == 1) {
            return Teacher::where('id', auth()->user()->role_id)->first();

        } else {
            return Student::where('id', auth()->user()->role_id)->first();

        }

    }
}
