<?php

namespace App\Jobs\Emails;

use App\Notifications\Summary\AdminDailyUserSummary;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        $this->prepareNewUserSummary();
    }

    public function prepareNewUserSummary()
    {
        Log::info('Sending motion summary');

        $newUsers = User::with('roles')->where('created_at', '>=', Carbon::now()->subHours(24))
                    ->doesntHaveRoles(['administrator'])->get();

        if ($newUsers->isEmpty()) {
            return true;
        }

        $admins = User::hasPermissions(['show-user'])->preference('authentication.notify.admin.summary.on', 1)->get();

        foreach ($admins as $admin) {
            Log::info('Sending admin summary to user: '.$user->id);

            $admin->notify(new AdminDailyUserSummary($newUsers, $admin));
        }
    }
}
