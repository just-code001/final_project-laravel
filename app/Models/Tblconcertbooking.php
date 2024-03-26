<?php

namespace App\Models;

use App\Models\Tblclient;
use App\Models\Tblconcert;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblconcertbooking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'concert_id',
        'client_id',
        'client_name',
        'client_email',
        'contact_number',
        'concert_name',
        'no_of_tickets',
        'booking_date',
        'ticket_type',
        'price',
        'payment_id',
        'razorpay_id',
        'payment_status',
    ];

    public function concert()
    {
        return $this->belongsTo(Tblconcert::class, 'concert_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Tblclient::class, 'client_id', 'client_id');
    }
}
