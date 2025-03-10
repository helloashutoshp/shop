<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userShipping extends Model
{
    use HasFactory;
    protected $table = "usershipping_address";
    protected $fillable = ['user_id','firstName','lastName','email','mobile','country_id','address','appartment','city','state','zip']; 
}
