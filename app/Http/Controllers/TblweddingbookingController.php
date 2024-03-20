<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tblweddingbooking;
use Illuminate\Support\Facades\DB;

class TblweddingbookingController extends Controller
{
    public function createWeddingBooking(Request $request)
    {
        $request->validate([
            'client_id'       => 'required|exists:tblclients,client_id',
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|max:100',
            'address'         => 'required|string|max:255',
            'contact_number'  => 'required|string|max:20',
            'city'            => 'required|string|max:100',
            'guest_list'      => 'required|string|max:255',
            'specialServices' => 'nullable|string',
        ]);

        $bookingData = [
            'client_id'       => $request->client_id,
            'name'            => $request->name,
            'email'           => $request->email,
            'address'         => $request->address,
            'contact_number'  => $request->contact_number,
            'city'            => $request->city,
            'guest_list'      => $request->guest_list,
            'specialServices' => $request->specialServices,
        ];

        DB::beginTransaction();
        try {

            $wedding = Tblweddingbooking::create($bookingData);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message"=>$ex->getMessage(),
                $wedding = null,
            ]);

        }

        if ($wedding != null) {
            return response([
                'wedding'    => $wedding,
                'message' => 'Wedding booking done successfully.',
                'info'=>'We Will Contact You For Further Information.',
                'status'  => 1,
            ], 201);
        } else {
            return response([
                'message' => 'internal server error.',
                'status'  => 0,
            ], 200);
        }
    }

    public function fetchBookingsForAdmin()
    {
        // Fetch all venue bookings
        $bookings = Tblweddingbooking::with('client')->get();

        // Return the bookings data
        return response()->json(['bookings' => $bookings, "status" => 0], 200);
    }
}
