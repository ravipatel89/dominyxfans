<?php

namespace App\Filament\Resources\Attachments\Pages;

use App\Filament\Resources\Attachments\AttachmentResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAttachment extends EditRecord
{
    protected static string $resource = AttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AttachmentResource::getRecordActionsGroup(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        abort_unless(AttachmentResource::canEdit($record->refresh()), 403);

        if (AttachmentResource::isShared($record)) {
            // Shared assets may only receive safe metadata changes. Structural
            // fields remain immutable because placements depend on them.
            $record->fill(array_intersect_key($data, array_flip([
                'filename', 'has_thumbnail', 'has_blurred_preview',
            ])));
            $record->save();

            return $record;
        }

        return parent::handleRecordUpdate($record, $data);
    }
}
