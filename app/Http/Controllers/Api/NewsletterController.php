<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function joinNewsletter(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        // dd($email);

        $newsletter = new NewsLetter();
        $newsletter->email = $email;
        $newsletter->save();
        $this->sendWelcomeEmail($email);

        $response = [
            'success' => true,
            'message' => 'You have successfully join to our newsletter',
        ];
        return $this->sendSuccessResponse($response);
    }

    private function sendWelcomeEmail($email)
    {
        Mail::to($email)->send(new \App\Mail\WelcomeNewsletter());
    }

    public function saveNewNewsletter()
    {
        try {
            $newsletter = NewsLetter::create(['title' => $request->title, 'body' => $request->body]);

            $response = [
                'message' => 'Sucessfully',
                'data' => $newsletter,
            ];

            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

    }
}
