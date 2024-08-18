<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\SendOtp;
use App\Models\User;
use App\Services\RegistrationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function register(RegisterRequest $request)
    {
        // try {
            // $user = $this->$registrationService->register($request->all());
            $user = $this->registrationService->register($request->all());

            $response = [
                'message' => 'User registered successfully. Please verify your email address.',
                'user' => $user,
            ];
            return response()->json($response, 200);
        // } catch (Exception $e) {
        //     return response()->json(['error' => 'Registration failed', 'code' => 500], 500);
        // }
    }

    public function resendOTP(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validatedData['email'])->first();
        if (!$user) {
            return $this->sendErrorResponse('User not found.', 404);
        }

        $otp = rand(1111, 9999);
        if ($this->verifyEmail($user->email, $otp)) {
            // Update OTP in the database
            // $user->otp = $otp;
            // $user->save();
            return $this->sendSuccessResponse(['message' => 'OTP resent successfully.'], 200);
        } else {
            return $this->sendErrorResponse('Failed to resend OTP. Please try again later.', 500);
        }
    }
    private function verifyEmail($email)
    {
        $otp = rand(1111, 9999);
        User::where('email', $email)->update(['otp' => $otp]);

        try {
            Mail::to($email)->send(new SendOtp($otp));
            return response()->json([
                'message' => 'Please kindly check your email for OTP',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send OTP. Please try again later.',
                'code' => 500,
            ], 500);
        }
    }

    public function match_otp(Request $request)
    {

        $validatedData = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        // Check if the OTP is within the validity period ( 3 minutes )
        $expirationTime = now()->subMinutes(3);
        if ($user->updated_at->lessThan($expirationTime)) {
            return response()->json([
                'message' => 'OTP has expired.',
            ], 403);
        }
        // Check if OTP matches
        if ($user->otp == $validatedData['otp']) {
            // OTP matched successfully

            // Update email_verified_at column
            $user->email_verified_at = now();
            $user->save();

            // Generate access token
            $accessToken = $user->createToken('token generated')->accessToken;

            return response()->json([
                'message' => 'OTP matched successfully.',
                'user' => $user,
                'access_token' => $accessToken,
            ]);
        } else {
            return response()->json([
                'message' => 'OTP mismatch.',
            ], 403);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if ($user->email_verified_at != null) {
                    $response = $this->generateToken($user);
                    return $this->sendSuccessResponse($response, 200);
                } else {
                    // Email is not verified, deny login
                    return $this->sendErrorResponse('Unauthorized: Email not verified.', 401);
                }
            }
            return $this->sendErrorResponse('Unauthorized: Incorrect email or password', 401);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Unauthorized', 401);
        }
    }

    private function generateToken($user)
    {
        DB::beginTransaction();
        try {
            // Set token expiration time
            $tokenResult = $user->createToken('appToken');
            $token = $tokenResult->token;
            // $token->expires_at = Carbon::now()->addMinutes(1); // Set token expiration time to 60 minutes
            // $token->expires_at = Carbon::now()->addHours(24); // Set token expiration time to 24 hours

            $token->save();

            $accessToken = $tokenResult->accessToken;
            $user->token = $accessToken;

            DB::commit();

            return [
                'message' => 'User login successfully',
                'user' => $user,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function forget_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        if (User::where('email', $request->email)->doesntExist()) {
            return response()->json([
                'message' => 'your email does not exist',
            ], 403);
        } else {
            $otp = rand(1111, 9999);
            $user = User::where('email', $request->email)->update(['otp' => $otp]);
            Mail::to($request->email)->send(new SendOtp($otp));
            return response()->json([
                'message' => 'please kindly check your email',
                'code' => 200,
            ]);
        }
    }

    public function rest_password(Request $request)
    {
        if ($request->password == $request->confirm_password) {
            Auth::user()->update(['password' => Hash::make($request->password)]);
            $response = [
                'message' => 'Password reset successfully',
                'code' => 200,
            ];
            return $this->sendSuccessResponse($response, 200);
        } else {
            return $this->sendErrorResponse('confirm password does not match', 403);
        }
    }

    public function update_password(Request $request)
    {

        $request->validate([
            'new_password' => 'required|min:8',
        ]);
        if (Hash::check($request->old_password, auth()->user()->password)) {
            //dd( $request->old_password );
            if ($request->new_password == $request->confirm_password) {
                Auth::user()->update(['password' => Hash::make($request->new_password)]);
                return response()->json([
                    'message' => 'password has been updated',
                    'code' => 200,
                ]);
            } else {
                return $this->sendErrorResponse('your confirm password does not match with your new password', 403);
            }
        } else {
            return $this->sendErrorResponse('your old password does not match', 403);
        }
    }

    //logout api

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();

            $response = [
                'message' => 'User logged out successfully',
            ];

            return $this->sendSuccessResponse($response, 200);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Logout failed', 500);
        }
    }
    //end logout api

    //for test purpose

    public function get_user()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json($user, 200);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }
    //end for test purpose
}
