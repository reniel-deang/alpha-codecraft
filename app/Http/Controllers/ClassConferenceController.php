<?php

namespace App\Http\Controllers;

use App\Models\ClassConference;
use App\Models\Classroom;
use App\Models\User;
use Firebase\JWT\JWT;
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

        return view('classroom.meet', compact('token', 'conference', 'class'));
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
                'message' => 'Class meeting successfully created.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function calculateTime(Request $request, User $user)
    {
        dd($request->all());
    }

    public function generateToken($room, $user, $mod) 
    {
        $appId = 'jitsi_class_app_12d93f6e';
        $appSecret = 'b4a5f784d7b8f9e12c6a58a3b9d12345a9e8f7a6c5b4d3a2f1e8d7c9b6a5e4f3';
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
