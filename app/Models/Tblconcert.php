<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
