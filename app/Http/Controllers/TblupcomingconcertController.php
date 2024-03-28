<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Tblupcomingconcert;

use Illuminate\Http\Request;

class TblupcomingconcertController extends Controller
{
    public function createUpcomingConcert(Request $request)
    {
        $request->validate([
        'concert_date' => 'required|date|after_or_equal:today',
        'concert_singer' => 'required|string',
        'description' => 'required|string',
        ]);

        $upcomingConcertData=[
            'concert_date' => $request->concert_date,
            'concert_singer' => $request->concert_singer,
            'description' => $request->description,
             ];

             DB::beginTransaction();
             try {
                 $upcoming_concert = Tblupcomingconcert::create($upcomingConcertData);
     
                 // Create new exihibitionn detail
                 DB::commit();
     
             } catch (\Exception $ex) {
                 DB::rollBack();
     
                 $ex->getMessage();
                 $upcoming_concert = null;
            
     
             }
    
    
            return redirect()->back()->with('success', 'upcoming concert created successfully.');
        }
        

        // fetch all date

        public function fetchAllUpcominConcert()
     {
         $upcoming_concert = Tblupcomingconcert::where('isdeleted', false)->get();
         return response()->json([
             "upcoming_concert" => $upcoming_concert,
             "status" => 1,
         ], 200);
     }

     //fetch single data
    public function fetchSpecificUpcomingConcert($id)
    {
        // Find exihibitin by ID with its detail
        $upcoming_concert = Tblupcomingconcert::where('isDeleted', false)->find($id);

        // Check if exihibitin exists
        if (!$upcoming_concert) {
            return response()->json([
                'error'  => 'upcoming concert not found',
                'status' => 0,
            ], 200);
        }

        return response(["upcoming_concert" => $upcoming_concert, "status" => 1], 200);
    }


//Update the specified resource in storage.

public function updateUpcomingConcert(Request $request,string $id)
{

    // Find exihibitin by ID
    $upcoming_concert = Tblupcomingconcert::find($id);
    if (!$upcoming_concert) {
        return response()->json(['error' => 'upcoming concert not found','status'=>0], 200);
    }

    // Validate request data including file upload
    $request->validate([
        'concert_date' => 'required|date|after_or_equal:today',
        'concert_singer' => 'required|string',
        'description' => 'required|string',
    ]);


    DB::beginTransaction();
    try {
        $upcoming_concert->update([
            'concert_date' => $request->concert_date,
            'concert_singer' => $request->concert_singer,
            'description' => $request->description,
        ]);

        DB::commit();

        return response([
            "message" => "upcoming concert Data Updated Successfully.",
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

//delete upcoming concert
public function destroyUpcomingConcert(string $id)
   {
       // Find exihibitin by ID
       $upcoming_concert = Tblupcomingconcert::find($id);
       if (is_null($upcoming_concert)) {
           return response()->json([
               "message" => "upcoming concert doesn't exist.",
               "status"  => 0,
           ], 200);
       }
 
       // Check if the exihibitin has already been soft deleted
       if ($upcoming_concert->isdeleted) {
           return response()->json([
               "message" => "upcoming concert already deleted",
               "status"  => 1,
           ], 200);
       }
 
       DB::beginTransaction();
       try {
           // Soft delete the exihibition
           $upcoming_concert->isdeleted = true;
           $upcoming_concert->save();
 
 
           DB::commit();
 
           return response()->json([
               "message" => "upcoming concer Deleted Successfully.",
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
