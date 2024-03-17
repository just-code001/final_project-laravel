<?php

namespace App\Http\Controllers;

use App\Mail\setOtpMail;
use App\Models\Tblclient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TblclientController extends Controller
{

    // register new client --------------------------
    public function register(Request $request)
    {

        $request->validate([
        'firstname'=>"required",
        'lastname'=>"required",
        'email'=>"required|unique:tblclients,email",
        'phno'=>"required|numeric|min:10",
        'password'=>"required|min:8|confirmed",
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
            'email'     => "required|email",
            'otp'   => 'required|digits:6',
            ]);

        $client = Tblclient::where('email', $request->email)->first();
        // dd($client->email);

        if ($client && Hash::check($request->otp, $client->otp)) {
            // OTP verification successful, proceed with client registration
            $affectedRows = Tblclient::whereNull('email_verified_at')->update([
                'email_verified_at' => strtotime(now()),
            ]);

            return response()->json(["client"=>$client,'message' => 'OTP verification successful', 'status' => 1, "affectef_row" => $affectedRows], 200);
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
                "admin"=>false
            ], 200);

        }
    }

    public function fetchClients()
    {
        // Fetch staff and employee details with isDeleted false
        $client = Tblclient::all();

        return response()->json(['client' => $client, "status" => 1], 200);
    }

}
