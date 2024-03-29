<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblupcomingart extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['art_name', 'art_image', 'art_date','art_description','isdeleted'];
}
