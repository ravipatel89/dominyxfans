<?php

namespace App\Filament\Resources\Attachments\Pages;

use App\Filament\Resources\Attachments\AttachmentResource;
use App\Model\Attachment;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewAttachment extends ViewRecord
{
    protected static string $resource = AttachmentResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('edit_attachment')
                ->label(__('Edit'))
                ->url(fn (Attachment $record): string => AttachmentResource::getUrl('edit', ['record' => $record]))
                ->visible(fn (Attachment $record): bool => AttachmentResource::canEdit($record)),
            AttachmentResource::getRecordActionsGroup(),
        ];
    }
}
