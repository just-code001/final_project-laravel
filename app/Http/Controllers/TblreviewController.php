<?php

namespace App\Http\Controllers;

use App\Models\Tblreview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TblreviewController extends Controller
{
        public function insertReview(Request $request)
        {
            // Validate incoming request data
            $validatedData = $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|email|max:255',
                'message' => 'required|string',
            ]);

            DB::beginTransaction();
            try {
                // Create a new instance of the TblContactUs model
                $review = new Tblreview();

                // Assign values from the validated data to the model attributes
                $review->name    = $request->name;
                $review->email   = $request->email;
                $review->message = $request->message;

                // Save the new record to the database
                $review->save();

                DB::commit();
                // Return a success response
                return response()->json([
                    'message' => 'review sent successfully.',
                    'status'  => 1,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                // Return an error response if an exception occurs
                return response()->json([
                    'message' => 'Failed to save contact information.',
                    'status'  => 0,
                    'error'   => $e->getMessage(), // Optionally include the error message for debugging
                ], 200);
            }
        }

    public function fetchReviewDetails()
    {
        try {
            // Fetch all records from the tblcontact_uses table
            $reviews = Tblreview::all();

            // Return a success response with the fetched data
            return response()->json([
                'reviews' => $reviews,
                'status'          => 1,
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            return response()->json([
                'message' => 'Failed to fetch contact details.',
                'status'  => 0,
                'error'   => $e->getMessage(), // Optionally include the error message for debugging
            ], 200);
        }
    }
}
