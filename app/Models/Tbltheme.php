<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbltheme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['type', 'themename', 'themeimage','isdeleted'];
}
