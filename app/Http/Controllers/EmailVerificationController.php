<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationController extends Controller
{

    /**
     * Visszaigazoló üzenet küldése e-mail címre
     *
     * @param Request $request
     * @return Response
     */
    public function sendVerificationEmail(Request $request): Response
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response([
                "success" => false,
                "message" => "Email already verified."
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response([
            "success" => true,
            "status" => "Verification link has been sent."
        ]);
    }

    /**
     * E-mail cím megerősítése
     *
     * @param EmailVerificationRequest $request
     * @return Response
     */
    public function verify(EmailVerificationRequest $request): Response
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response([
                "success" => false,
                "message" => "Email already verified."
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response([
            "success" => true,
            "message" => "Email has been verified."
        ]);
    }
}
