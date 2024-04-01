<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblupcommingcar extends Model
{
    use HasFactory;
    
    public $timestamps = false;

protected $fillable = ['name','carimage','city','time','date','description','isdeleted'];
}
