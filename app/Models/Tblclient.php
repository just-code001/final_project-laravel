<?php

namespace App\Models;

use App\Models\Tblvenue_booking;
use App\Models\Tblconcertbooking;
use App\Models\Tblweddingbooking;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Tblbirthdaybooking;
use App\Models\Tblexhibitionbooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tblclient extends Model
{
    use HasApiTokens,HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'client_id';

    protected $fillable = ['firstname', 'lastname', 'email', 'phno', 'email_verified_at', 'password', 'otp'];

    /**
     * Get the bookings associated with the client.
     */
    public function venueBookings()
    {
        return $this->hasMany(Tblvenue_booking::class, 'client_id','client_id');
    }

    public function concertBookings()
    {
        return $this->hasMany(Tblconcertbooking::class, 'client_id','client_id');
    }

    public function exhibitionBookings()
    {
        return $this->hasMany(Tblexhibitionbooking::class,'client_id','client_id');
    }

    public function weddingBookings()
    {
        return $this->hasMany(Tblweddingbooking::class,'client_id','client_id');
    }
    public function birthdayBookings()
    {
        return $this->hasMany(Tblbirthdaybooking::class,'client_id','client_id');
    }



    protected $hidden = [
        'password',
        'otp',
    ];
}
