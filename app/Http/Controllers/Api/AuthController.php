<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function passwordResetRequest(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->reset_key = rand(10000, 99999);
        $user->save();

        Mail::raw("Your Password Reset Key is: {$user->reset_key}", function ($message) use ($user) {
            $message->from('no-reply@tailors.com', 'Tailors Team');
            $message->subject('Password Reset Key');
            $message->to($user->email);
        });

        return response()->json([
            'data' => [
                'message' => 'Password reset key sent to your email',
            ],
        ]);
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email|exists:users',
            'reset_key' => 'required',
            'password'  => 'required',
        ]);

        $user = User::where([
            ['reset_key', $request->reset_key],
            ['email', $request->email],
        ])->first();

        if (!$user) {
            return response()->json([
                'data' => [
                    'message' => 'Email and Reset Key does not match.',
                ],
            ], 422);
        }

        $user->reset_key = null;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'data' => [
                'message' => 'Password changed successfully.',
            ],
        ]);
    }
}
