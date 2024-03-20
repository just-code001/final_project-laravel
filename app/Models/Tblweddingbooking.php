<?php

namespace App\Models;

use App\Models\Tblclient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tblweddingbooking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['client_id',
    'name',
    'email',
    'address',
    'contact_number',
    'city',
    'guest_list',
    'specialServices'];

    public function client()
    {
        return $this->belongsTo(Tblclient::class, 'client_id', 'client_id');
    }
}
