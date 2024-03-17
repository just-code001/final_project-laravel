<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tblvenue_detail;
use App\Models\Tblvenue_booking;

class Tblvenues extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'venue_category', 'venue_image', 'price', 'rating', 'venue_capacity', 'status'];

    public function detail()
    {
        return $this->hasOne(Tblvenue_detail::class,'venue_id','id');
    }

     /**
     * Get the bookings associated with the venue.
     */
    public function venueBookings()
    {
        return $this->hasMany(Tblvenue_booking::class, 'venue_id','id');
    }
}
