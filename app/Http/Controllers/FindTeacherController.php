<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FindTeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('user_type', 'Teacher')
        ->whereHas('teacherDetail', function(Builder $query) {
            $query->where('is_verified', TRUE);
        })
        ->get();

        return view('student.find-teacher', compact('teachers'));
    }
}
