<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            return ResponseFormatter::success($request->user(), 'User data retrieved successfully!');
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|max:255',
            ];

            if ($request->username != auth()->user()->username) {
                $rules['username'] = 'required|String|max:255|unique:users';
            }
            if ($request->phone_number != auth()->user()->phone_number) {
                $rules['phone_number'] = 'required|String|min:9|unique:users';
            }
            if ($request->email != auth()->user()->email) {
                $rules['email'] = 'required|String|email|max:255|unique:users';
            }

            $validatedData = $request->validate($rules);

            if ($request->password) {
                $validatedData['password'] = bcrypt($request->password);
            }

            $user = User::where('id', auth()->user()->id)->update($validatedData);

            return ResponseFormatter::success($user, 'User updated successfully!');
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }
}
