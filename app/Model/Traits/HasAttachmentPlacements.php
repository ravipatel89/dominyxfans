<?php

namespace App\Model\Traits;

use App\Model\Attachment;
use App\Model\AttachmentPlacement;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAttachmentPlacements
{
    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(Attachment::class, 'attachment_placements', AttachmentPlacement::parentKey($this), 'attachment_id')
            ->withPivot(['id', 'position'])->withTimestamps()
            ->orderBy('attachment_placements.position')->orderBy('attachment_placements.id');
    }

    public function placements(): HasMany
    {
        return $this->hasMany(AttachmentPlacement::class, AttachmentPlacement::parentKey($this));
    }
}
