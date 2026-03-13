<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReportExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $reportType, public array $filters = [])
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Logic for heavy report generation (Excel/PDF) using Maatwebsite Excel
    }
}
