<?php

namespace App\Http\Controllers;

use Razorpay\Api\Api;
use Illuminate\Http\Request;
use App\Models\Tblvenue_booking;
use Illuminate\Support\Facades\DB;

class TblvenueBookingController extends Controller
{
    public function createBooking(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'venue_id'        => 'required|exists:tblvenues,id',
            'client_id'       => 'required|exists:tblclients,client_id',
            'client_name'     => 'required|string|max:255',
            'client_email'    => 'required|string|email|max:255',
            'contact_number'  => 'required|string|max:20',
            'venue_name'      => 'required|string|max:255',
            'no_of_guests'    => 'required|integer|min:1',
            'checkin_date'    => 'required|date',
            'checkout_date'   => 'required|date',
            'price'           => 'required|numeric|min:0',
            'payment_id'      => 'nullable|string',
            'razorpay_id'     => 'nullable|string',
            'payment_status'  => 'nullable|string|in:pending,completed',
            'special_request' => 'nullable|string',
        ]);

        // Razorpay code to create order
        $api = new Api('rzp_test_3qAmVq6F30jxx3', 'Vl2E4muPvkGvKy5KZI9Ds183');

        $orderData = [
            'receipt' => 'rcptid_11', // Replace with your logic to generate a unique receipt ID
            'amount' => $request->price * 100, // Convert price to paise (multiply by 100)
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
            'venue_id'        => $request->venue_id,
            'client_id'       => $request->client_id,
            'client_name'     => $request->client_name,
            'client_email'    => $request->client_email,
            'contact_number'  => $request->contact_number,
            'venue_name'      => $request->venue_name,
            'no_of_guests'    => $request->no_of_guests,
            'checkin_date'    => $request->checkin_date,
            'checkout_date'   => $request->checkout_date,
            'price'           => $request->price,
            'payment_id'      => $payment_id,
            'special_request' => $request->special_request,
        ];

        DB::beginTransaction();
        try {
            $booking = Tblvenue_booking::create($bookingData);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response([
                "message" => $ex->getMessage(),
                $booking  => null,
                "status"  => 0,
            ]);
        }

        // Return a success response
        if ($booking != null) {
            return response([
                'booking' => $booking,
                'message' => 'booking created successfully.',
                'status'  => 1,
            ], 201);
        } else {
            return response([
                'message' => 'internal server error.',
                'status'  => 0,
            ], 200);
        }
    }

    public function fetchBookingsForAdmin()
    {
        // Fetch all venue bookings
        $bookings = Tblvenue_booking::with('venue')->get();

        // Return the bookings data
        return response()->json(['bookings' => $bookings, "status" => 0], 200);
    }

    public function fetchSepcificVenueBookingDetail($id)
    {
        // Find venue by ID with its detail
        $venueBooking = Tblvenue_booking::find($id);

        // Check if venue exists
        if (!$venueBooking) {
            return response()->json([
                'error'  => 'Venue Booking not found',
                'status' => 0,
            ], 200);
        }

        return response(["venuebooking" => $venueBooking, "status" => 1], 200);
    }

    public function updatePaymentStatus(Request $request, string $id){
        $venueBooking = Tblvenue_booking::find($id);

        if (!$venueBooking) {
            return response()
                ->json([
                    'error'  => 'venue Booking not found',
                    "status" => 0,
                ], 200);
        }

        $request->validate([
            'payment_status'               => 'required|string|in:pending,completed',
        ]);

        DB::beginTransaction();
        try {
            $venueBooking->payment_status               = $request->payment_status;
            $venueBooking->save();

            DB::commit();

            return response([
                "message" => "Payment status Updated Successfully.",
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

    public function fetchVenueBookingSpecificClient(string $client_id){
        $booking = Tblvenue_booking::where('client_id',$client_id)->paginate(3);

        // Check if booking exists
        if (!$booking) {
            return response()->json([
                'error'  => 'booking not found',
                'status' => 0,
            ], 200);
        }

        return response(["booking" => $booking, "status" => 1], 200);

    }
}
