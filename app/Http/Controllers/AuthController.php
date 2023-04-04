<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $validate = Validator::make($request->all(), [
            'email'  => 'required|email:rfc,dns',
            'password' => 'required',
        ]);


        if ($validate->fails()) {
            $respon = [
                'error' => true,
                'status_code' => 401,
                'message' => 'Maaf, Silahkan isi semua form yang tersedia',
                'messages' => $validate->errors(),
            ];
            return response()->json($respon, 401);
        }

        if (!$user) {
            $respon = [
                'error' => true,
                'status_code' => 401,
                'message' => 'Maaf, email yang Anda gunakan tidak terdaftar',
            ];
            return response()->json($respon, 401);
        }

        if ($request->method == 'form') {
            if (!Hash::check($request->password, $user->password)) {
                $respon = [
                    'error' => true,
                    'status_code' => 401,
                    'message' => 'Maaf, email atau kata sandi yang Anda gunakan salah',
                ];
                return response()->json($respon, 401);
            }
        }

        Auth::login($user);

        $tokenResult = $user->createToken('api_token')->plainTextToken;
        $respon = [
            'error' => false,
            'status_code' => 200,
            'message' => 'Selamat! Anda berhasil masuk aplikasi',
            'data' => [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
                'update' => 'ok kah'
            ]
        ];
        return response()->json($respon, 200);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // check if user already exists
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User already exists',
                ]);
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'google_id' => $request->google_id,
                'facebook_id' => $request->facebook_id,
            ]);

            DB::commit();
            return response()->json([
                // 'access_token' => $token,
                // 'token_type' => 'Bearer',
                'status' => 'success',
                'message' => 'User created successfully',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'User created failed',
            ]);
        }
        // $token = $user->createToken('api_token')->plainTextToken;
    }
}
