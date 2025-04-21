<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    public function index()
    {
        $admins = User::where('role', 'admin')->get();

        return response()->json([
            'admins' => $admins->map(function($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->role,
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at
                ];
            })
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|min:6'
        ]);

        $user->update([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Admin updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6'
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    public function requestTokenForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', 'admin')
                    ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
        }

        // Generate random token
        $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);

        // Save token to user with 30 seconds expiry
        $user->update([
            'reset_token' => $token,
            'reset_token_expiry' => now()->addSeconds(30)
        ]);

        // Send email using Resend API with SSL verification disabled
        $response = Http::withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer re_RS4BGVvJ_FAVdNzRbqjag2BS3C5G5zAMq',
                'Content-Type' => 'application/json',
            ])->post('https://api.resend.com/emails', [
                'from' => 'onboarding@resend.dev',
                'to' => $request->email,
                'subject' => 'Reset Password - LendEase',
                'html' => "<p>Hi there,</p><p>We received a request to reset your password for your <strong>LendEase</strong> account.</p><p>Your password reset token is:</p><h2>{$token}</h2><p>If you didn't request this, you can safely ignore this email.</p><p>Thanks, <br>The LendEase Team</p>"
            ]);

        return response()->json([
            'message' => "A reset token has been sent to your email address, the token will expire in 30 seconds",
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);

        // First check if token exists
        $user = User::where('reset_token', $request->token)
                    ->where('role', 'admin')
                    ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid token'
            ], 400);
        }

        // Then check if token is expired
        if ($user->reset_token_expiry <= now()) {
            return response()->json([
                'message' => 'Token has expired'
            ], 400);
        }

        // Update user with new password
        $user->update([
            'password' => Hash::make($request->new_password),
            'reset_token' => null,
            'reset_token_expiry' => null
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'Admin deleted successfully'
        ]);
    }
}
