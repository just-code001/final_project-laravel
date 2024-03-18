<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Tblvenue_booking;

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

    protected $hidden = [
        'password',
        'otp',
    ];
}
