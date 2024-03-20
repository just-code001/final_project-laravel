<?php

namespace App\Models;

use App\Models\Tblexihibition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tblexhibitionbooking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'exhibition_id',
        'client_id',
        'client_name',
        'client_email',
        'contact_number',
        'exhibition_name',
        'no_of_tickets',
        'booking_date',
        'exhibition_type',
        'price',
        'payment_id',
        'razorpay_id',
        'payment_status',
    ];

    // Define relationships as mentioned earlier
    public function exhibition()
    {
        return $this->belongsTo(Tblexihibition::class, 'exhibition_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Tblclient::class, 'client_id', 'client_id');
    }
}
