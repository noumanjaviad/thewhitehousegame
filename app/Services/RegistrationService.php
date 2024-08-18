<?php

namespace App\Services;

use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    protected $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    public function register(array $data)
    {
        DB::beginTransaction();
        // try {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();

        // $otp = rand(1000, 9999);
        // SendOtpEmail::dispatch($user->email,$otp);

        DB::commit();

        $this->emailVerificationService->sendOtp($user->email);
        return $user;
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     throw new Exception('Registration failed');
        // }
    }
}
