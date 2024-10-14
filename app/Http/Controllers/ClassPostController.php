<?php

namespace App\Http\Controllers;

use App\Models\ClassPost;
use App\Models\Classroom;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassPostController extends Controller
{
    //
    public function post(Request $request, Classroom $class)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string']
        ]);
        $user = $request->user();

        $post = DB::transaction(function() use ($class, $user, $validated) {
            $content = ClassPost::make($validated);
            $content->classroom()->associate($class);
            $content->author()->associate($user);

            $content->save();
            return $content;
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

    public function update(Request $request, Classroom $class, ClassPost $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string']
        ]);

        $postUpdate = DB::transaction(function() use ($post, $validated) {
            $update = $post->update($validated);

            return $update;
        });

        if($postUpdate) {
            return response()->json([
                'success' => true,
                'message' => 'Post successfully updated.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function delete(Classroom $class, ClassPost $post)
    {
        $deletePost = DB::transaction(function() use ($post) {

            $delete = $post->delete();

            return $delete;
        });

        if($deletePost) {
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

    public function comment(Request $request, Classroom $class, ClassPost $post)
    {
        if ($request->input('comment')) {
            $comment = Comment::findOrFail($request->input('comment'));
            
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

    public function deleteComment(Classroom $class, ClassPost $post, Comment $comment)
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
}
