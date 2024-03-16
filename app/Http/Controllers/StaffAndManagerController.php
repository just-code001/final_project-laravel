<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffAndManagerController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function fetchUsers()
    {
        // Fetch staff and employee details with isDeleted false
        $users = User::where('type', '!=', 'admin')->where('isdeleted', false)->get();

        return response()->json(['users' => $users, "status" => 1], 200);
    }

    public function fetchStaffUsers()
    {
        // Fetch staff and employee details with isDeleted false
        $users = User::where('type', '!=', 'admin')
             ->where('type', '!=', 'manager')
             ->where('isdeleted', false)
             ->get();

        return response()->json(['users' => $users, "status" => 1], 200);
    }


    public function fetchManagerUsers()
    {
        // Fetch staff and employee details with isDeleted false
        $users = User::where('type', '!=', 'admin')
             ->where('type', '!=', 'staff')
             ->where('isdeleted', false)
             ->get();

        return response()->json(['users' => $users, "status" => 1], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255|unique:users,name',
            'email'              => 'required|email|unique:users,email',
            'mobile_no'          => 'required|unique:users,mobile_no|min:10',
            'type'               => 'required|in:staff,manager,admin',
            'password'           => 'required|string|min:6',
            'adhaarcard_details' => 'nullable|string',
            'skills'             => 'nullable|string',
            'salary'             => 'required|numeric|min:0',
            'status'             => 'required|string|max:20',
        ]);

        $userData = [
            'name'               => $request->name,
            'email'              => $request->email,
            'mobile_no'          => $request->mobile_no,
            'type'               => $request->type,
            'password'           => Hash::make($request->password),
            'adhaarcard_details' => $request->adhaarcard_details,
            'skills'             => $request->skills,
            'salary'             => $request->salary,
            'status'             => $request->status,
        ];
        DB::beginTransaction();
        try {
            $user = User::create($userData);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                $user = null,
            ]);

        }

        if ($user != null) {
            $token = $user->createToken('email')->plainTextToken;
            return response([
                'user'    => $user,
                'token'   => $token,
                'message' => 'user created successfully.',
                'status'  => 1,
            ], 201);
        } else {
            return response([
                'message' => 'internal server error.',
                'status'  => 0,
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_id)
    {
        $user = User::where('user_id', $user_id)->where('isDeleted', 0)->first();

        if (!is_null($user)) {
            return response([
                "user"   => $user,
                "status" => 1,
            ], 200);
        } else {
            // $student=null;
            return response([
                "message" => "User doesn't exist or has been deleted.",
                "status"  => 0,
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()
                ->json([
                    'error'  => 'User not found',
                    "status" => 0,
                ], 200);
        }

        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email',
            'mobile_no'          => 'required|min:10',
            'type'               => 'required|in:staff,manager,admin',
            'adhaarcard_details' => 'nullable|string',
            'skills'             => 'nullable|string',
            'salary'             => 'required|numeric|min:0',
            'status'             => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $user->name               = $request->name;
            $user->email              = $request->email;
            $user->mobile_no          = $request->mobile_no;
            $user->type               = $request->type;
            $user->adhaarcard_details = $request->adhaarcard_details;
            $user->skills             = $request->skills;
            $user->salary             = $request->salary;
            $user->status             = $request->status;
            $user->save();

            DB::commit();

            return response([
                "message" => "User Data Updated Successfully.",
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user_id)
    {
        $user = User::find($user_id);
        if (is_null($user)) {
            return response([
                "message" => "user doesn't exist.",
                "status"  => 0,
            ], 200);
        }

        // Check if the user has already been soft deleted
        if ($user->isdeleted) {
            return response()->json(['message' => 'User already deleted', "status" => 1], 200);
        }

        DB::beginTransaction();
        try {
            $user->isdeleted = true;
            $user->save();
            DB::commit();

            return response([
                "message" => "User Deleted Successfully.",
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
}
