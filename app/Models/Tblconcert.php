<?php

namespace App\Models;

use App\Models\Tblconcertbooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tblconcert extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'event_name',
        'singer',
        'event_timing',
        'concert_date',
        'city',
        'state',
        'pincode',
        'location',
        'concert_image',
        'description',
        'ticket_type1',
        'ticket_pricing1',
        'ticket_type2',
        'ticket_pricing2',
        'ticket_type3',
        'ticket_pricing3',
        'isdeleted', // Add the isdeleted field to the fillable array
    ];

    public function concertBookings()
    {
        return $this->hasMany(Tblconcertbooking::class, 'concert_id','id');
    }
}
