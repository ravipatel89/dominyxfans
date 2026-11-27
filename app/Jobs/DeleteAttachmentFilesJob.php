<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DeleteAttachmentFilesJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public int $tries = 10;

    public int $timeout = 120;

    // Only scalar storage keys are serialized: the attachment may no longer exist.
    public function __construct(public string $disk, public array $keys, public ?string $coconutJobId = null, public ?string $coconutRegion = null)
    {
        $this->onConnection('attachment_cleanup')->onQueue('attachment-cleanup')->beforeCommit();
    }

    public function backoff(): array
    {
        return [60, 300, 900, 3600, 21600];
    }

    public function handle(): void
    {
        if ($this->coconutJobId && !app(\App\Services\Media\CoconutJobStatusService::class)->isFinished($this->coconutJobId, $this->coconutRegion)) {
            throw new RuntimeException('Waiting for the remote encoder to finish before purging its output files.');
        }
        $storage = Storage::disk($this->disk);
        foreach ($this->keys as $key) {
            if (!$storage->delete($key) && $storage->exists($key)) {
                throw new RuntimeException('Could not remove attachment storage object: '.$key);
            }
        }
    }
}
