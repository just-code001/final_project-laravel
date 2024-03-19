<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Tblpackage;

use Illuminate\Http\Request;

class TblPackageController extends Controller
{
    public function createPackage(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'packagename' => 'required',
            'packageimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'packagepricing' => 'required|numeric',
            'description' => 'required',
        ]);

        $uploaded = null;
        if ($request->hasFile('packageimage')) {
            $image     = $request->file('packageimage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $uploaded = $imageName;
        }

        $packageData=[
            'type' => $request->type,
            'packagename' => $request->packagename,
            'packageimage' => $uploaded,
            'packagepricing' => $request->packagepricing,
            'description' => $request->description,
        ];

        DB::beginTransaction();
         try {
             $package = Tblpackage::create($packageData);
 
             // Create new exihibitionn detail
             DB::commit();
 
         } catch (\Exception $ex) {
             DB::rollBack();
 
             $ex->getMessage();
             $package = null;
        
 
         }


        return redirect()->back()->with('success', 'Package created successfully.');
    }

    //fetch all data

    public function fetchAllPackage()
    {
        $package = Tblpackage::where('isdeleted', false)->get();
        return response()->json([
            "package" => $package,
            "status" => 1,
        ], 200);
    }

        //fetch single data
    public function fetchSpecificPackage($id)
    {
        // Find exihibitin by ID with its detail
        $package = Tblpackage::where('isDeleted', false)->find($id);

        // Check if exihibitin exists
        if (!$package) {
            return response()->json([
                'error'  => 'package not found',
                'status' => 0,
            ], 200);
        }

        return response(["package" => $package, "status" => 1], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tblpackage $Tblpackage)
    {
        //
    }
   

     //Update the specified resource in storage.

   public function updatePackage(Request $request,string $id)
   {

       // Find exihibitin by ID
       $package = Tblpackage::find($id);
       // dd($exihibitn);
       // Check if exihibitin exists
       if (!$package) {
           return response()->json(['error' => 'package not found','status'=>0], 200);
       }

       // Validate request data including file upload
       $request->validate([
        'type' => 'required',
        'packagename' => 'required',
        'packageimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'packagepricing' => 'required|numeric',
        'description' => 'required',
       ]);

       // Handle file upload
       $uploaded = null;
       if ($request->hasFile('packageimage')) {
           $image     = $request->file('packageimage');
           $imageName = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('images'), $imageName);
           $uploaded = $imageName;
       }

       DB::beginTransaction();
       try {
           $package->update([
            'type' => $request->type,
            'packagename' => $request->packagename,
            'packageimage' => $uploaded,
            'packagepricing' => $request->packagepricing,
            'description' => $request->description,
           ]);

           DB::commit();

           return response([
               "message" => "package Data Updated Successfully.",
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

   public function destroyPackage(string $id)
   {
       // Find exihibitin by ID
       $package = Tblpackage::find($id);
       if (is_null($package)) {
           return response()->json([
               "message" => "package doesn't exist.",
               "status"  => 0,
           ], 200);
       }
 
       // Check if the exihibitin has already been soft deleted
       if ($package->isdeleted) {
           return response()->json([
               "message" => "package already deleted",
               "status"  => 1,
           ], 200);
       }
 
       DB::beginTransaction();
       try {
           // Soft delete the exihibition
           $package->isdeleted = true;
           $package->save();
 
 
           DB::commit();
 
           return response()->json([
               "message" => "package Deleted Successfully.",
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
