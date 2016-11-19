<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vinkla\Backup\Backup;

class RunBackup implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $backup;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Backup $backup)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->backup->run();
    }
}
