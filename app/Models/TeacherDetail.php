<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherDetail extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'contact_number',
        'is_verified',
        'file',
        'bio',
        'schedules'
    ];

    protected function casts(): array
    {
        return [
            'schedules' => 'array'
        ];
    }

    /** Relation */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
