<?php



namespace App\Services;



use App\Models\User;

use Illuminate\Support\Facades\Mail;

use App\Mail\SendOtp;

use Exception;



class EmailVerificationService

{

    public function sendOtp($email)

    {

        $otp = rand(1111, 9999);

        User::where('email', $email)->update(['otp' => $otp]);



        // try {

            Mail::to($email)->send(new SendOtp($otp));

        // } catch (Exception $e) {

        //     throw new Exception('Failed to send OTP. Please try again later.');

        // }

    }

}

