<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryDelete extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'name',
        'path'
    ];

    public function communityPostAttachment(): BelongsTo
    {
        return $this->belongsTo(CommunityPostAttachment::class);
    }

    public function communityPost(): BelongsTo
    {
        return $this->belongsTo(CommunityPost::class);
    }
}
