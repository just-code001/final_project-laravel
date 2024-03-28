<?php

namespace App\Http\Controllers;

use App\Models\Tblconcert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TblconcertController extends Controller
{
    public function createConcert(Request $request)
    {

        $request->validate([
            'event_name'      => 'required|string|max:255',
            'singer'          => 'required|string|max:255',
            'event_timing'    => 'required|string|max:255',
            'concert_date'    => 'required|date',
            'city'            => 'required|string|max:255',
            'state'           => 'required|string|max:255',
            'pincode'         => 'required|string|max:10',
            'location'        => 'required|string|max:255',
            'description'     => 'nullable|string',
            'ticket_type1'    => 'required|string|max:50',
            'ticket_pricing1' => 'required|numeric',
            'ticket_type2'    => 'nullable|string|max:50',
            'ticket_pricing2' => 'nullable|numeric',
            'ticket_type3'    => 'nullable|string|max:50',
            'ticket_pricing3' => 'nullable|numeric',
            'concert_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Handle file upload
        $uploaded = null;
        if ($request->hasFile('concert_image')) {
            $image     = $request->file('concert_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/concert_images'), $imageName);
            $uploaded = $imageName;
        }

        $concertData = [
            'event_name'      => $request->event_name,
            'singer'          => $request->singer,
            'event_timing'    => $request->event_timing,
            'concert_date'    => $request->concert_date,
            'city'            => $request->city,
            'state'           => $request->state,
            'pincode'         => $request->pincode,
            'location'        => $request->location,
            'description'     => $request->description,
            'ticket_type1'    => $request->ticket_type1,
            'ticket_pricing1' => $request->ticket_pricing1,
            'ticket_type2'    => $request->ticket_type2,
            'ticket_pricing2' => $request->ticket_pricing2,
            'ticket_type3'    => $request->ticket_type3,
            'ticket_pricing3' => $request->ticket_pricing3,
            'concert_image'   => $uploaded,
        ];

        // Create new concert
        DB::beginTransaction();
        try {
            $concert = Tblconcert::create($concertData);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message"=>$ex->getMessage(),
                $concert       = null
            ]);
            

        }

        if ($concert != null) {
            return response([
                'concert'       => $concert,
                'message'     => 'concert created successfully.',
                'status'      => 1,
            ], 201);
        } else {
            return response([
                'message' => 'internal server error.',
                'status'  => 0,
            ], 200);
        }
    }

    public function updateConcert(Request $request, $id){
        // Find concert by ID
        $concert = Tblconcert::find($id);
        // dd($concert);
        // Check if concert exists
        if (!$concert) {
            return response()->json(['error' => 'concert not found'], 200);
        }

        $request->validate([
            'event_name'      => 'required|string|max:255',
            'singer'          => 'required|string|max:255',
            'event_timing'    => 'required|string|max:255',
            'concert_date'    => 'required|date|after_or_equal:today',
            'city'            => 'required|string|max:255',
            'state'           => 'required|string|max:255',
            'pincode'         => 'required|string|max:10',
            'location'        => 'required|string|max:255',
            'description'     => 'nullable|string',
            'ticket_type1'    => 'required|string|max:50',
            'ticket_pricing1' => 'required|numeric',
            'ticket_type2'    => 'nullable|string|max:50',
            'ticket_pricing2' => 'nullable|numeric',
            'ticket_type3'    => 'nullable|string|max:50',
            'ticket_pricing3' => 'nullable|numeric',
            'concert_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Handle file upload
        $uploaded = null;
        if ($request->hasFile('concert_image')) {
            $image     = $request->file('concert_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/concert_images'), $imageName);
            $uploaded = $imageName;
        }

        DB::beginTransaction();
        try {
            $concert->update([
                'event_name'      => $request->event_name,
            'singer'          => $request->singer,
            'event_timing'    => $request->event_timing,
            'concert_date'    => $request->concert_date,
            'city'            => $request->city,
            'state'           => $request->state,
            'pincode'         => $request->pincode,
            'location'        => $request->location,
            'description'     => $request->description,
            'ticket_type1'    => $request->ticket_type1,
            'ticket_pricing1' => $request->ticket_pricing1,
            'ticket_type2'    => $request->ticket_type2,
            'ticket_pricing2' => $request->ticket_pricing2,
            'ticket_type3'    => $request->ticket_type3,
            'ticket_pricing3' => $request->ticket_pricing3,
            'concert_image'   => $uploaded,
            ]);

            DB::commit();

            return response([
                "message" => "concert Data Updated Successfully.",
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

    public function destroyConcert(Request $request, string $id){
        // Find concert by ID
        $concert = Tblconcert::find($id);
        if (is_null($concert)) {
            return response()->json([
                "message" => "concert doesn't exist.",
                "status"  => 0,
            ], 200);
        }

        // Check if the concert has already been soft deleted
        if ($concert->isdeleted) {
            return response()->json([
                "message" => "concert already deleted",
                "status"  => 1,
            ], 200);
        }

        DB::beginTransaction();
        try {
            // Soft delete the concert
            $concert->isdeleted = true;
            $concert->save();

            DB::commit();

            return response()->json([
                "message" => "concert and concert Detail Deleted Successfully.",
                "status"  => 1,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                "message" => "Internal Server Error" . $ex->getMessage(),
                "status"  => 0,
            ], 500);
        }
    }

    public function fetchAllConcerts()
    {
        $concerts = Tblconcert::where('isdeleted', false)->get();
        return response()->json([
            "concerts" => $concerts,
            "status" => 1,
        ], 200);
    }
    
    public function fetchSepcificConcert($id)
    {
        // Find concert by ID with its detail
        $concert = Tblconcert::where('isDeleted', false)->find($id);

        // Check if concert exists
        if (!$concert) {
            return response()->json([
                'error'  => 'concert not found',
                'status' => 0,
            ], 200);
        }

        return response(["concert" => $concert, "status" => 1], 200);
    }


}
