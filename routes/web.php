<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageStudentsController;
use App\Http\Controllers\Admin\ManageTeachersController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/landing', function() {
    $user = request()->user();
    switch ($user->user_type) {
        case 'Admin':
            return to_route('dashboard');
            break;
        
        default:
            return to_route('community');
            break;
    }
})->name('home');


Route::middleware(['auth', 'verified', 'verified.teachers'])->group(function() {
    

    Route::middleware('admin')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::controller(ManageTeachersController::class)->group(function() {
            Route::get('/verified-teachers', 'verifiedTeachers')
                ->name('verified.teachers');
            Route::get('/unverified-teachers', 'unverifiedTeachers')
                ->name('unverified.teachers');
            
            Route::get('/get-teachers/{condition}', 'teachersList')
                ->name('get.teachers');
            Route::get('/view-file/{file}', 'viewFile')
                ->name('view.teacher_id');
            Route::post('/verify-teacher', 'verifyTeacher')
                ->name('verify.teacher');
        });
        
        Route::controller(ManageStudentsController::class)->group(function() {
            Route::get('/students', 'index')
                ->name('students.index');
            Route::get('/get-students', 'getStudents')
                ->name('get.students');
        });
    });

    Route::controller(CommunityController::class)->group(function() {
        Route::get('/community', 'index')
            ->name('community');
    });
    Route::controller(ProfileController::class)->group(function() {
        Route::get('/profile', 'index')
            ->name('profile');
    });
    Route::controller(ClassroomController::class)->group(function() {
        Route::get('/classes', 'index')
            ->name('classes');
        Route::post('/classes/{user}', 'create')
            ->name('classes.create');
    });
    
    Route::get('/find-teachers', function() {
        return view('student.find-teacher');
    })->name('find.teachers');

});



//Fortify route for becoming a teacher || can't modify vendor files
//So we extend the routes :)
Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    Route::get('/be-a-teacher', fn() => view('auth.be-a-teacher'))
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('be.a.teacher');
    
    Route::post('/be-a-teacher', [RegisteredUserController::class, 'store'])
        ->middleware(['guest:'.config('fortify.guard')]);
    
    Route::get('/for-approval', fn() => view('auth.teacher-approval-prompt'))
        ->middleware(['auth:'.config('fortify.guard')])
        ->name('for.approval.notice');
});