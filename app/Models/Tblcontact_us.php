<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblcontact_us extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sender_name',
        'sender_email',
        'sender_contact',
        'message',
    ];
}
