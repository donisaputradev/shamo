<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'username' => 'required|max:255|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'phone_number' => 'required|max:255|unique:users',
                'password' => 'required|min:6',
            ]);

            $validatedData['password'] = bcrypt($validatedData['password']);

            $user = User::create($validatedData);

            $token = $user->createToken($user->email)->plainTextToken;

            return ResponseFormatter::success(
                ['access_token' => $token, 'token_type' => 'Bearer', 'user' => $user],
                'Authentication Success',
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required|exists:users',
                'password' => 'required|min:6',
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error(null, 'Authentication Invalid', 401);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                return ResponseFormatter::error(null, 'Invalid Credentials', 401);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success(
                ['access_token' => $token, 'token_type' => 'Bearer', 'user' => $user],
                'Authentication Success',
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }
}
