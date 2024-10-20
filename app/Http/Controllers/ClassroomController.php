<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\User;
use App\Notifications\SendInvite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ClassroomController extends Controller
{
    //

    public function index()
    {
        $user = Auth::user();

        $classrooms = Classroom::whereRelation('teacher', 'teacher_id', $user->id)->get();
        $enrollments = Classroom::whereRelation('enrollments', 'student_id', $user->id)->get();

        return view('shared.classroom', compact('classrooms', 'enrollments'));
    }

    public function viewPosts(Classroom $class)
    {
        $students = User::with('studentDetail')
            ->where('user_type', 'Student')
            ->whereDoesntHave('enrollments', function (Builder $query) use ($class) {
                $query->where('classroom_id', $class->id);
            })->get();

        return view('classroom.posts', compact('class', 'students'));
    }

    public function viewLessons(Classroom $class)
    {
        $students = User::with('studentDetail')
            ->where('user_type', 'Student')
            ->whereDoesntHave('enrollments', function (Builder $query) use ($class) {
                $query->where('classroom_id', $class->id);
            })->get();

        return view('classroom.lessons', compact('class', 'students'));
    }

    public function viewExams(Classroom $class)
    {
        $students = User::with('studentDetail')
            ->where('user_type', 'Student')
            ->whereDoesntHave('enrollments', function (Builder $query) use ($class) {
                $query->where('classroom_id', $class->id);
            })->get();

        return view('classroom.exams', compact('class', 'students'));
    }

    public function viewParticipants(Classroom $class)
    {
        $students = User::with('studentDetail')
            ->where('user_type', 'Student')
            ->whereDoesntHave('enrollments', function (Builder $query) use ($class) {
                $query->where('classroom_id', $class->id);
            })->get();

        $enrolled = User::with('enrollments')
            ->where('user_type', 'Student')
            ->whereHas('enrollments', function (Builder $query) use ($class) {
                $query->where('classroom_id', $class->id);
            })->get();

        return view('classroom.participants', compact('class', 'students', 'enrolled'));
    }

    public function create(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string']
        ]);

        $classroom = DB::transaction(function () use ($validated, $user) {
            $validated['code'] = str()->random(6);

            $classroom = $user->classrooms()->create($validated);

            return $classroom;
        });

        if ($classroom) {
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

        $updateClass = DB::transaction(function () use ($validated, $class) {

            $update = $class->update($validated);

            return $update;
        });

        if ($updateClass) {
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
        $deleteClass = DB::transaction(function () use ($class) {

            $delete = $class->delete();

            return $delete;
        });

        if ($deleteClass) {
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
        $enrollment = Enrollment::where('student_id', $user->id)->where('classroom_id', $classroom?->id)->first();

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

        $joins = DB::transaction(function () use ($classroom, $user) {
            $class = Enrollment::make();
            $class->student()->associate($user);
            $class->classroom()->associate($classroom);

            $class->save();

            return $class;
        });

        if ($joins) {
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

        $classroom = DB::transaction(function () use ($enrollment) {
            $opt = $enrollment->delete();

            return $opt;
        });

        if ($classroom) {
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

    public function invite(Request $request, Classroom $class)
    {
        $request->merge(['emails' => json_decode($request->input('emails'), true)]);
        $validated = $request->validate([
            'emails.*' => ['required', 'email']
        ]);

        $users = User::with('studentDetail')
            ->where('user_type', 'Student')
            ->whereDoesntHave('enrollments', function (Builder $query) use ($class) {
                $query->where('classroom_id', $class->id);
            })
            ->whereIn('email', $validated['emails'])
            ->get();



        foreach ($users as $user) {
            $invites = [
                'code' => $class->code,
                'inviteUrl' => URL::signedRoute('classes.email.invite', ['user' => $user, 'code' => $class->code]),
                'name' => $user->name,
                'class' => $class->name
            ];
            $user->notify(new SendInvite($invites));
        }

        return response()->json([
            'success' => true,
            'message' => 'Invites sent to all students selected.'
        ]);
    }

    public function inviteLink($code, $user)
    {
        $classroom = Classroom::where('code', $code)->first();
        $enrollment = Enrollment::where('student_id', $user)->where('classroom_id', $classroom?->id)->first();

        if (!$classroom) {
            return response()->json([
                'message' => 'No class found with the provided code.'
            ]);
        } else {
            if ($enrollment) {
                return response()->json([
                    'message' => 'You cannot join the class because you are already enrolled in it.'
                ]);
            }
        }

        $joins = DB::transaction(function () use ($classroom, $user) {
            $class = Enrollment::make();
            $class->student()->associate($user);
            $class->classroom()->associate($classroom);

            $class->save();

            return $class;
        });

        if ($joins) {
            return to_route('classes');
        } else {
            return response()->json([
                'error' => 'Something went wrong. Please try again later.'
            ]);
        }
    }

    public function kickStudent(Classroom $class, User $student)
    {
        $enrollment = $student->enrollments()->where('classroom_id', $class->id)->first();

        $kick = DB::transaction(function () use ($enrollment) {
            $status = $enrollment->delete();

            return $status;
        });

        if ($kick) {
            return response()->json([
                'success' => true,
                'message' => 'Student has been kicked.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }
}
