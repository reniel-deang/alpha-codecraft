<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NotifyTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ManageTeachersController extends Controller
{
    /**
     * Display a listing of verified teachers.
     */
    public function verifiedTeachers()
    {
        return view('admin.verified-teachers');
    }

    /**
     * Display a listing of verified teachers.
     */
    public function unverifiedTeachers()
    {
        return view('admin.unverified-teachers');
    }

    /**
     * request for teachers list 
     * to display in datatable
     */
    public function teachersList($condition)
    {
        $results = User::teachers($condition)->get();
        $data = [];

        foreach ($results as $result) {
            $data[] = [
                'id' => $result->id,
                'name' => $result->name,
                'email' => $result->email,
                'contact_number' => $result->teacherDetail->contact_number,
                'file' => str_replace('teachers-attachments/', '', $result->teacherDetail->file)
            ];
        }
        
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Display the file on another tab.
     */
    public function viewFile($file)
    {
        // Check if the file exists
        if (!Storage::disk('teacher-id')->exists($file)) {
            abort(404);
        }

        return response()->file(Storage::disk('teacher-id')->path($file));
    }

    /**
     * Do teacher verification base on action
     * passed as post data
     */
    public function verifyTeacher(Request $request)
    {
        $data = $request->all();

        $teacher = User::findOrFail($data['id']);

        if($data['action'] === 'Approve') {
            $content = [
                'message' => 'Your application has been approved. Click on the button below to login with your account.',
                'url' => url('/login'),
                'action' => 'Login'
            ];

            $teacher->notify(new NotifyTeacher($content));

            $teacher->teacherDetail->update([
                'is_verified' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Teacher has been approved.'
            ]);

        } else {
            $content = [
                'message' => "Your application has been rejected. Reason being: {$data['reason']}. You can register again just click on the button below.",
                'url' => url('/register'),
                'action' => 'Login'
            ];
            $teacher->notify(new NotifyTeacher($content));

            Storage::disk('teacher-id')->delete($teacher->teacherDetail->file);
            
            $teacher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Teacher has been rejected.'
            ]);
        }

        
    }
}
