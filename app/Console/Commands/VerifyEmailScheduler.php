<?php

namespace App\Console\Commands;

use Exception;
use App\Models\User;
use App\Jobs\VerifyEmailJob;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\Dispatchable;

class VerifyEmailScheduler extends Command
{
    use Dispatchable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch email verification jobs for users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
        $users = User::whereNull('email_verified_at')->get();

        foreach ($users as $user) {
            dispatch(new VerifyEmailJob($user->email));
        }
    } catch (Exception $e) {
        \Log::error('Error in VerifyEmailScheduler: ' . $e->getMessage());
    }
        return 0;
    }
}
