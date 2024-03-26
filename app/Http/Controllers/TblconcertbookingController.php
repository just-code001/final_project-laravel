<?php

namespace App\Http\Controllers;

use App\Models\Tblconcertbooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class TblconcertbookingController extends Controller
{
    public function createBooking(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'concert_id'     => 'required|exists:tblconcerts,id',
            'client_id'      => 'required|exists:tblclients,client_id',
            'client_name'    => 'required|string|max:255',
            'client_email'   => 'required|string|email|max:255',
            'contact_number' => 'required|string|max:20',
            'concert_name'   => 'required|string|max:255',
            'no_of_tickets'  => 'required|integer|min:1',
            'booking_date'   => 'required|date',
            'ticket_type'    => 'required|string',
            'price'          => 'required|numeric|min:0', // Assuming you have a fixed ticket price per unit
            'payment_id' => 'nullable|string',
            'razorpay_id'    => 'nullable|string',
            'payment_status' => 'nullable|string|in:pending,completed',
        ]);

        // Calculate total price based on number of tickets and ticket price per unit
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
            'concert_id'     => $request->concert_id,
            'client_id'      => $request->client_id,
            'client_name'    => $request->client_name,
            'client_email'   => $request->client_email,
            'contact_number' => $request->contact_number,
            'concert_name'   => $request->concert_name,
            'no_of_tickets'  => $request->no_of_tickets,
            'booking_date'   => $request->booking_date,
            'ticket_type'    => $request->ticket_type,
            'price'          => $totalPrice, // Use the calculated total price
            'payment_id' => $payment_id,
            'payment_status'=>'completed'
        ];

        DB::beginTransaction();
        try {
            $booking = Tblconcertbooking::create($bookingData);
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

    public function fetchConcertBookings()
    {
        // Fetch all venue bookings
        $bookings = Tblconcertbooking::get();

        // Return the bookings data
        return response()->json(['bookings' => $bookings, "status" => 0], 200);
    }

    public function fetchConcertBookingSpecificClient(string $client_id){
        $concert_booking = Tblconcertbooking::where('client_id',$client_id)->paginate(3);

        // Check if booking exists
        if (!$concert_booking) {
            return response()->json([
                'error'  => 'concert booking not found',
                'status' => 0,
            ], 200);
        }

        return response(["concert_booking" => $concert_booking, "status" => 1], 200);

    }
}
