<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\CommunityPostAttachment;
use App\Models\CommunityPostComment;
use App\Models\ReportPost;
use App\Models\TemporaryDelete;
use App\Models\TemporaryUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommunityPostController extends Controller
{
    //

    public function index()
    {
        $fileName = 'temporary-' . Carbon::now()->format('Y-m-d-h-i-s');
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

        $post = DB::transaction(function () use ($user, $validated, $request) {
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

        if ($post) {
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

    public function update(Request $request, CommunityPost $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);
        $user = $request->user();

        $post = DB::transaction(function () use ($user, $validated, $post, $request) {
            $communityPost = $post->update($validated);

            $deletes = TemporaryDelete::where('community_post_id', $post->id)->get();
            $temp = TemporaryUpload::where('user_id', $user->id)->where('name', $request->input('name'))->get();

            if ($temp) {
                foreach ($temp as $file) {
                    $post->communityPostAttachments()->create([
                        'original_name' => $file->original_name,
                        'name' => $file->name,
                        'path' => $file->path
                    ]);
                    $file->delete();
                }
            }

            if ($deletes) {
                foreach ($deletes as $delete) {
                    if (Storage::disk('public')->exists($delete->path)) {
                        Storage::disk('public')->delete($delete->path);
                        $post->communityPostAttachments()->where('id', $delete->community_post_attachment_id)->delete();
                        $delete->delete();
                    }
                }
            }

            return $communityPost;
        });

        if ($post) {
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

    public function delete(Request $request, CommunityPost $post)
    {
        $deletePost = DB::transaction(function () use ($post) {
            $files = $post->communityPostAttachments()->get();

            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
            }

            $delete = $post->delete();

            return $delete;
        });

        if ($deletePost) {
            return response()->json([
                'success' => true,
                'message' => 'Post successfully deleted.'
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
        if ($request->input('comment')) {
            $comment = CommunityPostComment::findOrFail($request->input('comment'));

            $validated = $request->validate([
                'content' => ['required', 'string']
            ]);

            $update = DB::transaction(function () use ($comment, $validated) {
                $content = $comment->update($validated);
                return $content;
            });
    
            if ($update) {
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
        } else {
            $validated = $request->validate([
                'content' => ['required', 'string']
            ]);
            $user = $request->user();
    
            $comment = DB::transaction(function () use ($post, $user, $validated) {
                $content = $post->comments()->make($validated);
                $content->author()->associate($user);
    
                $content->save();
                return $content;
            });
    
            if ($comment) {
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
    }

    public function report(Request $request, CommunityPost $post)
    {
        $user = $request->user();

        $report = DB::transaction(function () use ($user, $post) {
            $reportPost = new ReportPost();

            $reportPost->reporter()->associate($user);
            $reportPost->communityPost()->associate($post);

            $reportPost->save();

            return $reportPost;
        });

        if ($report) {
            return response()->json([
                'success' => true,
                'message' => 'Post successfully reported.'
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
        $name = $request->input('name');

        $temporary = TemporaryUpload::where('original_name', $name)->first();

        if ($temporary) {
            if (Storage::disk('public')->exists($temporary->path)) {
                Storage::disk('public')->delete($temporary->path);
                $temporary->delete();
            }
        } else {
            $edit = CommunityPostAttachment::where('original_name', $name)->first();
            $post = $edit->communityPost;

            if ($edit) {
                $tempDelete = $edit->temporaryDelete()->make([
                    'original_name' => $edit->original_name,
                    'name' => $edit->name,
                    'path' => $edit->path
                ]);
                $tempDelete->communityPost()->associate($post);
                $tempDelete->save();
            }
        }
        return response()->json(['success' => 'Files removed successfully']);
    }

    public function deleteComment(CommunityPost $post, CommunityPostComment $comment)
    {
        $deleteComment = DB::transaction(function () use ($comment) {
            $delete = $comment->delete();

            return $delete;
        });

        if ($deleteComment) {
            return response()->json([
                'success' => true,
                'message' => 'Comment successfully deleted.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function getAttachments(CommunityPost $post)
    {
        $attachments = $post->communityPostAttachments()->get();
        $data = [];
        if ($attachments) {
            foreach ($attachments as $attachment) {
                array_push($data, $attachment->path);
            }
        }

        return response()->json($data);
    }
}
