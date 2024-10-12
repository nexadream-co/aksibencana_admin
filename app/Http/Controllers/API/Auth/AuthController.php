<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login Manual
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => $error,
                ],
                400
            );
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'Your email or password is incorrect.',
                    'data' => null,
                ],
                400
            );
        }

        // if (!$user->hasVerifiedEmail()) {
        //     return response()->json(
        //         [
        //             'status' => 'unverified',
        //             'message' => 'Your account is still not active, please check your email inbox to verify. If not, please check the SPAM folder.',
        //             'data' => null,
        //         ],
        //         200
        //     );
        // }

        $token = $user->createToken($request->device_name ?? "android")->plainTextToken;

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Login berhasil.',
                'data' => [
                    'token' => $token,
                ],
            ],
            200
        );
    }

    /**
     * Register new account
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => $error,
                ],
                200
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // $user->sendEmailVerificationNotification();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Register successfully, please log in using your new account.',
                'data' => null
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                "status" => "success",
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
            "message" => "Data user tidak ditemukan."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
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
                'status' => 'success',
                'message' => 'You have successfully logged out.',
            ],
            200
        );
    }

    /**
     * Handle a request verification email for user.
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
                    'status' => 'failed',
                    'message' => $error,
                ],
                200
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
                        'status' => 'failed',
                        'message' =>
                        'If you have previously requested verification, please check your incoming email messages, including spam and promotional folders. You can send a verification request again after ' .
                            $seconds .
                            ' seconds',
                        'data' => null,
                    ],
                    200
                );
            } else {
                RateLimiter::hit($key, $decay);
                $user->sendEmailVerificationNotification();
                return response()->json(
                    [
                        'status' => 'success',
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
                'status' => 'success',
                'message' => 'Akun kamu sudah aktif.',
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
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
                    'status' => 'failed',
                    'message' => $error,
                ],
                200
            );
        }
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = Password::broker()->sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? response()->json(
                [
                    'status' => 'success',
                    'message' =>
                    'The password reset link has been successfully sent to your email, please check your email inbox.',
                ],
                200
            )
            : response()->json(
                [
                    'status' => 'failed',
                    'message' =>
                    'You have requested a password reset some time ago, please check your email again.',
                ],
                200
            );
    }
}
