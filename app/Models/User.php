<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'avatar',
        'ban_effective'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ban_effective' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
    //scope for getting all teacher
    public function scopeTeachers($query, $verified)
    {
        return $query->where('user_type', 'Teacher')
                    ->whereRelation('teacherDetail', 'is_verified', $verified);
    }

    //scope for getting all students
    public function scopeStudents($query)
    {
        return $query->where('user_type', 'Student')
                    ->with('studentDetail');
    }


    /** Start of Relationships || Relation */
    public function studentDetail(): HasOne
    {
        return $this->hasOne(StudentDetail::class);
    }

    public function teacherDetail(): HasOne
    {
        return $this->hasOne(TeacherDetail::class);
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function enrolledClassroom(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'enrollments');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ClassPost::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

    public function conferences(): HasMany
    {
        return $this->HasMany(ClassConference::class, 'teacher_id');
    }

    public function communityPosts(): HasMany
    {
        return $this->hasMany(CommunityPost::class, 'author_id');
    }

    public function communityPostComments(): HasMany
    {
        return $this->hasMany(CommunityPostComment::class, 'author_id');
    }

    public function temporaryUploads(): HasMany
    {
        return $this->hasMany(TemporaryUpload::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ReportPost::class, 'reporter_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'student_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'student_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'student_id');
    }

    public function teacherSchedule(): HasMany
    {
        return $this->hasMany(TeacherSchedule::class, 'teacher_id');
    }
}
