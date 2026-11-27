<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class NpmPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npm:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds client assets and publishes framework-managed assets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Builds client assets and publishes framework-managed assets.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('[*]['.date('H:i:s').'] Building Bootstrap 5 assets.');

        $npm = PHP_OS_FAMILY === 'Windows' ? 'npm.cmd' : 'npm';
        passthru($npm.' run build', $status);

        if ($status !== self::SUCCESS) {
            $this->error('[!] Frontend build failed. Check the build output before retrying.');

            return self::FAILURE;
        }

        // Filament & plugins
        $this->info('[*] ['.now()->format('H:i:s').'] Publishing Filament assets...');
        Artisan::call('filament:assets');

        // Livewire assets
        $this->publishLiveWire();

        $this->info('[*]['.date('H:i:s')."] Assets published successfully.");

        return self::SUCCESS;
    }

    /**
     * Publishing livewire assets on each build
     * Relying on livewire's default assets loading (route-based) was not reliable on shared hosting/sub-directories usage.
     * @return void
     */
    public function publishLiveWire()
    {
        $this->info('[*] ['.now()->format('H:i:s').'] Publishing Livewire assets...');

        // 1. Publish to public/vendor/livewire
        Artisan::call('vendor:publish', [
            '--tag' => 'livewire:assets',
            '--force' => true,
        ]);

        // 2. Move to public/libs/livewire
        $source = public_path('vendor/livewire');
        $destination = public_path('libs/livewire');

        if (File::exists($source)) {
            if (File::exists($destination)) {
                File::deleteDirectory($destination);
            }

            File::ensureDirectoryExists(dirname($destination));
            File::copyDirectory($source, $destination);
            $this->line("[-] [".now()->format('H:i:s')."] Livewire assets moved to public/libs/livewire.");

            // 3. Cleanup original vendor/livewire folder
            File::deleteDirectory($source);
            $this->line("[-] Removed original public/vendor/livewire directory.");

            // Removing folder entirely
            $vendorPath = public_path('vendor');

            if (File::isDirectory($vendorPath) && count(File::allFiles($vendorPath)) === 0 && count(File::directories($vendorPath)) === 0) {
                File::deleteDirectory($vendorPath);
                $this->line("[-] Removed empty public/vendor directory.");
            }

        } else {
            $this->warn("[-] Livewire assets not found in vendor folder.");
        }
    }
}
