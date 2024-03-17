<?php

namespace App\Models;

use App\Models\Tblclient;
use App\Models\Tblvenues;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblvenue_booking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['venue_id', 'client_id', 'client_name', 'client_email', 'contact_number', 'venue_name', 'no_of_guests',
        'checkin_date',
        'checkout_date',
        'price',
        'payment_id',
        'razorpay_id',
        'payment_status',
        'special_request'];

    /**
     * Get the venue that the booking belongs to.
     */
    public function venue()
    {
        return $this->belongsTo(Tblvenues::class, 'venue_id', 'id');
    }

    /**
     * Get the client that owns the booking.
     */
    public function client()
    {
        return $this->belongsTo(Tblclient::class, 'client_id', 'client_id');
    }
}
