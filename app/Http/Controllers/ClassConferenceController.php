<?php

namespace App\Http\Controllers;

use App\Models\ClassConference;
use App\Models\Classroom;
use App\Services\JitsiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassConferenceController extends Controller
{
    protected $jitsiService;

    public function __construct(JitsiService $jitsiService)
    {
        $this->jitsiService = $jitsiService;
    }

    public function startMeeting(Request $request, Classroom $class, ClassConference $conference)
    {
        $user = $request->user();

        //$jwt = $this->jitsiService->generateToken($user, $user->user_type, $room);

        return view('classroom.meet', compact('user', 'conference', 'class'));
    }

    /**
     * create new meeting.
     */
    public function createMeeting(Request $request, Classroom $class)
    {
        $title = $request->validate([
            'conference_name' => ['required', 'string']
        ]);

        $meet = DB::transaction(function() use ($title, $class) {
            $conf = ClassConference::make($title);
            $conf->classroom()->associate($class);
            $conf->teacher()->associate($class->teacher);
            $conf->save();

            return $conf;
        });

        if($meet) {
            return response()->json([
                'success' => true,
                'message' => 'Class meeting successfully updated.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

}
