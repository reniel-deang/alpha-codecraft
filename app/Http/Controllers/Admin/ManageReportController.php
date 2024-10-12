<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManageReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.reported-posts');
    }

    public function getReportedPosts()
    {
        $reported = CommunityPost::whereHas('reports')->get();
        $result = [
            'data' => []
        ];
        foreach ($reported as $report) {
            $result['data'][] = [
                'id' => $report->id,
                'user' => $report->author->name,
                'post' => $report->title,
                'report_count' => $report->reports()->count()
            ];
        }

        return response()->json($result);
    }

    public function getReportedPost(CommunityPost $post)
    {
        $attachments = $post->communityPostAttachments()->get();

        $result = [
            'user' => $post->author?->name,
            'title' => $post->title,
            'content' => $post->content,
            'attachments' => []
        ];

        foreach ($attachments as $file) {
            array_push($result['attachments'], $file->path);
        }

        return response()->json($result);
    }

    public function removePost(CommunityPost $post)
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

}
