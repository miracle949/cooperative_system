<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $email = $request->email;
        $otp = rand(100000, 999999);

        Session::put('email_otp', $otp);
        Session::put('email_otp_email', $email);
        Session::put('email_otp_expires', now()->addMinutes(5));

        Mail::raw("Your verification code is: $otp\n\nThis code expires in 5 minutes.", function ($msg) use ($email) {
            $msg->to($email)->subject('Email Verification Code');
        });

        return response()->json(['sent' => true]);
    }

    public function verify(Request $request)
    {
        $otp = Session::get('email_otp');
        $email = Session::get('email_otp_email');
        $expires = Session::get('email_otp_expires');

        if (!$otp || !$expires) {
            return response()->json(['valid' => false, 'message' => 'OTP expired. Please request a new one.']);
        }
        if (now()->gt($expires)) {
            Session::forget(['email_otp', 'email_otp_email', 'email_otp_expires']);
            return response()->json(['valid' => false, 'message' => 'OTP has expired. Please request a new one.']);
        }
        if ((string) $request->otp !== (string) $otp || $request->email !== $email) {
            return response()->json(['valid' => false, 'message' => 'Incorrect code. Please try again.']);
        }

        Session::forget(['email_otp', 'email_otp_email', 'email_otp_expires']);
        Session::put('email_otp_verified_email', $request->email); // ← only change
        return response()->json(['valid' => true]);
    }
}