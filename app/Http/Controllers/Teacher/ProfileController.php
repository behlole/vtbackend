<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;

class ProfileController extends Controller
{
    public function getProfile()
    {
        return Teacher::find(auth()->user()->role_id)->first();
    }
}
