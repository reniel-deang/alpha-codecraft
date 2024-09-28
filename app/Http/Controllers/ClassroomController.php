<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    //

    public function index()
    {
        $classrooms = Classroom::with('teacher')->whereRelation('teacher', 'teacher_id', request()->user()->id)->get();
        $enrollments = User::with('enrollments')->get();
        return view('shared.classroom', compact('classrooms', 'enrollments'));
    }

    public function create(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string']
        ]);

        $classroom = DB::transaction(function() use ($validated, $user) {

            $classroom = $user->classrooms()->create($validated);

            return $classroom;
        });

        if($classroom) {
            return response()->json([
                'success' => true,
                'message' => 'Classroom successfully created.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }
}
