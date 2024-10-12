<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CommunityPost;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File;

class ProfileController extends Controller
{
    //
    public function index(User $user)
    {
        return view('shared.profile', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'address' => ['required', 'string'],
            'contact_number' => ['required', 'numeric'],
            'bio' => ['sometimes', 'nullable', 'string'],
            'avatar' => File::image()->max(1024 * 2)
        ]);
        $userType = $user->user_type;

        $process = DB::transaction(function () use ($user, $userType, $request, $validated) {
            if ($userType === 'Teacher') {
                $teacherDetail = [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'address' => $validated['address'],
                    'contact_number' => $validated['contact_number'],
                    'bio' => $validated['bio']
                ];

                if (isset($validated['avatar'])) {
                    $avatar = $request->file('avatar')->getClientOriginalName();
                    $image = $request->file('avatar')->storeAs('users-avatar', $avatar, 'public');

                    $teacherAccount = [
                        'email' => $validated['email'],
                        'avatar' => $avatar,
                        'name' => "{$validated['first_name']} {$validated['last_name']}"
                    ];
                } else {
                    $teacherAccount = [
                        'email' => $validated['email'],
                        'name' => "{$validated['first_name']} {$validated['last_name']}"
                    ];
                }

                $user->update($teacherAccount);
                $user->teacherDetail()->update($teacherDetail);

                return true;
            } else if ($userType === 'Student') {
                $studentDetail = [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'address' => $validated['address'],
                    'contact_number' => $validated['contact_number'],
                    'bio' => $validated['bio']
                ];

                if (isset($validated['avatar'])) {
                    $avatar = $request->file('avatar')->getClientOriginalName();
                    $image = $request->file('avatar')->storeAs('users-avatar', $avatar, 'public');

                    $studentAccount = [
                        'email' => $validated['email'],
                        'avatar' => $avatar,
                        'name' => "{$validated['first_name']} {$validated['last_name']}"
                    ];
                } else {
                    $studentAccount = [
                        'email' => $validated['email'],
                        'name' => "{$validated['first_name']} {$validated['last_name']}"
                    ];
                }

                $user->update($studentAccount);
                $user->studentDetail()->update($studentDetail);
                return true;
            }
        });

        if ($process) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function certificates(User $user)
    {
        $certificates = $user->certificates()->get();
        return view('student.certificates', compact('user', 'certificates'));
    }

    public function viewCertificate(User $user, Certificate $certificate)
    {
        return view('student.certificate', compact('user', 'certificate'));
    }

    public function viewMoments(User $user)
    {
        $fileName = 'temporary-' . Carbon::now()->format('Y-m-d-h-i-s');
        $communityPosts = CommunityPost::with('author')->where('author_id', $user->id)->get();
        return view('student.posts', compact('communityPosts', 'fileName'));
    }

    public function setSchedule(Request $request, User $user)
    {
        $schedules = $request->validate([
            'schedules' => ['required']
        ]);

        if ($user->user_type === 'Teacher') {
            $update = $user->teacherDetail()->update([
                'schedules' => $schedules['schedules']
            ]);
        }

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Schedules updated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }
}
