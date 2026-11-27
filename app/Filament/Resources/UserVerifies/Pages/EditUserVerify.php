<?php

namespace App\Filament\Resources\UserVerifies\Pages;

use App\Filament\Resources\UserVerifies\UserVerifyResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\EditRecord;

class EditUserVerify extends EditRecord
{
    protected static string $resource = UserVerifyResource::class;

    protected function getHeaderActions(): array
    {
        [$reviewActions, $otherActions] = collect(UserVerifyResource::getRecordActions())
            ->partition(fn (Action $action): bool => in_array($action->getName(), ['approve', 'reject'], true));

        return [
            ...$reviewActions->map(fn (Action $action): Action => $action->button())->all(),
            ActionGroup::make($otherActions->all())
                ->icon('heroicon-o-ellipsis-horizontal')
                ->iconSize('lg'),
        ];
    }
}
