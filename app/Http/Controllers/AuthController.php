<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();


        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {

            return redirect('login')->with(['error' => 'Silahkan isi semua form yang tersedia']);
        }

        if (!$user) {
            return redirect('login')->with(['error' => 'Username tidak terdaftar']);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return redirect('login')->with(['error' => 'Username atau password salah']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect('login')->with(['error' => 'Unathorized, password yang kamu masukkan tidak sesuai']);
        }


        return redirect('dashboard');
    }
    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginApi(Request $request)
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

            if ($user->google_id) {
                $respon = [
                    'error' => true,
                    'status_code' => 401,
                    'message' => 'Maaf, email yang Anda gunakan sudah terdaftar dengan akun Google',
                ];
                return response()->json($respon, 401);
            }

            if ($user->facebook_id) {
                $respon = [
                    'error' => true,
                    'status_code' => 401,
                    'message' => 'Maaf, email yang Anda gunakan sudah terdaftar dengan akun Facebook',
                ];
                return response()->json($respon, 401);
            }

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

            $createdUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => explode('@', $request->email)[0],
                'password' => Hash::make($request->password),
                'google_id' => $request->google_id,
                'facebook_id' => $request->facebook_id,
                'profile_photo_path' => $request->photoURL ?? null,
            ]);
            $team = Team::find(1);
            $team->users()->attach($createdUser, ['role' => 'member']);
            $createdUser->roles()->attach('0feb7d3a-90c0-42b9-be3f-63757088cb9a');

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
                'error' => $th->getMessage()
            ], 400);
        }
        // $token = $user->createToken('api_token')->plainTextToken;
    }
}
