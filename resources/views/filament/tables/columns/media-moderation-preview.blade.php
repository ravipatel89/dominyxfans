@php
    $item = $getRecord();
    $attachment = $item->attachment;
    $type = (string) $item->media_type;
    $preview = match (true) {
        $item->isProfileAsset() => app(\App\Services\Moderation\ProfileMediaSourceService::class)->previewUrl($item),
        $item->isStreamPoster() => app(\App\Services\Moderation\StreamPosterSourceService::class)->previewUrl($item),
        (bool) $attachment?->has_thumbnail => $attachment->thumbnail,
        $type === 'image' => $attachment?->path,
        default => null,
    };
    $icon = match ($type) {
        'video' => 'heroicon-o-video-camera',
        'image' => 'heroicon-o-photo',
        default => 'heroicon-o-paper-clip',
    };
    $label = ucfirst($type).' moderation item';
@endphp

<a
    href="{{ \App\Filament\Resources\MediaModerationItems\MediaModerationItemResource::getUrl('view', ['record' => $item]) }}"
    class="admin-attachment-table-preview"
    title="{{ $label }}"
>
    @if (filled($preview))
        <img src="{{ $preview }}" alt="" loading="lazy">

        @if ($type === 'video')
            <span class="admin-attachment-table-preview__overlay" aria-hidden="true">
                <x-filament::icon icon="heroicon-s-play" />
            </span>
        @endif
    @else
        <x-filament::icon :icon="$icon" />
    @endif

    <span class="fi-sr-only">{{ $label }}</span>
</a>
