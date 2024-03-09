<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

class TbluserController extends Controller
{
    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "The given data was invalid.",
                "errors" => $validator->errors(),
                "status" => 0,
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                "message" => "The provided credentials are incorrect.",
                "status" => 0,
            ], 401);
        }

        // $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            "user" => $user,
            // "token" => $token,
            "message" => "Login successfully.",
            "status" => 1,
        ], 200);
    }
}