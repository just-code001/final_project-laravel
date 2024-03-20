<?php

namespace App\Http\Controllers;

use App\Models\Tblbirthdaybooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TblbirthdaybookingController extends Controller
{
    public function createBirthdayBooking(Request $request)
    {
        $request->validate([
            'client_id'      => 'required|exists:tblclients,client_id',
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|max:100',
            'address'        => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'city'           => 'required|string|max:100',
            'guest_list'     => 'required|string|max:255',
            'package_name'   => 'required|string|max:255',
            'theme'          => 'required|string|max:255',
        ]);

        $bookingData = [
            'client_id'      => $request->client_id,
            'name'           => $request->name,
            'email'          => $request->email,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
            'city'           => $request->city,
            'guest_list'     => $request->guest_list,
            'package_name'   => $request->package_name,
            'theme'          => $request->theme,
        ];

        DB::beginTransaction();
        try {

            $birthday = Tblbirthdaybooking::create($bookingData);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                $birthday = null,
            ]);

        }

        if ($birthday != null) {
            return response([
                'birthday' => $birthday,
                'message' => 'Birthday booking done successfully.',
                'info'    => 'We Will Contact You For Further Information.',
                'status'  => 1,
            ], 201);
        } else {
            return response([
                'message' => 'internal server error.',
                'status'  => 0,
            ], 200);
        }
    }

    public function fetchBirthdayBookingForAdmin()
    {
        // Fetch all venue bookings
        $bookings = Tblbirthdaybooking::with('client')->get();

        // Return the bookings data
        return response()->json(['bookings' => $bookings, "status" => 0], 200);
    }
}
