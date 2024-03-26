<?php

namespace App\Models;

use App\Models\Tblexhibitionbooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tblexihibition extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['event_name', 'type', 'exhibition_image', 'event_pricing', 'event_starting_date', 'event_ending_date', 'location', 'city', 'state','isdeleted'];

    public function exhibitionBookings()
    {
        return $this->hasMany(Tblexhibitionbooking::class, 'exhibition_id','id');
    }
}
