<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'verifiedTeacherCount' => User::teachers(true)->count(),
            'unverifiedTeacherCount' => User::teachers(false)->count(),
            'totalStudentCount' => User::students()->count()
        ];

        return view('admin.dashboard', compact('data'));
    }
}
