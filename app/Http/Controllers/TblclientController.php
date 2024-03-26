<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Mail\setOtpMail;
use App\Models\Tblclient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TblclientController extends Controller
{

    // register new client --------------------------
    public function register(Request $request)
    {

        $request->validate([
            'firstname' => "required",
            'lastname'  => "required",
            'email'     => "required|unique:tblclients,email",
            'phno'      => "required|numeric|min:10",
            'password'  => "required|min:8|confirmed",
        ]);

        // create otp
        $otp = mt_rand(100000, 999999);

        $data = [
            "firstname" => $request->firstname,
            "lastname"  => $request->lastname,
            "email"     => $request->email,
            "phno"      => $request->phno,
            "password"  => Hash::make($request->password),
            "otp"       => Hash::make($otp),
        ];

        // dd($data);
        DB::beginTransaction();
        try {
            // add user details to database
            $client = Tblclient::create($data);

            $token = $client->createToken($request->email)->plainTextToken;

            DB::commit();
            // send otp via mail
            $otpDetails = [
                "title" => "mail come from Just Code",
                "body"  => "please use this below otp code for register as user.",
                "otp"   => $otp,
            ];
            Mail::to($request->email)->send(new setOtpMail($otpDetails));

            return response([
                "client"  => $client,
                "token"   => $token,
                "message" => "Otp sent Successfully.",
                "status"  => 1,
            ]);
        } catch (\Exception $ex) {
            return response([
                "message" => $ex->getMessage(),
                "status"  => 0,
            ], 200);
        }
    }

    // verify otp on registration--------------------
    public function verifyOtp(Request $request)
    {
        // Validate request data...
        $request->validate([
            'email' => "required|email",
            'otp'   => 'required|digits:6',
        ]);

        $client = Tblclient::where('email', $request->email)->first();
        // dd($client->email);

        if ($client && Hash::check($request->otp, $client->otp)) {
            // OTP verification successful, proceed with client registration
            $affectedRows = Tblclient::whereNull('email_verified_at')->update([
                'email_verified_at' => strtotime(now()),
            ]);

            return response()->json(["client" => $client, 'message' => 'OTP verification successful', 'status' => 1, "affectef_row" => $affectedRows], 200);
        } else {
            // OTP verification failed
            return response()->json(['message' => 'Invalid OTP', 'status' => 0], 200);
        }
    }

    // login for existing client -------------------------
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'    => "required|email",
            'password' => "required|min:8",
        ]);

        if ($validation->fails()) {
            return response([
                "errors" => $validation->messages(),
                "status" => 0,
            ], 422);
        } else {

            $client = Tblclient::where('email', $request->email)->first();

            if ($client && Hash::check($request->password, $client->password)) {
                // $token = $client->createToken($request->email)->plainTextToken;
                return response([
                    "client"  => $client,
                    "message" => "login successfully.",
                    "status"  => 1,
                ], 200);
            }

            return response([
                "message" => "provided credentials are incorrect.",
                "status"  => 0,
                "admin"   => false,
            ], 200);

        }
    }

    public function fetchClients()
    {
        // Fetch staff and employee details with isDeleted false
        $client = Tblclient::all();

        return response()->json(['client' => $client, "status" => 1], 200);
    }

    public function updateProfile(Request $request, string $client_id)
    {
        // Find the client by their ID
        $client = Tblclient::find($client_id);

        if (!$client) {
            return response()
                ->json([
                    'error'  => 'client not found',
                    "status" => 0,
                ], 200);
        }

        // Validate the incoming request data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:tblclients,email',
            'phno'      => 'required|string|max:15|unique:tblclients,phno',
            // You may add more validation rules as needed
        ]);

        // Update the client's profile with the validated data
        DB::beginTransaction();
        try {
            $client->firstname = $request->firstname;
            $client->lastname  = $request->lastname;
            $client->email     = $request->email;
            $client->phno      = $request->phno;
            $client->save();

            DB::commit();

            return response([
                "client"  => $client,
                "message" => "Client Data Updated Successfully.",
                "status"  => 1,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                "status"  => 0,
            ]);
        }
    }

    public function ftechSingleClient($id)
    {
        // Find venue by ID with its detail
        $client = Tblclient::find($id);

        // Check if client exists
        if (!$client) {
            return response()->json([
                'error'  => 'client not found',
                'status' => 0,
            ], 200);
        }

        return response(["client" => $client, "status" => 1], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $client = Tblclient::where('email', $request->email)->first();

        if (!$client) {
            return response()->json(['message' => 'Client not found', 'status' => 0], 200);
        }

        // Generate a random token
        $token = Str::random(60);

        // Update the client's reset password token
        // $client->update(['reset_password_token' => $token]);
        $client->reset_password_token = $token;
        $client->save();
        // Send password reset email
        Mail::to($client->email)->send(new PasswordResetMail($token));

        return response()->json(['message' => 'Password reset link sent to your email', 'status' => 1, 'token' => $token], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $client = Tblclient::where('reset_password_token', $request->token)
            ->first();

        if (!$client) {
            return response()->json(['message' => 'Invalid token', 'status' => 0], 200);
        }

        // Update the password
        $client->password             = Hash::make($request->password);
        $client->reset_password_token = null; // Clear the reset token
        $client->save();

        return response()->json(['message' => 'Password reset successfully', 'status' => 1], 200);
    }

}
