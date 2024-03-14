<?php

namespace App\Http\Controllers;

use App\Models\Tblvenues;
use App\Models\Tblvenue_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TblvenuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function fetchAllVenuesAndDetails()
    {
        $venues = Tblvenues::with('detail')->where('isdeleted', false)->get();
        return response()->json([
            "venues" => $venues,
            "status" => 1,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createVenue(Request $request)
    {
        // Validate request data including file upload
        $request->validate([
            'name'             => 'required|string|max:255',
            'venue_category'   => 'required|string|max:255',
            'venue_image'      => 'nullable|image|mimes:png,jpg,jpeg', // Example validation for image upload
            'price' => 'required|numeric|min:0',
            'rating'           => 'required|numeric|min:0|max:5',
            'venue_capacity'   => 'required|string|max:255',
            'status'           => 'required|string|max:20',
            'description'      => 'nullable|string',
            'city'             => 'required|string|max:255',
            'state'            => 'required|string|max:255',
            'pincode'          => 'required|string|max:255',
            'location'         => 'required|string|max:255',
            'contact'          => 'required|string|max:20',
            'food_facility'    => 'nullable|string',
            'special_facility' => 'nullable|string',
        ]);

        // Handle file upload
        $uploaded = null;
        // dd($request->hasFile('venue_image'));
        if ($request->hasFile('venue_image')) {
            $image = $request->file('venue_image');
            if (!$image->isValid()) {
                return response()->json([
                    'message' => 'The venue image failed to upload.',
                    'errors'  => [
                        'venue_image' => ['The venue image is not valid.'],
                    ],
                ], 400);
            }

            $imageName = time() . '.' . $image->getClientOriginalExtension();
            if (!$image->move(public_path('images'), $imageName)) {
                return response()->json([
                    'message' => 'The venue image failed to upload.',
                    'errors'  => [
                        'venue_image' => ['Failed to move the uploaded image.'],
                    ],
                ], 500);
            }

            $uploaded = $imageName;
        }

        $venueData = [
            'name'           => $request->name,
            'venue_image'    => $uploaded,
            'venue_category' => $request->venue_category,
            'price'          => $request->price,
            'rating'         => $request->rating,
            'venue_capacity' => $request->venue_capacity,
            'status'         => $request->status,
        ];

        // Create new venue
        DB::beginTransaction();
        try {
            $venue = Tblvenues::create($venueData);

            // Create new venue detail
            DB::commit();

            $venueDetail = Tblvenue_detail::create([
                'venue_id'         => $venue->id,
                'description'      => $request->description,
                'city'             => $request->city,
                'state'            => $request->state,
                'pincode'          => $request->pincode,
                'location'         => $request->location,
                'contact'          => $request->contact,
                'food_facility'    => $request->food_facility,
                'special_facility' => $request->special_facility,
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            $ex->getMessage();
            $venue       = null;
            $venueDetail = null;

        }

        if ($venue != null && $venueDetail != null) {
            // $token = $user->createToken('email')->plainTextToken;
            return response([
                'venue'       => $venue,
                'venueDetail' => $venueDetail,
                'message'     => 'venue created successfully.',
                'status'      => 1,
            ], 201);
        } else {
            return response([
                'message' => 'internal server error.',
                'status'  => 0,
            ], 200);
        }

        // return response()->json(['venue' => $venue, 'venue_detail' => $venueDetail], 201);
    }

    /**
     * Display the specified resource.
     */
    // Find venue by ID with its detail
    public function fetchSepcificVenueAndDetail($id)
    {
        // Find venue by ID with its detail
        $venue = Tblvenues::with('detail')->where('isDeleted', false)->find($id);

        // Check if venue exists
        if (!$venue) {
            return response()->json([
                'error'  => 'Venue not found',
                'status' => 0,
            ], 200);
        }

        return response(["venue" => $venue, "status" => 1], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tblvenues $tblvenues)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateVenue(Request $request, $id)
    {

        // Find venue by ID
        $venue = Tblvenues::find($id);
        // dd($venue);
        // Check if venue exists
        if (!$venue) {
            return response()->json(['error' => 'Venue not found'], 200);
        }

        // Validate request data including file upload
        $request->validate([
            'name'             => 'required|string|max:255',
            'venue_category'   => 'required|string|max:255',
            'venue_image'      => 'nullable', // Example validation for image upload
            'price'            => 'required|numeric|min:0',
            'rating'           => 'required|numeric|min:0|max:5',
            'venue_capacity'   => 'required|string|max:255',
            'status'           => 'required|string|max:20',
            'description'      => 'nullable|string',
            'city'             => 'required|string|max:255',
            'state'            => 'required|string|max:255',
            'pincode'          => 'required|string|max:255',
            'location'         => 'required|string|max:255',
            'contact'          => 'required|string|max:20',
            'food_facility'    => 'nullable|string',
            'special_facility' => 'nullable|string',
        ]);

        // Handle file upload
        $uploaded = null;
        if ($request->hasFile('venue_image')) {
            $image     = $request->file('venue_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $uploaded = $imageName;
        }

        DB::beginTransaction();
        try {
            $venue->update([
                'name'           => $request->name,
                'venue_image'    => $uploaded,
                'venue_category' => $request->venue_category,
                'price'          => $request->price,
                'rating'         => $request->rating,
                'venue_capacity' => $request->venue_capacity,
                'status'         => $request->status,
            ]);

            // Update or create venue detail
            $venue->detail()->updateOrCreate([], [
                'description'      => $request->description,
                'city'             => $request->city,
                'state'            => $request->state,
                'pincode'          => $request->pincode,
                'location'         => $request->location,
                'contact'          => $request->contact,
                'food_facility'    => $request->food_facility,
                'special_facility' => $request->special_facility,
            ]);

            DB::commit();

            return response([
                "message" => "Venue Data Updated Successfully.",
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
    public function destroyVenueAndDetail(string $id)
    {
        // Find venue by ID
        $venue = Tblvenues::find($id);
        if (is_null($venue)) {
            return response()->json([
                "message" => "Venue doesn't exist.",
                "status"  => 0,
            ], 200);
        }

        // Check if the venue has already been soft deleted
        if ($venue->isdeleted) {
            return response()->json([
                "message" => "Venue already deleted",
                "status"  => 1,
            ], 200);
        }

        DB::beginTransaction();
        try {
            // Soft delete the venue
            $venue->isdeleted = true;
            $venue->save();

            // Soft delete associated venue detail if exists
            $venueDetail = Tblvenue_detail::where('venue_id', $id)->first();
            if ($venueDetail) {
                $venueDetail->isDeleted = true;
                $venueDetail->save();
            }

            DB::commit();

            return response()->json([
                "message" => "Venue and Venue Detail Deleted Successfully.",
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

}
