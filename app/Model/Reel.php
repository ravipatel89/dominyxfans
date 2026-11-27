<?php

namespace App\Model;

use App\Providers\AttachmentServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Reel extends Model
{
    protected $fillable = [
        'user_id',
        'caption',
        'is_public',
        'overlay',
        'sound_id',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'overlay' => 'array',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Attachment, $this>
     */
    use Traits\HasAttachmentPlacements;

    /**
     * @return HasOneThrough<Attachment, AttachmentPlacement, $this>
     */
    public function video(): HasOneThrough
    {
        return $this->hasOneThrough(Attachment::class, AttachmentPlacement::class, 'reel_id', 'id', 'id', 'attachment_id')
            ->whereIn('attachments.type', AttachmentServiceProvider::getTypeByExtension('video'))
            ->orderBy('attachment_placements.position')->orderBy('attachment_placements.id');
    }

    /**
     * @return HasOneThrough<Attachment, AttachmentPlacement, $this>
     */
    public function cover(): HasOneThrough
    {
        return $this->hasOneThrough(Attachment::class, AttachmentPlacement::class, 'reel_id', 'id', 'id', 'attachment_id')
            ->whereIn('attachments.type', AttachmentServiceProvider::getTypeByExtension('default'))
            ->orderBy('attachment_placements.position')->orderBy('attachment_placements.id');
    }

    /**
     * @return HasMany<ReelView, $this>
     */
    public function views(): HasMany
    {
        return $this->hasMany(ReelView::class);
    }

    /**
     * @return HasMany<ReelComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ReelComment::class);
    }

    /**
     * @return HasMany<Reaction, $this>
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class, 'reel_id');
    }

    /**
     * @return HasMany<UserBookmark, $this>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(UserBookmark::class, 'reel_id');
    }

    /**
     * @return BelongsTo<Sound, $this>
     */
    public function sound(): BelongsTo
    {
        return $this->belongsTo(Sound::class, 'sound_id');
    }
}
