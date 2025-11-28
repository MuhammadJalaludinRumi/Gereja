<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\OtpMail;

class ForgotPasswordController extends Controller
{
    /**
     * Kirim OTP ke email
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Email tidak ditemukan."
            ], 404);
        }

        // buat OTP 6 digit
        $otp = rand(100000, 999999);

        // simpan OTP ke database
        $user->reset_token = $otp;
        $user->reset_token_expired = now()->addMinutes(10);
        $user->save();

        // kirim email OTP
        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            "success" => true,
            "message" => "OTP dikirim ke email.",
        ]);
    }

    /**
     * Verifikasi OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $user = User::where('reset_token', $request->otp)->first();

        if (!$user) {
            return response()->json(["success" => false, "message" => "OTP salah"], 400);
        }

        if ($user->reset_token_expired < now()) {
            return response()->json(["success" => false, "message" => "OTP kadaluarsa"], 400);
        }

        return response()->json([
            "success" => true,
            "message" => "OTP valid"
        ]);
    }

    /**
     * Reset password menggunakan OTP
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|min:6'
        ]);

        $user = User::where('reset_token', $request->otp)->first();

        if (!$user) {
            return response()->json(["success" => false, "message" => "OTP salah"], 400);
        }

        if ($user->reset_token_expired < now()) {
            return response()->json(["success" => false, "message" => "OTP kadaluarsa"], 400);
        }

        // ubah password
        $user->password = bcrypt($request->password);

        // hapus OTP
        $user->reset_token = null;
        $user->reset_token_expired = null;
        $user->save();

        return response()->json([
            "success" => true,
            "message" => "Password berhasil diubah"
        ]);
    }
}
