<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
        ], [
            'email.email' => 'This email address could not be found. Please check and try again.',
        ]);

        $email = $request->email;

        // ── Real deliverability check — catches mailboxes that pass DNS
        //    but don't actually exist ──
        $check = $this->verifyEmailDeliverability($email);
        if (!$check['deliverable']) {
            return response()->json([
                'sent' => false,
                'invalid_recipient' => true,
                'message' => $check['message'],
            ], 422);
        }

        $otp = rand(100000, 999999);

        Session::put('email_otp', $otp);
        Session::put('email_otp_email', $email);
        Session::put('email_otp_expires', now()->addMinutes(5));

        Log::info('OTP SEND', [
            'session_id' => Session::getId(),
            'email' => $email,
            'otp' => $otp,
        ]);

        try {
            Mail::raw(
                "Your verification code is: $otp\n\nThis code expires in 5 minutes.",
                function ($msg) use ($email) {
                    $msg->to($email)->subject('Email Verification Code');
                }
            );
        } catch (\Exception $e) {
            Log::error('OTP mail failed: ' . $e->getMessage());
            return response()->json([
                'sent' => false,
                'message' => 'Failed to send email. Please check your mail configuration.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        try {
            AuditLog::log(
                'Sent OTP',
                "Sent OTP verification email to {$email}",
                'otp',
                null
            );
        } catch (\Exception $e) {
            Log::error('AuditLog failed (non-fatal): ' . $e->getMessage());
        }

        return response()->json(['sent' => true]);
    }

    public function verify(Request $request)
    {
        $otp = Session::get('email_otp');
        $email = Session::get('email_otp_email');
        $expires = Session::get('email_otp_expires');

        Log::info('OTP VERIFY ATTEMPT', [
            'session_id' => Session::getId(),
            'session_otp' => $otp,
            'session_email' => $email,
            'submitted_otp' => $request->otp,
            'submitted_email' => $request->email,
        ]);

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
        Session::put('email_otp_verified_email', $request->email);

        try {
            AuditLog::log(
                'Verified OTP',
                "OTP verified for email {$request->email}",
                'otp',
                null
            );
        } catch (\Exception $e) {
            Log::error('AuditLog failed (non-fatal): ' . $e->getMessage());
        }

        return response()->json(['valid' => true]);
    }

    /**
     * Checks whether an email address is actually deliverable
     * (not just DNS-valid) using AbstractAPI's Email Validation service.
     * Fails open (treats as deliverable) if the API key is missing
     * or the API call fails, so this never blocks registration outright
     * if the third-party service is down.
     */
    private function verifyEmailDeliverability(string $email): array
    {
        $apiKey = config('services.abstract_email.key');
        if (!$apiKey) {
            return ['deliverable' => true, 'message' => null];
        }

        try {
            $response = Http::timeout(5)->get(
                'https://emailreputation.abstractapi.com/v1/',
                ['api_key' => $apiKey, 'email' => $email]
            );

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['email_deliverability']['status'] ?? null;

                if ($status === 'undeliverable') {
                    return [
                        'deliverable' => false,
                        'message' => 'This email address does not exist. Please check and try again.',
                    ];
                }
            } else {
                Log::warning('Email reputation API returned non-success status', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Email verification API failed (non-fatal): ' . $e->getMessage());
        }

        return ['deliverable' => true, 'message' => null];
    }
}