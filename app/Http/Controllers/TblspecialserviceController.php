<?php

namespace App\Http\Controllers;

use App\Models\Tblspecialservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TblspecialserviceController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'service_name'  => 'required|string|max:100',
            'service_image' => 'required|image|mimes:jpeg,png,jpg,webp',
            'other_img1'    => 'required|image|mimes:jpeg,png,jpg,gif',
            'other_img2'    => 'required|image|mimes:jpeg,png,jpg,gif',
            'other_img3'    => 'required|image|mimes:jpeg,png,jpg,gif',
            'description'   => 'nullable|string',
            'testimonial'   => 'nullable|string',
        ]);

        // Handle file upload
        $service_image = null;
        if ($request->hasFile('service_image')) {
            $image     = $request->file('service_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $service_image = $imageName;
        }

        $other_img1 = null;
        if ($request->hasFile('other_img1')) {
            $image     = $request->file('other_img1');
            $imageName = time()+1 . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $other_img1 = $imageName;
        }
        $other_img2 = null;
        if ($request->hasFile('other_img2')) {
            $image     = $request->file('other_img2');
            $imageName = time()+2 . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $other_img2 = $imageName;
        }
        $other_img3 = null;
        if ($request->hasFile('other_img3')) {
            $image     = $request->file('other_img3');
            $imageName = time()+3 . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $other_img3 = $imageName;
        }

        $serviceData = [
            'service_name'  => $request->service_name,
            'service_image' => $service_image,
            'other_img1'    => $other_img1,
            'other_img2'    => $other_img2,
            'other_img3'    => $other_img3,
            'description'   => $request->description,
            'testimonial'   => $request->testimonial,
        ];

        // Create new service
        DB::beginTransaction();
        try {
            $service = Tblspecialservice::create($serviceData);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                $service = null,
            ]);
        }

        if ($service != null) {
            return response([
                'service' => $service,
                'message' => 'service created successfully.',
                'status'  => 1,
            ], 201);
        } else {
            return response([
                'message' => 'internal server error.',
                'status'  => 0,
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        // Find the special service by ID
        $service = Tblspecialservice::find($id);

        // Validate incoming request
        $request->validate([
            'service_name'  => 'required|string|max:100',
            'service_image' => 'required|image|mimes:jpeg,png,jpg,webp',
            'other_img1'    => 'required|image|mimes:jpeg,png,jpg,gif',
            'other_img2'    => 'required|image|mimes:jpeg,png,jpg,gif',
            'other_img3'    => 'required|image|mimes:jpeg,png,jpg,gif',
            'description'   => 'nullable|string',
            'testimonial'   => 'nullable|string',
        ]);

        // Handle file upload
        $service_image = null;
        if ($request->hasFile('service_image')) {
            $image     = $request->file('service_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $service_image = $imageName;
        }

        $other_img1 = null;
        if ($request->hasFile('other_img1')) {
            $image     = $request->file('other_img1');
            $imageName = time()+1 . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $other_img1 = $imageName;
        }
        $other_img2 = null;
        if ($request->hasFile('other_img2')) {
            $image     = $request->file('other_img2');
            $imageName = time()+2 . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $other_img2 = $imageName;
        }
        $other_img3 = null;
        if ($request->hasFile('other_img3')) {
            $image     = $request->file('other_img3');
            $imageName = time()+3 . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/service_images'), $imageName);
            $other_img3 = $imageName;
        }

        $serviceData = [
            'service_name'  => $request->service_name,
            'service_image' => $service_image,
            'other_img1'    => $other_img1,
            'other_img2'    => $other_img2,
            'other_img3'    => $other_img3,
            'description'   => $request->description,
            'testimonial'   => $request->testimonial,
        ];

        // Create new service
        DB::beginTransaction();
        try {
            $service->update($serviceData);

            DB::commit();

            return response([
                "message" => "service Data Updated Successfully.",
                "status"  => 1,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                $service = null,
            ]);
        }
    }

    public function destroySpecialService(Request $request, string $id)
    {
        // Find service by ID
        $service = Tblspecialservice::find($id);
        if (is_null($service)) {
            return response()->json([
                "message" => "service doesn't exist.",
                "status"  => 0,
            ], 200);
        }

        // Check if the service has already been soft deleted
        if ($service->isdeleted) {
            return response()->json([
                "message" => "service already deleted",
                "status"  => 1,
            ], 200);
        }

        DB::beginTransaction();
        try {
            // Soft delete the service
            $service->isdeleted = true;
            $service->save();

            DB::commit();

            return response()->json([
                "message" => "service Deleted Successfully.",
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

    public function fetchAllSpecialServices()
    {
        $service = Tblspecialservice::where('isdeleted', false)->get();
        return response()->json([
            "service" => $service,
            "status"   => 1,
        ], 200);
    }

    public function fetchSepcificConcert($id)
    {
        // Find concert by ID with its detail
        $service = Tblspecialservice::where('isDeleted', false)->find($id);

        // Check if service exists
        if (!$service) {
            return response()->json([
                'error'  => 'service not found',
                'status' => 0,
            ], 200);
        }

        return response(["service" => $service, "status" => 1], 200);
    }
}
