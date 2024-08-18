<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\SendOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $email;
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if (User::where('email', $this->email)->exists()) {
                $otp = 1234; // Ideally, generate a random OTP here
                User::where('email', $this->email)->update(['otp' => $otp]);
                Mail::to($this->email)->send(new SendOtp($otp));
            }
        } catch (\Exception $e) {
            \Log::error('Error in VerifyEmailJob: ' . $e->getMessage());
        }
    }
}
