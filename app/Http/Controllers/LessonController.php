<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\ExamScore;
use App\Models\Lesson;
use App\Models\LessonSection;
use App\Models\Progress;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Class_;

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
                    'message' => "Lesson successfully added to {$lesson->title}."
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
                'message' => 'Max lesson count reached! Cannot add anymore.'
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

    public function createExam(Request $request, Classroom $class, Lesson $lesson)
    {
        $user = $request->user();
        return view('classroom.exams-create', compact('class', 'lesson', 'user'));
    }

    public function saveExam(Request $request, Classroom $class, Lesson $lesson)
    {
        $data = $request->validate([
            'questions.*' => ['string'],
            'time_limit' => ['required', 'numeric']
        ]);

        $action = DB::transaction(function () use ($data, $lesson) {
            $exam = $lesson->exam()->create([
                'time_limit' => $data['time_limit']
            ]);

            foreach ($data['questions'] as $question) {
                $exam->examQuestions()->create([
                    'question' => $question
                ]);
            }

            return $exam;
        });

        if ($action) {
            return response()->json([
                'success' => true,
                'message' => 'Exam successfully saved.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function takeExam(Request $request, Classroom $class, Lesson $lesson, Exam $exam)
    {
        $user = $request->user();
        if ($user->user_type === 'Teacher') {

        }

        if ($user->user_type === 'Student') {
            return view('classroom.exam-view-student', compact('class', 'lesson', 'exam'));
        }
    }

    public function submitAnswer(Request $request, Classroom $class, Lesson $lesson, Exam $exam)
    {
        $user = $request->user();
        $questions = $exam->examQuestions()->get();
        $data = $request->validate([
            'answers.*' => ['required'] 
        ]);
        $answers = $data['answers'];

        $action = DB::transaction(function () use ($user, $exam, $questions, $answers) {
            foreach ($questions as $index => $question) {
                if ($answers[$index]) {
                    $ans = $question->answers()->make([
                        'answer' => $answers[$index]
                    ]);
                    $ans->student()->associate($user);
                    $ans->exam()->associate($exam);
                    $ans->save();
                }
            }

            return true;
        });

        if ($action) {
            return response()->json([
                'success' => true,
                'message' => 'Answer has been submitted. Please wait for the teacher to grade your exam.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.'
            ]);
        }
    }

    public function viewSubmission(Classroom $class, Lesson $lesson, Exam $exam, User $user)
    {
        return view('classroom.exam-view-submission', compact('class', 'lesson', 'exam', 'user'));
    }

    public function markAnswer(Request $request, Classroom $class, Lesson $lesson, Exam $exam, User $user, Answer $answer)
    {
        $score = $exam->examScores()->where('student_id', $user->id)->first();
        $remark = $request->input('answer');

        if ($score) {
            DB::transaction(function() use ($remark, $answer, $score) {
                $currentScore = $score->score;
                if ($remark === 'correct') {
                    $answer->update([
                        'is_correct' => true
                    ]);
                    $studentScore = $score->update([
                        'score' => $currentScore + 1,
                    ]);
                    return $studentScore;
                } else {
                    $answer->update([
                        'is_correct' => false
                    ]);
                    return true;
                }
            });
        } else {
            DB::transaction(function() use ($remark, $exam, $user, $answer) {
                if ($remark === 'correct') {
                    $answer->update([
                        'is_correct' => true
                    ]);
                    $studentScore = $exam->examScores()->make([
                        'score' => 1,
                    ]);
                    
                } else {
                    $answer->update([
                        'is_correct' => false
                    ]);
                    $studentScore = $exam->examScores()->make([
                        'score' => 0
                    ]);
                }
                $studentScore->student()->associate($user);
                $studentScore->save();

                return $studentScore;
            });
        }

        return redirect()->back();
    }

    public function markScore(Request $request, Classroom $class, Lesson $lesson, Exam $exam, User $user, ExamScore $score)
    {
        $remarks = $request->input('remarks');

        DB::transaction(function () use ($remarks, $score, $user, $lesson) {
            if ($remarks === 'pass') {
                $score->update([
                    'is_pass' => true
                ]);

                $certificate = $user->certificates()->make();
                $certificate->lesson()->associate($lesson);
                $certificate->save();

            } else {
                $score->update([
                    'is_pass' => false
                ]);
            }

            return $score;
        });

        return redirect()->back();
    }
}
