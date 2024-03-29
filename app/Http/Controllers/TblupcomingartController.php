<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Tblupcomingart;
use Illuminate\Http\Request;

class TblupcomingartController extends Controller
{
    public function createUpcomingArt(Request $request)
    {
        $request->validate([
            'art_name' => 'required|string|max:255',
            'art_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'art_date' => 'required|date|after_or_equal:today',
            'art_description' => 'required|string',
        ]);

        $uploaded = null;
        if ($request->hasFile('art_image')) {
            $image     = $request->file('art_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $uploaded = $imageName;
        }

        $upcoming_art=[
            'art_name' => $request->art_name,
            'art_image' => $uploaded,
            'art_date' => $request->art_date,
            'art_description' => $request->art_description,
        ];

        DB::beginTransaction();
         try {
             $upcoming_art = Tblupcomingart::create($upcoming_art);
 
             // Create new exihibitionn detail
             DB::commit();
 
         } catch (\Exception $ex) {
             DB::rollBack();
 
             $ex->getMessage();
             $upcoming_art = null;
        
 
         }


        return redirect()->back()->with('success', 'upcoming art data created successfully.');
    }

    //fetch all data

    public function fetchAllUpcomingArt()
    {
        $upcoming_art = Tblupcomingart::where('isdeleted', false)->get();
        return response()->json([
            "upcoming_art" => $upcoming_art,
            "status" => 1,
        ], 200);
    }

        //fetch single data
    public function fetchSpecificUpcomingArt($id)
    {
        // Find exihibitin by ID with its detail
        $upcoming_art = Tblupcomingart::where('isDeleted', false)->find($id);

        // Check if exihibitin exists
        if (!$upcoming_art) {
            return response()->json([
                'error'  => 'upcoming art not found',
                'status' => 0,
            ], 200);
        }

        return response(["upcoming_art" => $upcoming_art, "status" => 1], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tblupcomingart $Tblupcomingart)
    {
        //
    }
   

     //Update the specified resource in storage.

   public function updateUpcomingArt(Request $request,string $id)
   {

       // Find exihibitin by ID
       $upcoming_art = Tblupcomingart::find($id);
       // dd($exihibitn);
       // Check if exihibitin exists
       if (!$upcoming_art) {
           return response()->json(['error' => 'upcoming art not found','status'=>0], 200);
       }

       // Validate request data including file upload
       $request->validate([
        'art_name' => 'required|string|max:255',
            'art_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'art_date' => 'required|date|after_or_equal:today',
            'art_description' => 'required|string',
       ]);

       // Handle file upload
       $uploaded = null;
       if ($request->hasFile('art_image')) {
           $image     = $request->file('art_image');
           $imageName = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('images'), $imageName);
           $uploaded = $imageName;
       }

       DB::beginTransaction();
       try {
           $upcoming_art->update([
            'art_name' => $request->art_name,
            'art_image' => $uploaded,
            'art_date' => $request->art_date,
            'art_description' => $request->art_description,
           ]);

           DB::commit();

           return response([
               "message" => "upcoming art Data Updated Successfully.",
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

   public function destroyUpcomingArt(string $id)
   {
       // Find exihibitin by ID
       $upcoming_art = Tblupcomingart::find($id);
       if (is_null($upcoming_art)) {
           return response()->json([
               "message" => "upcoming art data doesn't exist.",
               "status"  => 0,
           ], 200);
       }
 
       // Check if the exihibitin has already been soft deleted
       if ($upcoming_art->isdeleted) {
           return response()->json([
               "message" => "upcoming art data already deleted",
               "status"  => 1,
           ], 200);
       }
 
       DB::beginTransaction();
       try {
           // Soft delete the exihibition
           $upcoming_art->isdeleted = true;
           $upcoming_art->save();
 
 
           DB::commit();
 
           return response()->json([
               "message" => "upcoming art data Deleted Successfully.",
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
