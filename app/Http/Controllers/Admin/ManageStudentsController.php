<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ManageStudentsController extends Controller
{

    /**
     * Display a listing of registered students
     */
    public function index()
    {
        return view('admin.students');
    }

    public function getStudents()
    {
        $students = User::students()->get();
        $data = [];

        foreach ($students as $value) {
            $data[] = [
                'id' => $value->id,
                'name' => $value->name,
                'email' => $value->email,
                'contact_number' => $value->studentDetail->contact_number,
            ];
        }


        return response()->json([
            'data' => $data
        ]);
    }

    public function getStudent(User $user)
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'contact_number' => $user->studentDetail?->contact_number,
            'address' => $user->studentDetail?->address
        ];

        return response()->json($data);
    }
}
