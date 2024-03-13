<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblvenue_detail extends Model
{
    use HasFactory;

    public $timestamps=false;

    protected $fillable = ['venue_id', 'description', 'city', 'state', 'pincode', 'location', 'contact', 'food_facility', 'special_facility'];

    public function venue()
    {
        return $this->belongsTo(Venue::class,'venue_id','id');
    }
}
