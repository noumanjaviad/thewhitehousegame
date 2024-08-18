<?php

namespace App\Console\Commands;

use Log;
use Exception;
use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWelcomeEmailScheduler extends Command
{
    use Dispatchable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-welcome-email-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
        $users = User::whereNotNull('email_verified_at') // Ensure email is verified (if applicable)
            ->where('newsletter_subscribed', true)
            ->whereNotNull('updated_at')
            // ->where('updated_at', '>=', now()->subDay()) // Adjust timeframe as needed
            ->get();

        // Dispatch the job for each user found
        foreach ($users as $user) {
            SendWelcomeEmailJob::dispatch($user);
        }

        $this->info('Welcome emails dispatched successfully.');
    } catch (Exception $e) {
        Log::error('Error in VerifyEmailScheduler: ' . $e->getMessage());
    }
        return 0;
    }
}
