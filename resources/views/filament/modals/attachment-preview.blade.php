@php
    $type = (string) $attachment->attachmentType;
    $path = (string) $attachment->path;
@endphp

<div class="admin-attachment-modal-preview">
    <div class="admin-attachment-modal-preview__media">
        @switch($type)
            @case('image')
                <img src="{{ $path }}" alt="{{ basename((string) $attachment->filename) }}">
                @break

            @case('video')
                <video
                    src="{{ $path }}#t=0.001"
                    @if($attachment->has_thumbnail) poster="{{ $attachment->thumbnail }}" @endif
                    controls
                    controlsList="nodownload"
                    preload="metadata"
                ></video>
                @break

            @case('audio')
                <div class="admin-attachment-modal-preview__audio">
                    <x-filament::icon icon="heroicon-o-speaker-wave" />
                    <audio src="{{ $path }}" controls controlsList="nodownload" preload="metadata"></audio>
                </div>
                @break

            @default
                <div class="admin-attachment-modal-preview__file">
                    <x-filament::icon icon="heroicon-o-document-text" />
                    <p>This file type cannot be previewed in the browser.</p>
                </div>
        @endswitch
    </div>

    <div class="admin-attachment-modal-preview__footer">
        <span>{{ ucfirst($type) }} · {{ basename((string) $attachment->filename) }}</span>

        <x-filament::button
            tag="a"
            :href="$path"
            target="_blank"
            rel="noopener noreferrer"
            icon="heroicon-o-arrow-top-right-on-square"
            color="gray"
            size="sm"
        >
            Open original
        </x-filament::button>
    </div>
</div>
