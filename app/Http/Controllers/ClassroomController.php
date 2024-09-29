<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    //

    public function index()
    {
        $classrooms = Classroom::with('teacher')->whereRelation('teacher', 'teacher_id', request()->user()->id)->get();
        $enrollments = Classroom::with('enrollments')->whereRelation('enrollments', 'student_id', request()->user()->id)->get();
        return view('shared.classroom', compact('classrooms', 'enrollments'));
    }

    public function view(Classroom $class)
    {
        $students = User::where('user_type', 'Student')->get();
        return view('classroom.classroom', compact('class', 'students'));
    }

    public function create(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string']
        ]);

        $classroom = DB::transaction(function() use ($validated, $user) {
            $validated['code'] = str()->random(6);

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

    public function update(Request $request, Classroom $class)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string']
        ]);

        $updateClass = DB::transaction(function() use ($validated, $class) {

            $update = $class->update($validated);

            return $update;
        });

        if($updateClass) {
            return response()->json([
                'success' => true,
                'message' => 'Classroom successfully updated.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function delete(Classroom $class)
    {
        $deleteClass = DB::transaction(function() use ($class) {

            $delete = $class->delete();

            return $delete;
        });

        if($deleteClass) {
            return response()->json([
                'success' => true,
                'message' => 'Classroom successfully deleted.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function join(Request $request, User $user)
    {
        $code = $request->validate([
            'code' => ['required', 'string', 'max:6']
        ]);

        $classroom = Classroom::where('code', $code)->first();
        $enrollment = Enrollment::where('classroom_id', $classroom?->id)->first();

        if (!$classroom) {
            return response()->json([
                'success' => false,
                'message' => 'No class found with the provided code.'
            ]);
        } else {
            if ($enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot join the class because you are already enrolled in it.'
                ]);
            }
        }

        $joins = DB::transaction(function() use ($classroom, $user) {
            $class = Enrollment::make();
            $class->student()->associate($user);
            $class->classroom()->associate($classroom);

            $class->save();

            return $class;
        });

        if($joins) {
            return response()->json([
                'success' => true,
                'message' => 'You have joined the classroom.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function leave(Classroom $class)
    {
        $student = request()->user();
        
        $enrollment = Enrollment::with('student')
            ->whereRelation('student', 'student_id', $student->id)
            ->whereRelation('classroom', 'classroom_id', $class->id)
            ->first();

        $classroom = DB::transaction(function() use ($enrollment) {
            $opt = $enrollment->delete();

            return $opt;
        });

        if($classroom) {
            return response()->json([
                'success' => true,
                'message' => 'You have left the classroom.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function invite(Request $request)
    {
        dd($request->all());
    }
}
