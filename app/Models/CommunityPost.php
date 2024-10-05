<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CommunityPostComment::class);
    }
    
    public function communityPostAttachments(): HasMany
    {
        return $this->hasMany(CommunityPostAttachment::class);
    }

    public function temporaryDeletes(): HasMany
    {
        return $this->hasMany(TemporaryDelete::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ReportPost::class);
    }
}
