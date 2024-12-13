<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
                'role' => 'string|nullable',
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
                    'message' => 'Your email or password is incorrect',
                ],
                400
            );
        }

        if (@$request->role && @$user->getRoleNames()[0] != 'courier') {
            return response()->json(
                [
                    'message' => 'you do not have the right to access',
                ],
                400
            );
        }

        if (!in_array(@$user->getRoleNames()[0], ['courier', 'user'])) {
            return response()->json(
                [
                    'message' => 'you do not have the right to access',
                ],
                400
            );
        }

        $user->fcm_token = $request->device_token;
        $user->save();

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
            'fcm_token' => $request->device_token
        ]);

        $token = $user->createToken($request->device_name ?? "android")->plainTextToken;

        $user->assignRole('user');

        DB::commit();

        return response()->json(
            [
                'message' => 'Register successfully',
                'data' => [
                    'token' => $token
                ]
            ],
            200
        );
    }

    /**
     * Login by Google
     * @unauthenticated
     */

    public function loginByGoogle(Request $request)
    {
        $request->validate([
            'token' => ['string', 'required'],
            'device_token' => 'string|required',
        ]);

        $userGoogle = null;

        try {
            // https://www.raziel619.com/blog/authentication-between-a-flutter-app-and-laravel-api-using-socialite-and-sanctum/
            $userGoogle = Socialite::driver('google')->stateless()->userFromToken($request->token);
        } catch (\Throwable $th) {
            return response()->json(
                ['message' => 'Login gagal, kredensial akun Google anda tidak valid'],
                400
            );
        }

        if (!@$userGoogle) {
            return response()->json(
                ['message' => 'Login gagal, kredensial akun Google anda tidak ditemukan'],
                400
            );
        }

        $user = User::where('email', @$userGoogle->email)->first();

        if (!@$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'fcm_token' => $request->device_token
            ]);


            $user->assignRole('user');
        } else {
            $user->fcm_token = $request->device_token;
            $user->save();
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
     * Update User
     */
    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'date_of_birth' => ['string'],
            'photo_url' => ['required', 'string'],
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->date_of_birth = $request->date_of_birth;
        $user->photo_url = $request->photo_url;
        $user->save();

        return response()->json(
            [
                'message' => 'Profile successfully updated'
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
            $volunteer = Volunteer::where('user_id', $request->user()->id)->first();
            $user = User::find($request->user()->id);
            $delivery = Delivery::where('delivery_by', $user->id)->where('status', 'active')->latest()->first();

            $branch_office = null;

            if (@$user->getRoleNames()[0] == 'courier') {
                $branch_office = @$user->branchOffice ? [
                    "id" => @$user->branchOffice->id,
                    "name" => @$user->branchOffice->name,
                ] : null;
            }

            return response()->json([
                "message" => "User data has been successfully retrieved",
                "data" => [
                    "id" => $request->user()->id,
                    "name" => $request->user()->name,
                    "email" => $request->user()->email,
                    "address" => @$volunteer->address,
                    "photo_url" => $request->user()->photo_url,
                    "date_of_birth" => @$volunteer->date_of_birth ?? @$user->date_of_birth,
                    "is_volunteer" => @$volunteer != null,
                    "branch_office" => $branch_office,
                    "role" => @$user->getRoleNames()[0],
                    "assignment" => @$delivery ? [
                        "id" => @$delivery->id,
                        "status" => @$delivery->status,
                    ] : null,
                ]
            ], 200);
        }

        return response()->json([
            "status" => "failed",
            "message" => "User data not found"
        ], 400);
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        DB::beginTransaction();
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        $request->user()->device_token = null;
        $request->user()->save();

        DB::commit();

        return response()->json(
            [
                'message' => 'You have successfully logged out',
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
                'message' => 'Your account successfully removed',
            ],
            200
        );
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Current password does not match!',
                ],
                400
            );
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(
            ['message' => 'Your password has been changed'],
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
                    'The password reset link has been successfully sent to your email, please check your email inbox',
                ],
                200
            )
            : response()->json(
                [
                    'message' =>
                    'You have requested a password reset some time ago, please check your email again',
                ],
                400
            );
    }
}
