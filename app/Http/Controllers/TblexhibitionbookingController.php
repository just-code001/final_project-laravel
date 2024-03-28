<?php

namespace App\Http\Controllers;

use App\Models\Tblexhibitionbooking;
use App\Rules\DateWithinExhibitionDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class TblexhibitionbookingController extends Controller
{
    public function createExhibitionBooking(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'exhibition_id'   => 'required|exists:tblexihibitions,id',
            'client_id'       => 'required|exists:tblclients,client_id',
            'client_name'     => 'required|string|max:255',
            'client_email'    => 'required|string|email|max:255',
            'contact_number'  => 'required|string|max:20',
            'exhibition_name' => 'required|string|max:255',
            'no_of_tickets'   => 'required|integer|min:1',
            'booking_date'    => [
                'required',
                'date',
                // Apply the custom validation rule for booking date within exhibition dates
                new DateWithinExhibitionDates($request->exhibition_id),'after_or_equal:today'
            ],
            'exhibition_type' => 'required|string|max:50|in:Art Exihibition,Car Exihibition',
            'price'           => 'required|numeric|min:0',
            'payment_id'      => 'nullable|string',
            'razorpay_id'     => 'nullable|string',
            'payment_status'  => 'nullable|string|in:pending,completed',
        ]);

        // Calculate total price based on number of tickets and ticket price
        $totalPrice = $request->no_of_tickets * $request->price;

        // Razorpay code to create order
        $api = new Api('rzp_test_3qAmVq6F30jxx3', 'Vl2E4muPvkGvKy5KZI9Ds183');

        $orderData = [
            'receipt' => 'rcptid_11', // Replace with your logic to generate a unique receipt ID
            'amount' => $totalPrice * 100, // Convert price to paise (multiply by 100)
            'currency' => 'INR',
        ];

        $payment_id = null;
        try {
            $razorpayOrder = $api->order->create($orderData);
            $payment_id    = $razorpayOrder->id; // Merge the generated Razorpay ID into the request
        } catch (\Exception $ex) {
            return response([
                "message" => $ex->getMessage(),
                "status"  => 0,
            ]);
        }

        $bookingData = [
            'exhibition_id'   => $request->exhibition_id,
            'client_id'       => $request->client_id,
            'client_name'     => $request->client_name,
            'client_email'    => $request->client_email,
            'contact_number'  => $request->contact_number,
            'exhibition_name' => $request->exhibition_name,
            'no_of_tickets'   => $request->no_of_tickets,
            'booking_date'    => $request->booking_date,
            'exhibition_type' => $request->exhibition_type,
            'price'           => $totalPrice, // Use the calculated total price
            'payment_id' => $payment_id,
            'payment_status'  => 'completed', // Assuming payment is completed upon booking creation
        ];

        DB::beginTransaction();
        try {
            $booking = Tblexhibitionbooking::create($bookingData);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                "status"  => 0,
            ]);
        }

        // Return a success response
        if ($booking != null) {
            return response([
                'booking' => $booking,
                'message' => 'Booking created successfully.',
                'status'  => 1,
            ], 201);
        } else {
            return response([
                'message' => 'Internal server error.',
                'status'  => 0,
            ], 500);
        }
    }
    public function fetchExihibitionBookings()
    {
        // Fetch all venue bookings
        $bookings = Tblexhibitionbooking::get();

        // Return the bookings data
        return response()->json(['bookings' => $bookings, "status" => 0], 200);
    }

    public function fetchExhibitionBookingSpecificClient(string $client_id)
    {
        $exhibition_booking = Tblexhibitionbooking::where('client_id', $client_id)->paginate(3);

        // Check if booking exists
        if (!$exhibition_booking) {
            return response()->json([
                'error'  => 'exhibition booking not found',
                'status' => 0,
            ], 200);
        }

        return response(["exhibition_booking" => $exhibition_booking, "status" => 1], 200);

    }
}
