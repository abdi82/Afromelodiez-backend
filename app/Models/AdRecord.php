<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdRecord extends Model
{
    use HasFactory;

     protected $table = 'ad_records';
     protected $guarded = [];

     public function users()

    {
       return $this->belongsTo(User::class,'user_id');
    }

}
