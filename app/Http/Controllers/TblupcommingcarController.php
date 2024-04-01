<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Tblupcommingcar; // Use your Tblupcommingcar model

use Illuminate\Http\Request;

class TblupcommingcarController extends Controller
{
    public function createUpcommingCar(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'carimage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'city' => 'required|string',
            'time' => 'required',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        $uploaded = null;
        if ($request->hasFile('carimage')) {
            $image     = $request->file('carimage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $uploaded = $imageName;
        }

        $upcommingCarData = [
            'name' => $request->name,
            'carimage' => $uploaded,
            'city' => $request->city,
            'time' => $request->time,
            'date' => $request->date,
            'description' => $request->description,
        ];

        DB::beginTransaction();
        try {
            $upcommingCar = Tblupcommingcar::create($upcommingCarData);
            DB::commit();
            return response()->json(['message' => "Created Successfully", 'status' => 1], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage(), 'status' => 0], 200);
        }
    }

    public function fetchAllUpcommingCars()
    {
        $upcommingCars = Tblupcommingcar::where('isdeleted', false)->get();
        return response()->json(['upcomming_cars' => $upcommingCars, 'status' => 1], 200);
    }

    public function fetchSpecificUpcommingCar($id)
    {
        $upcommingCar = Tblupcommingcar::where('isDeleted', false)->find($id);
        if (!$upcommingCar) {
            return response()->json(['error' => 'Upcomming car not found', 'status' => 0], 404);
        }
        return response()->json(['upcomming_car' => $upcommingCar, 'status' => 1], 200);
    }

    public function updateUpcommingCar(Request $request, $id)
    {
        $upcommingCar = Tblupcommingcar::find($id);
        if (!$upcommingCar) {
            return response()->json(['error' => 'Upcomming car not found', 'status' => 0], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'carimage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'city' => 'required|string',
            'time' => 'required|date_format:H:i',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);
        $uploaded = null;
        if ($request->hasFile('carimage')) {
            $image     = $request->file('carimage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $uploaded = $imageName;
        }

        DB::beginTransaction();
        try {
            $upcommingCar->update($request->all());
            DB::commit();
            return response()->json(['message' => 'Upcomming car updated successfully.', 'status' => 1], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage(), 'status' => 0], 500);
        }
    }

    public function destroyUpcommingCar($id)
    {
        $upcommingCar = Tblupcommingcar::find($id);
        if (!$upcommingCar) {
            return response()->json(['message' => "Upcomming car doesn't exist.", 'status' => 0], 404);
        }

        if ($upcommingCar->isdeleted) {
            return response()->json(['message' => "Upcomming car already deleted", 'status' => 1], 200);
        }

        DB::beginTransaction();
        try {
            $upcommingCar->isdeleted = true;
            $upcommingCar->save();
            DB::commit();
            return response()->json(['message' => "Upcomming car deleted successfully.", 'status' => 1], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => "Internal Server Error" . $ex->getMessage(), 'status' => 0], 500);
        }
    }
}
