<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblupcomingconcert extends Model
{
    use HasFactory;

    public $timestamps = false;

protected $fillable = ['concert_date', 'concert_singer', 'description','isdeleted'];
}
