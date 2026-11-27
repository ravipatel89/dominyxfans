@php
    $attachment = $getRecord();
    $type = (string) $attachment->attachmentType;
    $preview = match (true) {
        (bool) $attachment->has_thumbnail => $attachment->thumbnail,
        $type === 'image' => $attachment->path,
        default => null,
    };
    $icon = match ($type) {
        'video' => 'heroicon-o-video-camera',
        'audio' => 'heroicon-o-speaker-wave',
        'document' => 'heroicon-o-document-text',
        default => 'heroicon-o-paper-clip',
    };
@endphp

<div class="admin-attachment-table-preview" title="{{ ucfirst($type) }} attachment">
    @if (filled($preview))
        <img src="{{ $preview }}" alt="" loading="lazy">

        @if ($type === 'video')
            <span class="admin-attachment-table-preview__overlay" aria-hidden="true">
                <x-filament::icon icon="heroicon-s-play" />
            </span>
        @endif
    @elseif ($type === 'document')
        <img src="{{ asset('/img/pdf-preview.svg') }}" alt="" loading="lazy">
    @else
        <x-filament::icon :icon="$icon" />
    @endif

    <span class="fi-sr-only">{{ ucfirst($type) }} attachment</span>
</div>
