<?php

namespace App\Http\Controllers;

use App\Models\ClassConference;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassConferenceController extends Controller
{

    public function startMeeting(Request $request, Classroom $class, ClassConference $conference)
    {
        $user = $request->user();
        $role = '';
        $room = $conference->conference_name;

        if ($user->user_type === 'Teacher') {
            $role = 'owner';
        } else if ($user->user_type === 'Student') {
            $role = 'member';
        }

        $token = $this->generateToken($room, $user, $role);

        return view('classroom.meet', compact('token', 'conference', 'class', 'user'));
    }

    /**
     * create new meeting.
     */
    public function createMeeting(Request $request, Classroom $class)
    {
        $title = $request->validate([
            'conference_name' => ['required', 'string']
        ]);

        $meet = DB::transaction(function () use ($title, $class) {
            $conf = ClassConference::make($title);
            $conf->classroom()->associate($class);
            $conf->teacher()->associate($class->teacher);
            $conf->save();

            return $conf;
        });

        if ($meet) {
            return response()->json([
                'success' => true,
                'message' => 'Class meeting successfully created.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function calculateTime(Request $request, Classroom $class, ClassConference $conference, User $user)
    {
        $timeJoined = Carbon::parse($request->input('time_joined'));
        $timeLeft = Carbon::parse($request->input('time_left'));

        $total = $timeJoined->diffInHours($timeLeft);

        if ($user->user_type === 'Student') {
            $enrollment = Enrollment::with('student')
                ->where('student_id', $user->id)
                ->where('classroom_id', $class->id)->first();

            $update = DB::transaction(function () use ($user, $total, $enrollment) {
                $totalTalktime = $user->studentDetail?->talktime;
                $enrollmentTalktime = $enrollment->talktime;

                $update = $user->studentDetail()->update([
                    'talktime' => $totalTalktime + $total
                ]);
                
                $enrollmentUpdate = $enrollment->update([
                    'talktime' => $enrollmentTalktime + $total
                ]);

                if ($update && $enrollmentUpdate) {
                    return true;
                } else {
                    return false;
                }
            });

            if ($update) {
                return response()->json([
                    'success' => true,
                    'message' => 'Talktime added.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Oops! Something went wrong.'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'You are a teacher. Talktime not needed.'
        ]);
    }

    public function generateToken($room, $user, $mod)
    {
        $appId = env('JITSI_APP_ID');
        $appSecret = env('JITSI_APP_SECRET');
        $domain = 'webapi.codecraftmeet.online';

        $payload = [
            'aud' => $appId,
            'iss' => $appId,
            'sub' => $domain,
            'room' => $room,
            'exp' => time() + 3600,
            'context' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => asset("storage/users-avatar/{$user->avatar}"),
                    'affiliation' => $mod
                ]
            ]
        ];

        $token = JWT::encode($payload, $appSecret, 'HS256');

        return $token;
    }
}
