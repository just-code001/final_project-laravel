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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createVenue(Request $request)
    {
        // Validate request data including file upload
        $request->validate( [
            'name'             => 'required|string|max:255',
            'venue_category'   => 'required|string|max:255',
            'venue_image'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image upload
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

        // Check if validation fails
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 400);
        // }

        // Handle file upload
        if ($request->hasFile('venue_image')) {
            $image     = $request->file('venue_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $request->merge(['venue_image' => $imageName]);
        }

        $venueData = [
            'name'           => $request->name,
            'venue_category' => $request->venue_category,
            'price'          => $request->price,
            'rating'         => $request->rating,
            'venue_capacity' => $request->venue_capacity,
            'status'         => $request->status,
        ];

        // $venueDetailData = [
        //     'description'      => $request->description,
        //     'city'             => $request->city,
        //     'state'            => $request->state,
        //     'pincode'          => $request->pincode,
        //     'location'         => $request->location,
        //     'contact'          => $request->contact,
        //     'food_facility'    =>$request->food_facility,
        //     'special_facility' =>$request->special_facility,
        // ];
        // Create new venue
        DB::beginTransaction();
        try {
            $venue = Tblvenues::create($venueData);

            // Create new venue detail
            DB::commit();
            // dd($venue);

            // dd($request->merge(['venue_id' => $venue->id]));

            $venueDetail = Tblvenue_detail::create( [
                'venue_id' => $venue->id,
                'description'      => $request->description,
                'city'             => $request->city,
                'state'            => $request->state,
                'pincode'          => $request->pincode,
                'location'         => $request->location,
                'contact'          => $request->contact,
                'food_facility'    =>$request->food_facility,
                'special_facility' =>$request->special_facility,
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                $venue = null,
                $venueDetail = null,
            ]);
        }

        if ($venue != null && $venueDetail != null) {
            // $token = $user->createToken('email')->plainTextToken;
            return response([
                'venue'    => $venue,
                'venueDetail'   => $venueDetail,
                'message' => 'venue created successfully.',
                'status'  => 1,
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tblvenues $tblvenues)
    {
        //
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
    public function update(Request $request, Tblvenues $tblvenues)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tblvenues $tblvenues)
    {
        //
    }
}
