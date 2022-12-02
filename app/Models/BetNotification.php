<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetNotification extends Model
{
    use HasFactory;

     protected $fillable = [
        'bet_id',
        'title',
        'description',
        'user_id',
        'read',
       
    ];
}
