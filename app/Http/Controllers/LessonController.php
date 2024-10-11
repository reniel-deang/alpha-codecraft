<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\LessonSection;
use App\Models\Progress;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function save(Request $request, Classroom $class)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'sections' => ['required', 'numeric'],
            'description' => ['required', 'string']
        ]);

        $lesson = DB::transaction(function () use ($class, $validated) {
            $content = $class->lessons()->create($validated);
            return $content;
        });

        if ($lesson) {
            return response()->json([
                'success' => true,
                'message' => 'Lesson successfully created.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function addSection(Request $request, Classroom $class, Lesson $lesson)
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['required', 'string']
        ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $clean = $purifier->purify($data['content']);

        $validated = [
            'title' => $data['title'],
            'content' => $clean
        ];

        if ($lesson->sections()->count() <= $lesson->sections) {
            $section = DB::transaction(function () use ($lesson, $validated) {
                $content = $lesson->sections()->create($validated);

                return $content;
            });

            if ($section) {
                return response()->json([
                    'success' => true,
                    'message' => 'Section successfully added to lesson.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Oops! Something went wrong.'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Max section count reached! Cannot add anymore.'
            ]);
        }
    }

    public function viewLesson(Request $request, Classroom $class, Lesson $lesson)
    {
        $user = $request->user();
        $userProgress = Progress::where('student_id', $user->id)->where('lesson_id', $lesson->id)->first();

        if ($user->user_type === 'Student') {
            if (!$userProgress) {
                $progress = Progress::make([
                    'completed_sections' => 0,
                    'completed_sections_id' => []
                ]);

                $progress->lesson()->associate($lesson);
                $progress->student()->associate($user);
                $progress->save();
            }

            return view('classroom.lesson-view', compact('lesson', 'class', 'user'));
        } else {
            return view('classroom.lesson-view', compact('lesson', 'class', 'user'));
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function publishLesson(Classroom $class, Lesson $lesson)
    {
        $action = DB::transaction(function() use ($lesson) {
            $published = $lesson->update([
                'status' => 'published'
            ]);

            return $published;
        });

        if ($action) {
            return response()->json([
                'success' => true,
                'message' => 'Lesson successfully published.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function markAsDone(Request $request, Classroom $class, Lesson $lesson, LessonSection $section)
    {
        $user = $request->user();
        $progress = $lesson->progress()->where('student_id', $user->id)->first();

        if ($progress) {
            $action = DB::transaction(function() use ($user, $lesson, $section, $progress) {
                $completedSectionsId = $progress->completed_sections_id;
                array_push($completedSectionsId, $section->id);
                $completedSections = $progress->completed_sections;
                $progress->update([
                    'completed_sections' => $completedSections + 1,
                    'completed_sections_id' => $completedSectionsId
                ]);

                if ($progress->completed_sections === $lesson->sections) {
                    $certificate = $user->certificates()->make();
                    $certificate->lesson()->associate($lesson);
                    $certificate->save();
                }

                if ($progress && isset($certificate)) {
                    return true;
                }
                return $progress;
            });
        } else {
            $action = DB::transaction(function() use ($user, $lesson, $section) {
                $sectionId = [];
                array_push($sectionId, $section->id);

                $progress = Progress::make([
                    'completed_sections' => 1,
                    'completed_sections_id' => $sectionId
                ]);
                $progress->lesson()->associate($lesson);
                $progress->student()->associate($user);

                $progress->save();

                return $progress;
            });
        }

        return redirect()->back();
    }
}
