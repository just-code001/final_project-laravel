<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tblclient extends Model
{
    use HasApiTokens,HasFactory;

    public $timestamps = false;

    protected $fillable = ['firstname', 'lastname', 'email', 'phno', 'email_verified_at', 'password', 'otp'];
}
