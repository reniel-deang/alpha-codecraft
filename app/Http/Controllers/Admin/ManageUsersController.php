<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\BanNotice;
use App\Notifications\DeleteNotice;
use App\Notifications\SendWarning;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['studentDetail', 'teacherDetail'])->whereNot('user_type', 'Admin')->get();
        
        return view('admin.user-management', compact('users'));
    }

    /**
     * sendwarning.
     */
    public function sendWarning(Request $request, User $user)
    {
        $data = $request->validate([
            'message' => 'required'
        ]);

        $message = [
            'user' => $user->name,
            'message' => $data['message']
        ];

        $user->notify(new SendWarning($message));

        return response()->json([
            'success' => true,
            'message' => 'Warning sent.'
        ]);
    }

    public function banUser(Request $request, User $user)
    {
        $data = $request->validate([
            'ban_effective' => 'required',
            'message' => 'required'
        ]);

        $message = [
            'user' => $user->name,
            'message' => $data['message'],
            'date_effective' => $data['ban_effective']
        ];

        $notice = DB::transaction(function() use($message, $user) {
            $update = $user->update([
                'ban_effective' => $message['date_effective']
            ]);
            $user->notify(new BanNotice($message));

            return $update;
        });

        if ($notice) {
            return response()->json([
                'success' => true,
                'message' => 'User has been banned.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function deleteUser(Request $request, User $user)
    {
        $data = $request->validate([
            'message' => 'required'
        ]);

        $message = [
            'user' => $user->name,
            'message' => $data['message']
        ];

        $notice = DB::transaction(function() use ($user, $message) {

            $posts = $user->communityPosts()->get();

            if ($posts) {
                foreach ($posts as $post) {
                    $attachments = $post->communityPostAttachments()->get();

                    if ($attachments) {
                        foreach ($attachments as $attachment) {
                            Storage::disk('public')->delete($attachment->path);
                        }
                    }
                }
            }
            
            $action = $user->delete();

            $user->notify(new DeleteNotice($message));

            return $action;
        });

        if ($notice) {
            return response()->json([
                'success' => true,
                'message' => 'User has been deleted.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }

    }
}
