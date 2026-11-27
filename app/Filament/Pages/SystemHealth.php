<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Settings\ManageMediaSettings;
use App\Filament\Pages\Settings\ManageRuntimeSettings;
use App\Filament\Pages\Settings\ManageStorageSettings;
use App\Services\SystemHealthService;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class SystemHealth extends Page
{
    use HasPageShield;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $slug = 'system-health';

    protected string $view = 'filament.pages.system-health';

    public static function canAccess(): bool
    {
        return Filament::auth()->user()?->can('View:SystemHealth') ?? false;
    }

    public function getTitle(): string
    {
        return __('System Health');
    }

    protected function getHeaderActions(): array
    {
        return [Action::make('refresh')->label(__('Refresh'))->icon('heroicon-o-arrow-path')->color('gray')->action(fn () => null)];
    }

    protected function getViewData(): array
    {
        return [
            'snapshot' => app(SystemHealthService::class)->snapshot(),
            'actionUrls' => [
                'queue' => ManageRuntimeSettings::canAccess() ? ManageRuntimeSettings::getUrl(['tab' => 'queue']) : null,
                'cache' => ManageRuntimeSettings::canAccess() ? ManageRuntimeSettings::getUrl(['tab' => 'cache']) : null,
                'storage' => ManageStorageSettings::canAccess() ? ManageStorageSettings::getUrl() : null,
                'media' => ManageMediaSettings::canAccess() ? ManageMediaSettings::getUrl() : null,
            ],
        ];
    }
}
