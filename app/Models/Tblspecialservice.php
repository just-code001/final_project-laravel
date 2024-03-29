<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblspecialservice extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['service_name', 'service_image', 'other_img1',
        'other_img2',
        'other_img3',
        'description',
        'testimonial',
        'isdeleted'];
}
