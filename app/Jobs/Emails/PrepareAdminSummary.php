<?php

namespace App\Jobs\Emails;

use App\Notifications\Summary\AdminSummary;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrepareAdminSummary implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
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
        $newUsers = User::where('created_at', '>=', Carbon::now()->subHours(24))->get();

        if ($newUsers->empty()) {
            return true;
        }

        $admins = User::hasRoles(['administrator'])->get();


        foreach ($admins as $admin) {
            $admin->notify(new AdminSummary($newUsers));
        }
    }
}
