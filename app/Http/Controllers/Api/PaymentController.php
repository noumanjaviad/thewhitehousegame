<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{

    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|string',
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            \DB::beginTransaction();
            $user = auth()->user();

            if (empty($user->stripe_customer_id)) {
                $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->stripe_customer_id = $customer->id;
                $user->save();
            } else {
                $customer = Customer::retrieve($user->stripe_customer_id);
            }

            $paymentMethod = PaymentMethod::retrieve($request->payment_method_id);
            $paymentMethod->attach(['customer' => $customer->id]);

            Customer::update($customer->id, [
                'invoice_settings' => [
                    'default_payment_method' => $request->payment_method_id,
                ],
            ]);

            $cardLast4 = $paymentMethod->card->last4;

            $paymentIntent = PaymentIntent::create([
                'customer' => $customer->id,
                'payment_method' => $request->payment_method_id,
                'amount' => $request->amount, //use the amount as it is (in cents)
                'currency' => 'usd',
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'return_url' => 'https://white-house-game.vercel.app/candidate',
            ]);

            $user->card_last_four = $cardLast4;
            $user->save();
            DB::commit(); // Commit the transaction

            return response()->json(['success' => true, 'client_secret' => $paymentIntent->client_secret]);
        } catch (ApiErrorException $e) {
            \DB::rollBack(); // Rollback the transaction on error
            \Log::error('Stripe API error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            \DB::rollBack(); // Rollback the transaction on error
            \Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

}
