<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class MarkContractExportReady implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $token,
        private string $path,
        private string $filename
    ) {}

    public function handle(): void
    {
        Cache::put("export_contracts_{$this->token}", [
            'status' => 'ready',
            'path' => $this->path,
            'filename' => $this->filename,
        ], now()->addHours(2));
    }
}
