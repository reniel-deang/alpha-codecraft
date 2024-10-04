<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\TemporaryUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityPostController extends Controller
{
    //

    public function index()
    {
        $fileName = 'temporary-'.Carbon::now()->format('Y-m-d-h-i-s');
        $communityPosts = CommunityPost::with('author')->latest()->get();
        return view('shared.community', compact('communityPosts', 'fileName'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);
        $user = $request->user();

        $post = DB::transaction(function() use ($user, $validated, $request) {
            $communityPost = $user->communityPosts()->create($validated);

            $temp = TemporaryUpload::where('user_id', $user->id)->where('name', $request->input('name'))->get();

            if ($temp) {       
                foreach ($temp as $file) {
                    $communityPost->communityPostAttachments()->create([
                        'original_name' => $file->original_name,
                        'name' => $file->name,
                        'path' => $file->path
                    ]);
                    $file->delete();
                }
            }

            return $communityPost;
        });

        if($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post successfully created.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function comment(Request $request, CommunityPost $post)
    {
        $validated = $request->validate([
            'content' => ['required', 'string']
        ]);
        $user = $request->user();

        $comment = DB::transaction(function() use ($post, $user, $validated) {
            $content = $post->comments()->make($validated);
            $content->author()->associate($user);

            $content->save();
            return $content;
        });

        if($comment) {
            return response()->json([
                'success' => true,
                'message' => 'Comment successfully posted.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function tempImgUpload(Request $request)
    {
        $user = $request->user();
        $name = $request->input('name');
        $original = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store('uploads', 'public');

        $user->temporaryUploads()->create([
            'original_name' => $original,
            'name' => $name,
            'path' => $path
        ]);

        return response()->json(['success' => 'Files uploaded successfully']);
    }

    public function tempImgDelete(Request $request)
    {
        dd($request->all());
    }
}
