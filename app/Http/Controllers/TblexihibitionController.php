<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Tblexihibition;

use Illuminate\Http\Request;

class TblexihibitionController extends Controller
{
    public function createExihibition(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'event_name' => 'required|string|max:255',
            'type' => 'required|string|max:100|in:Art Exihibition,Car Exihibition',
            'exhibition_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_pricing' => 'nullable|numeric',
            'event_starting_date' => 'required|date',
            'event_ending_date' => 'required|date|after_or_equal:event_starting_date',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
        ]);


          // Handle file upload
          $uploaded = null;
          if ($request->hasFile('exhibition_image')) {
              $image     = $request->file('exhibition_image');
              $imageName = time() . '.' . $image->getClientOriginalExtension();
              $image->move(public_path('images'), $imageName);
              $uploaded = $imageName;
          }

         $exihibitionData=[
            'event_name' => $request->event_name,
            'type' => $request->type,
            'exhibition_image' => $uploaded,
            'event_pricing' => $request->event_pricing,
            'event_starting_date' => $request->event_starting_date,
            'event_ending_date' => $request->event_ending_date,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
        ];

         // Create new exihibition
         DB::beginTransaction();
         try {
             $exihibition = Tblexihibition::create($exihibitionData);
 
             // Create new exihibitionn detail
             DB::commit();
 
            //  $exihibitionDetail = Tblexihibition::create([
            //     'event_name' => $request->event_name,
            //     'type' => $request->type,
            //     'exhibition_image' => $uploaded,
            //     'event_pricing' => $request->event_pricing,
            //     'event_starting_date' => $request->event_starting_date,
            //     'event_ending_date' => $request->event_ending_date,
            //     'location' => $request->location,
            //     'city' => $request->city,
            //     'state' => $request->state,
            // ]);
 
             DB::commit();
         } catch (\Exception $ex) {
             DB::rollBack();
 
             $ex->getMessage();
             $exihibition = null;
        
 
         }

        // Redirect the user or do something else (e.g., display a success message)
        return redirect()->back()->with('success', 'Exhibition created successfully.');
        
    
    }

    //fetch all data

    public function fetchAllExihibition()
    {
        $exihibition = Tblexihibition::where('isdeleted', false)->get();
        return response()->json([
            "exihibition" => $exihibition,
            "status" => 1,
        ], 200);
    }

    //fetch single data
    public function fetchSpecificExihibition($id)
    {
        // Find exihibitin by ID with its detail
        $exihibition = Tblexihibition::where('isDeleted', false)->find($id);

        // Check if exihibitin exists
        if (!$exihibition) {
            return response()->json([
                'error'  => 'exihibitionn not found',
                'status' => 0,
            ], 200);
        }

        return response(["exihibition" => $exihibition, "status" => 1], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tblexihibition $tblexihibition)
    {
        //
    }

    //Update the specified resource in storage.

   public function updateExihibition(Request $request,string $id)
   {

       // Find exihibitin by ID
       $exihibition = Tblexihibition::find($id);
       // dd($exihibitn);
       // Check if exihibitin exists
       if (!$exihibition) {
           return response()->json(['error' => 'exihibition not found','status'=>0], 200);
       }

       // Validate request data including file upload
       $request->validate([
        'event_name' => 'required|string|max:255',
        'type' => 'required|string|max:100|in:Art Exihibition,Car Exihibition',
        'exhibition_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'event_pricing' => 'nullable|numeric',
        'event_starting_date' => 'required|date',
        'event_ending_date' => 'required|date|after_or_equal:event_starting_date',
        'location' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
       ]);

       // Handle file upload
       $uploaded = null;
       if ($request->hasFile('exhibition_image')) {
           $image     = $request->file('exhibition_image');
           $imageName = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('images'), $imageName);
           $uploaded = $imageName;
       }

       DB::beginTransaction();
       try {
           $exihibition->update([
            'event_name' => $request->event_name,
            'type' => $request->type,
            'exhibition_image' => $uploaded,
            'event_pricing' => $request->event_pricing,
            'event_starting_date' => $request->event_starting_date,
            'event_ending_date' => $request->event_ending_date,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
           ]);

           DB::commit();

           return response([
               "message" => "exihibition Data Updated Successfully.",
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
   
   //Remove the specified resource from storage.

  public function destroyExihibition(string $id)
  {
      // Find exihibitin by ID
      $exihibition = Tblexihibition::find($id);
      if (is_null($exihibition)) {
          return response()->json([
              "message" => "exihibition doesn't exist.",
              "status"  => 0,
          ], 200);
      }

      // Check if the exihibitin has already been soft deleted
      if ($exihibition->isdeleted) {
          return response()->json([
              "message" => "Exibition already deleted",
              "status"  => 1,
          ], 200);
      }

      DB::beginTransaction();
      try {
          // Soft delete the exihibition
          $exihibition->isdeleted = true;
          $exihibition->save();

          // Soft delete associated exihibition detail if exists
        //   $exihibitinDetail = Tblexihibitin_detail::where('exihibitin_id', $id)->first();
        //   if ($exihibitinDetail) {
        //       $exihibitinDetail->isDeleted = true;
        //       $exihibitinDetail->save();
        //   }

          DB::commit();

          return response()->json([
              "message" => "Exihibition Deleted Successfully.",
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

  public function fetchArtExihibition()
  {
      // Find exihibitin by ID with its detail
      $exihibition = Tblexihibition::where('type', '!=', 'Car Exihibition')
      ->where('isDeleted', false)->get();

      // Check if exihibitin exists
      if (!$exihibition) {
          return response()->json([
              'error'  => 'exihibitionn not found',
              'status' => 0,
          ], 200);
      }

      return response(["exihibition" => $exihibition, "status" => 1], 200);
  }

  public function fetchCarExihibition()
  {
      // Find exihibitin by ID with its detail
      $exihibition = Tblexihibition::where('type', '!=', 'Art Exihibition')
      ->where('isDeleted', false)->get();

      // Check if exihibitin exists
      if (!$exihibition) {
          return response()->json([
              'error'  => 'exihibitionn not found',
              'status' => 0,
          ], 200);
      }

      return response(["exihibition" => $exihibition, "status" => 1], 200);
  }

}
