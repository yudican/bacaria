<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // profile
    public function profile()
    {
        $user = auth()->user();
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user)
        ], 200);
    }

    // update profile
    public function updateProfile(Request $request)
    {
        if (!$request->hasFile('photo')) {
            return response()->json([
                'error' => true,
                'message' => 'File not found',
                'status_code' => 400,
            ], 400);
        }
        $file = $request->file('photo');
        if (!$file->isValid()) {
            return response()->json([
                'error' => true,
                'message' => 'Image file not valid',
                'status_code' => 400,
            ], 400);
        }
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $data = [
                'email'  => $request->email,
                'name'  => $request->name,
            ];

            if ($request->photo) {
                $user_profile_photo = $request->photo->store('upload', 'public');
                $data['profile_photo_path'] = $user_profile_photo;
                if (Storage::exists('public/' . $user->profile_photo_path)) {
                    Storage::delete('public/' . $user->profile_photo_path);
                }
            }

            $user->update($data);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data profil',
                'data' => new UserResource($user)
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah data profil',
                'data' => $th->getMessage()
            ], 400);
        }
    }

    // update password
    public function updatePassword(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password lama tidak sesuai'
                ], 400);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah password'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah password'
            ], 400);
        }
    }
}
