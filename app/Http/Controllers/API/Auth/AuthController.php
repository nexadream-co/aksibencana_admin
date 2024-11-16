<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Login Email Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @unauthenticated
     */
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'string',
                'device_token' => 'string|required',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(
                [
                    'message' => $error,
                ],
                400
            );
        }

        // Store firebase device token
        // code here...

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'message' => 'Your email or password is incorrect.',
                ],
                400
            );
        }

        if (@$user->getRoleNames()[0] != 'user') {
            return response()->json(
                [
                    'message' => 'you do not have the right to access',
                ],
                400
            );
        }

        $token = $user->createToken($request->device_name ?? "android")->plainTextToken;

        return response()->json(
            [
                'message' => 'Login berhasil',
                'data' => [
                    'token' => $token,
                ],
            ],
            200
        );
    }

    /**
     * Register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @unauthenticated
     */
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'device_name' => 'string',
                'device_token' => 'string|required',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(
                [
                    'message' => $error,
                ],
                400
            );
        }

        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($request->device_name ?? "android")->plainTextToken;

        $user->assignRole('user');

        DB::commit();

        return response()->json(
            [
                'message' => 'Register successfully.',
                'data' => [
                    'token' => $token
                ]
            ],
            200
        );
    }

    /**
     * Detail User
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                "message" => "User data has been successfully obtained.",
                "data" => [
                    "id" => $request->user()->id,
                    "name" => $request->user()->name,
                    "email" => $request->user()->email,
                    "photo_url" => $request->user()->photo_url,
                ]
            ], 200);
        }

        return response()->json([
            "status" => "failed",
            "message" => "User data not found."
        ], 400);
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json(
            [
                'message' => 'You have successfully logged out.',
            ],
            200
        );
    }

    /**
     * Remove Account
     *
     * @return \Illuminate\Http\Response
     */
    public function removeAccount(Request $request)
    {
        DB::beginTransaction();
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        $request->user()->email = Str::random(40) . '@aksibencana.com';
        $request->user()->save();

        DB::commit();

        return response()->json(
            [
                'message' => 'Your account successfully removed.',
            ],
            200
        );
    }

    /**
     * Resend Verification
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendVerification(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ],
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(
                [
                    'message' => $error,
                ],
                400
            );
        }
        $user = User::where('email', $request->email)->first();
        if (!$user->hasVerifiedEmail()) {
            $key = 'send-email.' . $user->id;
            $max = 1;
            $decay = 300;

            if (RateLimiter::tooManyAttempts($key, $max)) {
                $seconds = RateLimiter::availableIn($key);
                return response()->json(
                    [
                        'message' =>
                        'If you have previously requested verification, please check your incoming email messages, including spam and promotional folders. You can send a verification request again after ' .
                            $seconds .
                            ' seconds',
                        'data' => null,
                    ],
                    400
                );
            } else {
                RateLimiter::hit($key, $decay);
                $user->sendEmailVerificationNotification();
                return response()->json(
                    [
                        'message' =>
                        'A verification email has been sent, please check your email inbox to verify.',
                        'data' => null,
                    ],
                    200
                );
            }
        }

        return response()->json(
            [
                'message' => 'Akun kamu sudah aktif.',
            ],
            200
        );
    }

    /**
     * Forgot Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @unauthenticated
     */
    public function sendEmailResetPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ],
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(
                [
                    'message' => $error,
                ],
                400
            );
        }
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = Password::broker()->sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? response()->json(
                [
                    'message' =>
                    'The password reset link has been successfully sent to your email, please check your email inbox.',
                ],
                200
            )
            : response()->json(
                [
                    'message' =>
                    'You have requested a password reset some time ago, please check your email again.',
                ],
                400
            );
    }
}
