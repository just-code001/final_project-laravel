<?php

namespace App\Models;

use App\Models\Tblvenues;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tblvenue_detail extends Model
{
    use HasFactory;

    public $timestamps=false;

    protected $fillable = ['venue_id', 'description', 'city', 'state', 'pincode', 'location', 'contact', 'food_facility', 'special_facility'];

    public function venue()
    {
        return $this->belongsTo(Tblvenues::class,'venue_id','id');
    }
}
