<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageReportController;
use App\Http\Controllers\Admin\ManageStudentsController;
use App\Http\Controllers\Admin\ManageTeachersController;
use App\Http\Controllers\Admin\ManageUsersController;
use App\Http\Controllers\ClassConferenceController;
use App\Http\Controllers\ClassPostController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CommunityPostController;
use App\Http\Controllers\FindTeacherController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Route::get('/terms', function () {
    return view('terms');
})->middleware('guest')->name('terms');


Route::middleware(['auth', 'verified', 'verified.teachers'])->group(function() {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/compiler', function()
    {
        return view('shared.compiler');
    })->name('compiler');

    Route::middleware('admin')->group(function() {
        Route::controller(ManageTeachersController::class)->group(function() {
            Route::get('/verified-teachers', 'verifiedTeachers')->name('verified.teachers');
            Route::get('/unverified-teachers', 'unverifiedTeachers')->name('unverified.teachers');
            
            Route::get('/get-teachers/{condition}', 'teachersList')->name('get.teachers');
            Route::get('/view-file/{file}', 'viewFile')->name('view.teacher_id');
            Route::post('/verify-teacher', 'verifyTeacher')->name('verify.teacher');

            Route::get('/get-teacher/{user}', 'getTeacher')->name('get.teacher');
        });
        
        Route::controller(ManageStudentsController::class)->group(function() {
            Route::get('/students', 'index')->name('students.index');
            Route::get('/get-students', 'getStudents')->name('get.students');
            Route::get('/get-student/{user}', 'getStudent')->name('get.student');
        });

        Route::controller(ManageReportController::class)->group(function() {
            Route::get('/reported-posts', 'index')->name('reported.posts');
            Route::get('/get-reported-posts', 'getReportedPosts')->name('get.reported.posts');

            Route::get('/get-reported-posts/view/{post}', 'getReportedPost')->name('get.reported');
            Route::delete('/get-reported-posts/delete/{post}', 'removePost')->name('reported.remove');
        });

        Route::controller(ManageUsersController::class)->group(function() {
            Route::get('/users', 'index')->name('users.index');

            Route::post('/users/{user}/send-warning', 'sendWarning')->name('users.send.warning');
            Route::post('/users/{user}/ban', 'banUser')->name('users.ban');
            Route::post('/users/{user}/delete', 'deleteUser')->name('users.delete');
        });
    });

    Route::controller(CommunityPostController::class)->group(function() {
        Route::get('/community', 'index')->name('community');
        Route::post('/community/save', 'store')->name('community.post');
        Route::patch('/community/{post}/update', 'update')->name('community.post.update');
        Route::delete('/community/{post}/delete', 'delete')->name('community.post.delete');
        Route::post('/community/{post}/comment', 'comment')->name('community.comment');

        Route::delete('/community/{post}/comment/delete/{comment}', 'deleteComment')->name('community.comment.delete');

        Route::post('/community/{post}/report', 'report')->name('community.report');
        Route::post('/temp-upload', 'tempImgUpload')->name('community.temp.img');
        Route::post('/temp-delete', 'tempImgDelete')->name('community.temp.delete');

        Route::get('/community/{post}/get-attachments', 'getAttachments')->name('posts.attachments.view');
    });

    Route::controller(ProfileController::class)->group(function() {
        Route::get('/profile/{user}', 'index')->name('profile');
        Route::post('/profile/{user}/update', 'update')->name('profile.update');
        Route::post('/profile/{user}/set-schedule', 'setSchedule')->name('profile.set.schedule');

        Route::get('/profile/{user}/certificates', 'certificates')->name('profile.certificates');
        Route::get('/profile/{user}/certificates/view/{certificate}', 'viewCertificate')->name('profile.certificates.view');
        Route::get('/profile/{user}/moments', 'viewMoments')->name('profile.moments');

        Route::get('/profile/{user}/get-schedules', 'getSchedules')->name('profile.schedules');
    });

    Route::controller(ClassroomController::class)->group(function() {
        //Teachers
        Route::get('/classes', 'index')->name('classes');
        Route::post('/classes/{user}', 'create')->name('classes.create');
        Route::patch('/classes/update/{class}', 'update')->name('classes.update');
        Route::delete('/classes/delete/{class}', 'delete')->name('classes.delete');
        
        //Students
        Route::post('/classes/join/{user}', 'join')->name('classes.join');
        Route::delete('/classes/leave/{class}', 'leave')->name('classes.leave');

        //Shared
        Route::get('/classes/{class}/posts', 'viewPosts')->can('view', 'class')->name('classes.view');
        Route::get('/classes/{class}/lessons', 'viewLessons')->name('classes.view.lessons');
        Route::get('/classes/{class}/exams', 'viewExams')->name('classes.view.exams');
        Route::get('/classes/{class}/participants', 'viewParticipants')->name('classes.view.participants');

        Route::post('/classes/{class}/invite', 'invite')->name('classes.invite');

        Route::get('/classes/{code}/{user}', 'inviteLink')->name('classes.email.invite');

        Route::delete('/classes/{class}/kick/{student}', 'kickStudent')->name('classes.kick.student');
    });

    Route::controller(ClassPostController::class)->group(function() {
        Route::post('/classes/{class}/post', 'post')->name('classes.post');
        Route::patch('/classes/{class}/post/update/{post}', 'update')->can('update', 'post')->name('classes.post.update');
        Route::delete('/classes/{class}/post/delete/{post}', 'delete')->can('delete', 'post')->name('classes.post.delete');

        Route::post('/classes/{class}/post/{post}/comment', 'comment')->name('classes.post.comment');

        Route::delete('/classes/{class}/post/{post}/comment/delete/{comment}', 'deleteComment')->name('classes.post.comment.delete');
    });

    Route::controller(LessonController::class)->group(function () {
        Route::post('/classes/{class}/lesson', 'save')->name('classes.lesson.save');

        Route::post('/classes/{class}/lesson/{lesson}/add-section', 'addSection')->name('classes.lesson.add.section');
        Route::post('/classes/{class}/lesson/{lesson}/publish', 'publishLesson')->name('classes.lesson.publish');

        Route::get('/classes/{class}/lesson/{lesson}/create-exam', 'createExam')->name('classes.lesson.exam.create');

        Route::get('/classes/{class}/lesson/{lesson}/view-lesson', 'viewLesson')->name('classes.lesson.view.section');
        Route::post('/classes/{class}/lesson/{lesson}/mark-as-done/{section}', 'markAsDone')->name('classes.lesson.section.mark');

        Route::post('/classes/{class}/lesson/{lesson}/save-exam', 'saveExam')->name('classes.lesson.exam.save');

        Route::get('/classes/{class}/lesson/{lesson}/take-exam/{exam}', 'takeExam')->name('classes.lesson.exam.take');
        Route::post('/classes/{class}/lesson/{lesson}/take-exam/{exam}/submit-answer', 'submitAnswer')->name('classes.lesson.exam.submit.answer');

        Route::get('/classes/{class}/lesson/{lesson}/view-exam/{exam}/submission/{user}', 'viewSubmission')->name('classes.lesson.exam.view.submission');

        Route::post('/classes/{class}/lesson/{lesson}/view-exam/{exam}/submission/{user}/mark-answer/{answer}', 'markAnswer')->name('classes.lesson.exam.mark.answer');
        Route::post('/classes/{class}/lesson/{lesson}/view-exam/{exam}/submission/{user}/mark-score/{score}', 'markScore')->name('classes.lesson.exam.mark.score');
    });

    Route::controller(ClassConferenceController::class)->group(function() {
        Route::get('/classes/{class}/meet/{conference}', 'startMeeting')->name('classes.meet.start');
        Route::post('/classes/{class}/meet', 'createMeeting')->name('classes.meet.create');
        Route::post('/classes/{class}/meet/{conference}/save-time/{user}', 'calculateTime')->name('classes.meet.calculate');
    });
    
    Route::controller(FindTeacherController::class)->group(function() {
        Route::get('/find-teachers', 'index')->name('find.teachers');
    });

});

//Fortify route for becoming a teacher || can't modify vendor files
//So we extend the routes :)
Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    Route::get('/be-a-teacher', fn() => view('auth.be-a-teacher'))
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('be.a.teacher');
    
    Route::post('/be-a-teacher', [RegisteredUserController::class, 'store'])
        ->middleware(['guest:'.config('fortify.guard')]);
    
    Route::get('/for-approval', function(Request $request) {
        if($request->user()->user_type === 'Teacher' && !$request->user()->teacherDetail->is_verified) {
            return view('auth.teacher-approval-prompt');
        } else {
            return to_route('dashboard');
        }
    })
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('for.approval.notice');
});
