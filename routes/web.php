<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageStudentsController;
use App\Http\Controllers\Admin\ManageTeachersController;
use App\Http\Controllers\ClassConferenceController;
use App\Http\Controllers\ClassPostController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CommunityPostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified', 'verified.teachers'])->group(function() {
        
    Route::get('/landing', function() {
        $user = request()->user();
        if($user && $user->user_type === 'Admin') {
            return to_route('dashboard');
        } else {
            return to_route('community');
        }
    })->name('home');

    Route::middleware('admin')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::controller(ManageTeachersController::class)->group(function() {
            Route::get('/verified-teachers', 'verifiedTeachers')->name('verified.teachers');
            Route::get('/unverified-teachers', 'unverifiedTeachers')->name('unverified.teachers');
            
            Route::get('/get-teachers/{condition}', 'teachersList')->name('get.teachers');
            Route::get('/view-file/{file}', 'viewFile')->name('view.teacher_id');
            Route::post('/verify-teacher', 'verifyTeacher')->name('verify.teacher');
        });
        
        Route::controller(ManageStudentsController::class)->group(function() {
            Route::get('/students', 'index')->name('students.index');
            Route::get('/get-students', 'getStudents')->name('get.students');
        });
    });

    Route::controller(CommunityPostController::class)->group(function() {
        Route::get('/community', 'index')->name('community');
        Route::post('/community/save', 'store')->name('community.post');
        Route::post('/community/{post}/comment', 'comment')->name('community.comment');

        Route::post('/community/{post}/report', 'report')->name('community.report');
        Route::post('/temp-upload', 'tempImgUpload')->name('community.temp.img');
        Route::post('/temp-delete', 'tempImgDelete')->name('community.temp.delete');
    });

    Route::controller(ProfileController::class)->group(function() {
        Route::get('/profile', 'index')->name('profile');
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
        Route::get('/classes/{class}', 'view')->name('classes.view');
        Route::post('/classes/{class}/invite', 'invite')->name('classes.invite');

        Route::get('/classes/{code}/{user}', 'inviteLink')->name('classes.email.invite');
    });

    Route::controller(ClassPostController::class)->group(function() {
        Route::post('/classes/{class}/post', 'post')->name('classes.post');
        Route::patch('/classes/{class}/post/update/{post}', 'update')->can('update', 'post')->name('classes.post.update');
        Route::delete('/classes/{class}/post/delete/{post}', 'delete')->can('delete', 'post')->name('classes.post.delete');

        Route::post('/classes/{class}/post/{post}/comment', 'comment')->name('classes.post.comment');
    });

    Route::controller(ClassConferenceController::class)->group(function() {
        Route::get('/classes/{class}/meet/{conference}', 'startMeeting')->name('classes.meet.start');
        Route::post('/classes/{class}/meet', 'createMeeting')->name('classes.meet.create');
    });
    
    Route::get('/find-teachers', function() {
        return view('student.find-teacher');
    })->name('find.teachers');

});

Route::get('/meet', fn() => view('sample'));

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
            return to_route('home');
        }
    })
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('for.approval.notice');
});
