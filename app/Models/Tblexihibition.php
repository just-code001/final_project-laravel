<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblexihibition extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['event_name', 'type', 'exhibition_image', 'event_pricing', 'event_starting_date', 'event_ending_date', 'location', 'city', 'state','isdeleted'];

}
