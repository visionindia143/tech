<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPasswordResetOtp;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ──────────────────────────────────────────────
    // Login
    // ──────────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // ──────────────────────────────────────────────
    // Forgot Password
    // ──────────────────────────────────────────────
    public function showForgotPassword()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if email exists in admin users table
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No admin account found with this email address.'])->onlyInput('email');
        }

        // Generate and store OTP
        $otp = PasswordResetOtp::generateForEmail($request->email);

        try {
            // Send OTP via email
            Mail::to($request->email)->send(new AdminPasswordResetOtp($request->email, $otp));

            return redirect()->route('admin.password.verify-otp', ['email' => $request->email])
                ->with('success', 'OTP has been sent to your email address. Please check your inbox.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again or contact support.'])->onlyInput('email');
        }
    }

    // ──────────────────────────────────────────────
    // Verify OTP
    // ──────────────────────────────────────────────
    public function showVerifyOtp(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return redirect()->route('admin.password.request')->withErrors(['email' => 'Email address is required.']);
        }

        return view('admin.auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->merge([
            'otp' => trim($request->otp)
        ]);

        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        if (PasswordResetOtp::verifyOtp($request->email, $request->otp)) {
            return redirect()->route('admin.password.reset-form', [
                'email' => $request->email,
                'verified' => true
            ])->with('success', 'OTP verified successfully. Please set your new password.');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.'])->onlyInput(['email', 'otp']);
    }

    // ──────────────────────────────────────────────
    // Reset Password
    // ──────────────────────────────────────────────
    public function showResetPassword(Request $request, string $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        $verified = $request->query('verified');

        if (!$email || !$verified) {
            return redirect()->route('admin.password.request')->withErrors(['email' => 'Invalid reset request.']);
        }

        return view('admin.auth.reset-password-otp', compact('email'));
    }

    public function resetPasswordOtp(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.'])->onlyInput('email');
        }

        // Update password
        $user->forceFill([
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        return redirect()->route('admin.login')->with('success', 'Password reset successfully! You can now log in with your new password.');
    }
}
