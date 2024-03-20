<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Tbltheme;

use Illuminate\Http\Request;

class TblthemeController extends Controller
{
    public function createTheme(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'themename' => 'required|string',
            'themeimage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $uploaded = null;
        if ($request->hasFile('themeimage')) {
            $image     = $request->file('themeimage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $uploaded = $imageName;
        }

        $themeData=[
            'type' => $request->type,
            'themename' => $request->themename,
            'themeimage' => $uploaded,
             ];

        DB::beginTransaction();
         try {
             $theme = Tbltheme::create($themeData);
 
             // Create new exihibitionn detail
             DB::commit();
 
         } catch (\Exception $ex) {
             DB::rollBack();
 
             $ex->getMessage();
             $theme = null;
        
 
         }


        return redirect()->back()->with('success', 'Theme created successfully.');
    }

     //fetch all data

     public function fetchAllTheme()
     {
         $theme = Tbltheme::where('isdeleted', false)->get();
         return response()->json([
             "theme" => $theme,
             "status" => 1,
         ], 200);
     }
 
     //fetch single data
    public function fetchSpecificTheme($id)
    {
        // Find exihibitin by ID with its detail
        $theme = Tbltheme::where('isDeleted', false)->find($id);

        // Check if exihibitin exists
        if (!$theme) {
            return response()->json([
                'error'  => 'theme not found',
                'status' => 0,
            ], 200);
        }

        return response(["theme" => $theme, "status" => 1], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tbltheme $Tbltheme)
    {
        //
    }

      //Update the specified resource in storage.

   public function updateTheme(Request $request,string $id)
   {

       // Find exihibitin by ID
       $theme = Tbltheme::find($id);
       // dd($exihibitn);
       // Check if exihibitin exists
       if (!$theme) {
           return response()->json(['error' => 'theme not found','status'=>0], 200);
       }

       // Validate request data including file upload
       $request->validate([
        'type' => 'required|string',
            'themename' => 'required|string',
            'themeimage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
       ]);

       // Handle file upload
       $uploaded = null;
       if ($request->hasFile('themeimage')) {
           $image     = $request->file('themeimage');
           $imageName = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('images'), $imageName);
           $uploaded = $imageName;
       }

       DB::beginTransaction();
       try {
           $theme->update([
            'type' => $request->type,
            'themename' => $request->themename,
            'themeimage' => $uploaded,
           ]);

           DB::commit();

           return response([
               "message" => "theme Data Updated Successfully.",
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


   public function destroyTheme(string $id)
   {
       // Find exihibitin by ID
       $theme = Tbltheme::find($id);
       if (is_null($theme)) {
           return response()->json([
               "message" => "theme doesn't exist.",
               "status"  => 0,
           ], 200);
       }
 
       // Check if the exihibitin has already been soft deleted
       if ($theme->isdeleted) {
           return response()->json([
               "message" => "theme already deleted",
               "status"  => 1,
           ], 200);
       }
 
       DB::beginTransaction();
       try {
           // Soft delete the exihibition
           $theme->isdeleted = true;
           $theme->save();
 
 
           DB::commit();
 
           return response()->json([
               "message" => "theme Deleted Successfully.",
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
