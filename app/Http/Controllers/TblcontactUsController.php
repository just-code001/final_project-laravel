<?php

namespace App\Http\Controllers;

use App\Models\Tblcontact_us;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TblcontactUsController extends Controller
{
    public function insertContactUs(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'sender_name'    => 'required|string|max:255',
            'sender_email'   => 'required|email|max:255',
            'sender_contact' => 'required|string|max:255', // Added validation for sender_contact
            'message' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Create a new instance of the TblContactUs model
            $contactUs = new Tblcontact_us();

            // Assign values from the validated data to the model attributes
            $contactUs->sender_name    = $request->sender_name;
            $contactUs->sender_email   = $request->sender_email;
            $contactUs->sender_contact = $request->sender_contact; // Assign sender_contact value
            $contactUs->message        = $request->message;

            // Save the new record to the database
            $contactUs->save();

            DB::commit();
            // Return a success response
            return response()->json([
                'message' => 'Contact information saved successfully.',
                'status'  => 1,
                'info'    => 'We will Contact You in a short time.',
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

    public function fetchContactUsDetails()
    {
        try {
            // Fetch all records from the tblcontact_uses table
            $contactDetails = Tblcontact_us::all();

            // Return a success response with the fetched data
            return response()->json([
                'contact_details' => $contactDetails,
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
