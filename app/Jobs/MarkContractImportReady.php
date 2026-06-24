<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MarkContractImportReady implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $token,
        private string $filename,
        private string $path
    ) {}

    public function handle(): void
    {
        Cache::put("import_contracts_{$this->token}", [
            'status' => 'ready',
            'filename' => $this->filename,
        ], now()->addHours(2));

        Storage::disk('local')->delete($this->path);

        $directory = dirname($this->path);

        if (
            empty(Storage::disk('local')->files($directory)) &&
            empty(Storage::disk('local')->directories($directory))
        ) {
            Storage::disk('local')->deleteDirectory($directory);
        }
    }
}
